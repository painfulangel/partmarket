<?php

class MailBoxLoad extends CConsoleCommand
{
    /**
     * @var array логи процесса для администратора
     */
    public $admin_logs = array();

    /**
     * Идентификатор правила по умолчанию
     * @var int
     */
    public $id = 0;

    /**
     * @var null атрибут модели по умолчанию null
     */
    public $model = null;

    public $mailAddress = '';
    public $mailPort = '';

    /**
     * Запуск консольной команды.
     * В качестве параметра передается id mail бокса
     * @param array $args
     * @return int
     * @throws CHttpException
     */
    public function run($args)
    {

        $queue = PricesAutoloadQueue::model()->count();
        $queue = intval($queue);
        if($queue == 0){
            Yii::app()->db->createCommand("SET SESSION innodb_lock_wait_timeout = 180;")->execute();
            set_time_limit(1200);
            $this->id = $id = $args[0];
            set_error_handler(array($this, 'myErrorHandler'), E_ERROR);

            //количество загруженных
            $all_download_count = 0;
            /**
             * Модель почтового бокса
             */
            $model = $this->loadModel($id);
            $this->model = $model;

            $transaction = Yii::app()->db->beginTransaction();

            try {
                if ($model && $model->state == 1) {

                    $timeout = Yii::app()->db->createCommand("SELECT @@innodb_lock_wait_timeout;")->queryScalar();
                    echo 'timeout='.$timeout.PHP_EOL;

                    $flag_success = false;
                    $this->admin_logs[] = 'Starting the rule №' . $id;
                    CronLogs::log('Starting the rule №' . $id, 'Price Auto Loader');
                    $this->admin_logs[] = 'Choose method Email';


                    /**
                     * Если директории не существует нужно создать ее
                     * Иначе удалить старую и создать новую
                     */
                    $path_email = $this->checkEmailDirectory($model);

                    /**
                     * Получить адрес и порт для соединения с почтовым сервером
                     */
                    $this->getPortAndAddress($model);

                    /**
                     * соединяемся с почтовым сервером
                     */
                    //$connectImap = imap_open('{' . $this->mailAddress . ':' . $this->mailPort . '/'.$model->consoleProtocol.'/novalidate-cert}INBOX', $model->mailbox, $model->password) OR $this->admin_logs[] = 'Error connecting to the postal address: ' . imap_last_error();

                    //!!! Условия отбора писем
                    $criteria = array();

                    $criteria = $this->addConditionLoadType($model, $criteria);

                    $part_search_string = implode(' ', $criteria);

                    //источники
                    if($model->sources){

                        foreach ($model->sources as $source) {

                            $connectImap = imap_open('{' . $this->mailAddress . ':' . $this->mailPort . '/'.$model->consoleProtocol.'/novalidate-cert}INBOX', $model->mailbox, $model->password) OR $this->admin_logs[] = 'Error connecting to the postal address: ' . imap_last_error();

                            $download_count = 0;
                            $criteria2 = '';
                            if (!empty($source->mail_from)) {
                                $criteria2 .= ' FROM "' . $source->mail_from . '"';
                            }

                            if (!empty($source->mail_subject)) {
                                $criteria2 .= ' SUBJECT "' . $source->mail_subject . '"';
                            }

                            if (!empty($source->mail_body)) {
                                $criteria2 .= ' BODY "' . $source->mail_body . '"';
                            }

                            $searchCondition = $criteria2.' '.$part_search_string;

                            $this->admin_logs[] = 'Mail conditions ' . $searchCondition;

                            /**
                             * Тестирование ящика
                             */
                            echo '===========Правило №'.$source->id.' '.$source->rule_name.'=============='.PHP_EOL;
                            echo $searchCondition.PHP_EOL;
                            echo '======================================'.PHP_EOL;
                            //возвращается массив id писем
                            $mails = imap_search($connectImap, $searchCondition);

                            //если есть письма подходящие под критерию
                            if($mails){
                                //echo 'есть письма'.PHP_EOL;
                                // открываем каждое новое письмо
                                foreach ($mails as $j => $oneMail) {
                                    //echo '================================================'.PHP_EOL;
                                    echo 'Receiving Messages id=' . $oneMail.PHP_EOL;
                                    $this->admin_logs[] = 'Receiving Messages id=' . $oneMail;
                                    //если не прошло проверку письмо, пропускаем итерацию
                                    if (!$this->mailCheck($model, $connectImap, $oneMail)) {
                                        echo 'Не прошло проверку ' . $oneMail.PHP_EOL;
                                        continue;
                                    }

                                    /* Прочитать структуру указанного сообщения */
                                    $structure = imap_fetchstructure($connectImap, $oneMail);
                                    //Заголовок письма. Нужен для получения даты
                                    $header = imap_headerinfo($connectImap, $oneMail);
                                    //вложения
                                    $attachments = array();

                                    /* if any attachments found... */
                                    if (isset($structure->parts) && count($structure->parts)) {
                                        for ($i = 0; $i < count($structure->parts); $i++) {
                                            $attachments = $this->fillArrayAttachments($attachments, $i, $structure, $connectImap, $oneMail);
                                        }
                                    }

                                    if (count($attachments)) {
                                        //echo 'вложений ' .count($attachments).PHP_EOL;
                                        $download_flag = false;

                                        $counter = 0;

                                        /* iterate through each attachment and save it */
                                        foreach ($attachments as $attachment) {
                                            //если это вложение, нужно обработать его
                                            if ($attachment['is_attachment'] == 1) {

                                                //получить имя файла вложения
                                                $filename = trim($attachment['name']);

                                                if (empty($filename)){
                                                    $filename = trim($attachment['filename']);
                                                }
                                                //если имя файла не пустое
                                                if ($filename) {

                                                    $search_file_criteria = trim($source->search_file_criteria);

                                                    echo $filename.PHP_EOL;
                                                    //если имя файла не прошло критерий, пропустить итреацию
                                                    if ((!empty($search_file_criteria)) && !strpos('1qq1' . mb_strtoupper($filename), mb_strtoupper($search_file_criteria))) {
                                                        $this->admin_logs[] = 'Файл ' . $filename . ' не прошел критерий ' . $search_file_criteria . ' в обработке';
                                                        echo 'Файл ' . $filename . ' не прошел критерий ' . $search_file_criteria.PHP_EOL;
                                                        continue;
                                                    }

                                                    $this->admin_logs[] = 'Файл ' . $filename . ' в обработке';


                                                    $flag_success = true;
                                                    $download_flag = true;

                                                    //echo 'Файл ' . $filename . ' в обработке'.PHP_EOL;

                                                    //извлечь расширение из имени файла
                                                    $ext = str_replace('?=', '', pathinfo($filename, PATHINFO_EXTENSION));

                                                    //echo 'ext='.$ext.PHP_EOL;
                                                    $ext = $this->defineExt($ext, $path_email, $attachment);
                                                    if($ext === false){
                                                        continue;
                                                    }


                                                    if (strtolower($ext) == 'zip') {

                                                        $this->admin_logs[] = 'Processing a zip' . $filename;

                                                        /**
                                                         * Распаковываем архив и записываем в переменную количество распакованных файлов
                                                         */
                                                        $download_count = $this->handleZip($source, $oneMail, $ext, $path_email, $download_count, $model, $attachment);

                                                    } else {
                                                        //Название прайса
                                                        $name = pathinfo($filename, PATHINFO_BASENAME);
                                                        //echo 'Name a file ' . $name.PHP_EOL;
                                                        if (preg_match('/[^a-z\.]+/i', $name)){
                                                            $name = uniqid().'-'.$source->id.'-'.$oneMail."-price." . $ext;
                                                        }else{
                                                            $name = uniqid().'-'.$source->id.'-'.$oneMail."-price." . $ext;
                                                        }

                                                        //echo 'Processing a file' . $name.PHP_EOL;

                                                        $this->admin_logs[] = $name.' - '.pathinfo($filename, PATHINFO_BASENAME).' - '.intval(preg_match('/[^a-z\.]+/i', pathinfo($filename, PATHINFO_BASENAME)));

                                                        file_put_contents($path_email.$name, $attachment['attachment']);
                                                        /**
                                                         * Здесь делать запись в очередь
                                                         */
                                                        $queue = new PricesAutoloadQueue();
                                                        $queue->rule_id = $source->id;
                                                        $queue->store_id = $source->store_id;
                                                        $queue->path = $path_email;
                                                        $queue->filename = $name;
                                                        $queue->created = time();
                                                        $queue->save();
                                                        $download_count++;

                                                    }
                                                }
                                            }
                                        }

                                        /**
                                         * Если установлен флаг, что прайс загружен, отметить письмо как просмотренное
                                         */
                                        if ($download_flag) {
                                            $this->admin_logs[] = 'Set flag as viewed';
                                            //echo 'Set flag as viewed'.PHP_EOL;
                                            //imap_setflag_full($connectImap, $oneMail, '\\Seen');
                                        }
                                    } else {
                                        $this->admin_logs[] = 'No files in id=' . $oneMail;
                                    }

                                    /**
                                     * Если установлен флаг "Удалять старые", отметить письмо для удаления
                                     */
                                    if (intval($model->delete_old) == 1) {
                                        //Пометить сообщение для удаления
                                        $res = imap_delete($connectImap, $oneMail);

                                        $this->admin_logs[] = 'Message ' . $oneMail . ' is deleted ' . intval($res);
                                    }

                                    if ($model->expire) {
                                        //Пометить сообщение старше Х дней для удаления
                                        $mailDate = strtotime($header->date);
                                        $controlDate = strtotime("-{$model->expire} days");
                                        if($mailDate < $controlDate){
                                            $res = imap_delete($connectImap, $oneMail);
                                            $this->admin_logs[] = 'Message ' . $oneMail . ' is deleted after '.$model->expire .' days' . intval($res);
                                            //echo 'Message ' . $oneMail . ' is deleted after '.$model->expire .' days' . intval($res).PHP_EOL;
                                        }
                                    }
                                }

                                if (intval($model->delete_old) == 1 || $model->expire) {
                                    //Удалить все помеченные для удаления сообщения
                                    $res = imap_expunge($connectImap);

                                    $this->admin_logs[] = 'Delete result ' . intval($res);
                                }
                            }

                            //Количество загруженных файлов
                            $source->download_time = time();
                            $source->mail_file = $download_count;
                            if(!$source->save()){
                                $this->sendDie($source);
                            }else{
                                $this->sendAdmiMsg($source);
                            }
                            $all_download_count += $download_count;

                            ///////////// close imap /////////////////////
                            imap_close($connectImap);
                            echo 'Соединение закрыто '.PHP_EOL;
                        }
                    }

                    //exit;
                    CronLogs::log('Rule  №' . $id . ' is finished', 'Price Auto Loader');

                    if ($flag_success) {

                        $this->admin_logs[] = "Data were loaded in an amount $download_count, loading time " . date('d.m.Y H:i:s');
                        $model = $this->loadModel($id);
                        $model->download_time = time();
                        $model->download_count = $all_download_count;
                        $model->scenario = 'runCronTab';
                        $model->save();
                        foreach ($model->getErrors() as $row) {
                            $this->admin_logs[] = "Failed to update data downloading, transaction is canceled";
                            if ($transaction != NULL) {
                                $transaction->rollback();
                            }
                            CronLogs::log($row . ' Rule №' . $id, 'Price Auto Loader');
                        }
                    } else {
                        $this->admin_logs[] = "The data have not been loaded, no error is detected";
                    }


                    $transaction->commit();
                    //$this->sendAdmiMsg();

                }
            } catch (Exception $e) {

                $transaction->rollback();
                var_dump($e->getMessage());
                CronLogs::log($e->getMessage() . ' Mailbox №' . $id, 'Price Auto Loader');
                $this->admin_logs[] = $e->getMessage();
                //$this->sendDie();
            }

            //return parent::run($args);
            return 0;
        }

        return 0;

    }


    /**
     * Получить модель ящика из которого загружаем прайсы
     * @param $id
     * @return PricesFtpAutoloadMailboxes
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = PricesFtpAutoloadMailboxes::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Проверка корректности заголовка и отправителя письма
     * @param $model
     * @param $connectImap
     * @param $oneMail
     * @return bool
     * @todo Разобраться нужно ли это или нет
     */
    public function mailCheck($model, $connectImap, $oneMail)
    {
        // получаем заголовок
        $header = imap_header($connectImap, $oneMail);
        // достаем ящик отправителя письма
        $mailSender = $header->from[0]->mailbox . "@" . $header->from[0]->host;

        if (!empty($model->mail_from) && !strpos('qtemp1' . mb_strtoupper($mailSender), mb_strtoupper($model->mail_from))) {
            return false;
        }

        // получаем заголовок письма
        $subject = $header->subject;

        if (!empty($model->mail_subject) && !strpos('qtemp1' . mb_strtoupper($subject), mb_strtoupper($model->mail_subject))) {
            return false;
        }

        return true;
    }

    /**
     * Отправка логов процесса админу на почту
     */
    public function sendAdmiMsg($source)
    {
        if ($source->send_admin_mail == '1') {
            $message = new YiiMailMessage();
            $message->setBody(implode("<br>\n", $this->admin_logs), 'text/html');
            $message->setSubject('Price Auto Loader ' . Yii::app()->config->get('Site.SiteName'));
            //$message->addTo(Yii::app()->config->get('Site.AdminEmail'));
            $message->addTo('moskvinvitaliy@rambler.ru');
            $message->from = Yii::app()->config->get('Site.NoreplyEmail');
            Yii::app()->mail->send($message);
        }
    }

    /**
     * Отправить логи админу и прервать работу скрипта
     */
    public function sendDie($source)
    {
        $this->sendAdmiMsg($source);
        //die(1);
    }

    /**
     * Запись в cron log обработки ошибок процесса
     * @param $errno
     * @param $msg
     * @param $file
     * @param $line
     */
    public function myErrorHandler($errno, $msg, $file, $line)
    {
        $this->admin_logs[] = "error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>" . ' Rule №' . $this->id;
        $file = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $this->model->id;
        is_dir($file) ? @$this->removeDirectory($file) : @unlink($file);
        CronLogs::log("error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>" . ' Rule №' . $this->id, 'Price Auto Loader');
    }

    /**
     * Заполнить массив файлов
     * @param $dir
     * @param array $files
     * @return array
     */
    public function GetContents($dir, $files = array())
    {
        if (!($res = opendir($dir))) {
            exit("Can not find this directory...");
        }

        /**
         * Перебираем файлы из директории и складываем в массив
         */
        while (($file = readdir($res)) == TRUE) {
            if ($file != "." && $file != "..") {
                if (is_dir("$dir/$file")) {
                    array_push($files, "$dir/$file");
                    //$files = $this->GetContents("$dir/$file", $files);
                } else
                    array_push($files, "$dir/$file");
            }
        }

        closedir($res);
        //print_r($files);
        return $files;
    }

    /**
     * Рекурсивное удаление директорий после получения писем
     * @param $dir
     */
    public function removeDirectory($dir)
    {
        if ($objs = glob($dir . "/*")) {
            //print_r($objs);
            foreach ($objs as $obj) {
                is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    /**
     * @param $model
     * @return string
     */
    protected function checkEmailDirectory($model)
    {
        if (!file_exists(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id)) {
            mkdir(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id);
        } else {
            $file = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id;
            is_dir($file) ? @$this->removeDirectory($file) : @unlink($file);
            mkdir(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id);
        }

        return realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id . '/';
    }

    /**
     * @param $model
     * @return string
     */
    protected function checkEmailZipDirectory($model)
    {
        if (!file_exists(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id.'/zip')) {
            mkdir(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id.'/zip');
        } else {
            $file = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id.'/zip';
            is_dir($file) ? @$this->removeDirectory($file) : @unlink($file);
            mkdir(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id.'/zip');
        }

        return realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id . '/zip';
    }

    /**
     * @param $model
     */
    protected function getPortAndAddress($model)
    {
        switch ($model->protocol) {
            case PricesFtpAutoloadMailboxes::POP_PROTOCOL:
                $this->mailAddress = $model->pop_adress;
                $this->mailPort = $model->pop_port;
                break;
            case PricesFtpAutoloadMailboxes::IMAP_PROTOCOL:
                $this->mailAddress = $model->imap_address;
                $this->mailPort = $model->imap_port;
                break;
            default:
                $this->mailAddress = $model->pop_adress;
                $this->mailPort = $model->pop_port;
        }
    }

    /**
     * @param $model
     * @param $criteria
     * @return array
     */
    protected function addConditionLoadType($model, $criteria)
    {
        //Если флаг, загружать не просмотренные
        if ($model->just_new == '1') {
            $criteria[] = 'NEW';//'UNSEEN';
            $this->admin_logs[] = 'Not reviewed';
        } else {
            //иначе загружать все присьма
            $criteria[] = 'ALL';
            $this->admin_logs[] = 'All messages';
        }

        //Так же загружать не удаленные
        $criteria[] = 'UNSEEN';//'UNDELETED';

        return $criteria;
    }

    /**
     * @param $attachments
     * @param $i
     * @param $structure
     * @param $connectImap
     * @param $oneMail
     * @return mixed
     */
    protected function fillArrayAttachments($attachments, $i, $structure, $connectImap, $oneMail)
    {
        $attachments[$i] = array(
            'is_attachment' => false,
            'filename' => '',
            'name' => '',
            'attachment' => ''
        );

        if ($structure->parts[$i]->ifdparameters) {
            foreach ($structure->parts[$i]->dparameters as $object) {
                if (strtolower($object->attribute) == 'filename') {
                    $attachments[$i]['is_attachment'] = true;

                    //$name = imap_utf8($object->value);
                    $name = iconv_mime_decode($object->value,0,"UTF-8");

                    $attachments[$i]['filename'] = $name;
                }
            }
        }

        if ($structure->parts[$i]->ifparameters) {
            foreach ($structure->parts[$i]->parameters as $object) {
                if (strtolower($object->attribute) == 'name') {
                    $attachments[$i]['is_attachment'] = true;

                    //$name = imap_utf8($object->value);
                    $name = iconv_mime_decode($object->value,0,"UTF-8");

                    $attachments[$i]['name'] = $name;
                }
            }
        }

        if ($attachments[$i]['is_attachment']) {
            $attachments[$i]['attachment'] = imap_fetchbody($connectImap, $oneMail, $i + 1);

            /* 4 = QUOTED-PRINTABLE encoding */
            switch ($structure->parts[$i]->encoding) {
                case ENCBASE64: //3
                    $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']); //imap_base64
                    break;
                case ENCQUOTEDPRINTABLE: //4
                    $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']); //imap_qprint
                    break;
            }
        }
        return $attachments;
    }

    /**
     * @param $source
     * @param $oneMail
     * @param $ext
     * @param $path_email
     * @param $download_count
     * @param $model
     * @param $attachment
     * @return mixed
     */
    protected function handleZip($source, $oneMail, $ext, $path_email, $download_count, $model, $attachment)
    {
        $path_zip = $this->checkEmailZipDirectory($model);

        //массив файлов запакованных в архив
        $zip_files = array();
        //имя файла архива
        $name = $source->id . '-' . $oneMail . "-price." . $ext;
        //echo 'new name '.$name.PHP_EOL;

        //сохранить архив на диск;
        $fp = file_put_contents($path_email . $name, $attachment['attachment']);

        //распаковаваем архив в папку
        Yii::app()->zip->extractZip($path_email . $name, $path_zip);
        //получаем массив распакованных файлов
        $temp_files = PhpDirectory::getDirectory($path_zip);
        //print_r($temp_files);
        //Загнать файлы в массив
        foreach ($temp_files as $keyf => $valuef) {
            $zip_files[] = $valuef;
        }
        //print_r($zip_files);
        //удалить архив
        @unlink($path_email . $name);
        //перебираем файлы
        foreach ($zip_files as $value2) {
            //имя файла
            $name = pathinfo($value2, PATHINFO_BASENAME);
            $filename = uniqid().'-'.$name;
            $this->admin_logs[] = "File $filename from zip";
            echo "File $filename from zip".PHP_EOL;
            //новое имя файла
            //копируем файл в родительскую директорию
            $cp = copy($value2, $path_email . $filename);
            //конвертируем файл исходя из формата файла, в формат csv
            $new = $cp;//$source->convert($path_email, $filename);
            //сохраняем сконвертированный файл
            if ($new != FALSE) {
                echo "Пишем $filename в очередь".PHP_EOL;
                //$price = fopen($path_email . $filename, 'w');
                //fwrite($price, $new);

                /**
                 * сделать запись в очередь
                 */
                $queue = new PricesAutoloadQueue();
                $queue->rule_id = $source->id;
                $queue->store_id = $source->store_id;
                $queue->path = $path_email;
                //$queue->filename = 'temp_' . $filename;
                $queue->filename = $filename;
                $queue->created = time();
                $queue->save();

                //fclose($price);
                //echo 'сделать запись в очередь'.PHP_EOL;
                //наращиваем значение количество загруженных файлов
                $download_count++;
            }
            //удаляем не нужные файлы
            //@unlink($path_email . $name);
            @unlink($value2);
        }
        //удаляем распакованные файлы
        @unlink($path_zip);
        return $download_count;
    }

    protected function defineExt($ext, $path_email, $attachment){
        if($ext != ''){
            return $ext;
        }else{
            $allowedExt = array(
                'text/csv',
                'text/xml',
                'application/zip',
                'application/gzip',
                'application/xml',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/x-rar-compressed',
            );


            file_put_contents($path_email . 'tmp', $attachment['attachment']);
            $ext = mime_content_type($path_email . 'tmp');
            @unlink($path_email . 'tmp');
            if(in_array($ext, $allowedExt)){
                $extArray = explode('/', $ext);
                return $extArray[1];
            }else{
                return false;
            }
        }
    }

}