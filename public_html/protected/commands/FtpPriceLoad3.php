<?php
class FtpPriceLoad3 extends CConsoleCommand {
    public $admin_logs = array();
    public $id = 0;
    public $model = null;

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = PricesFtpAutoloadRules::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function mailCheck($model, $connectImap, $oneMail) {
        // получаем заголовок
        $header = imap_header($connectImap, $oneMail);
        // достаем ящик отправителя письма
        $mailSender = $header->from[0]->mailbox . "@" . $header->from[0]->host;

        if (!empty($model->mail_from) && !strpos('qtemp1'.mb_strtoupper($mailSender), mb_strtoupper($model->mail_from))) {
            return false;
        }

        // получаем заголовок письма
        $subject = $header->subject;

        if (!empty($model->mail_subject) && !strpos('qtemp1' . mb_strtoupper($subject), mb_strtoupper($model->mail_subject))) {
            return false;
        }

        return true;
    }

    public function sendAdmiMsg() {
        print_r($this->admin_logs);
        if ($this->model->send_admin_mail == '1') {
            $message = new YiiMailMessage();
            $message->setBody(implode("<br>\n", $this->admin_logs), 'text/html');
            $message->setSubject('Price Auto Loader ' . Yii::app()->config->get('Site.SiteName'));
            //$message->addTo(Yii::app()->config->get('Site.AdminEmail'));
            $message->addTo('moskvinvitaliy@rambler.ru');
            $message->from = Yii::app()->config->get('Site.NoreplyEmail');
            Yii::app()->mail->send($message);
        }
    }

    public function sendDie() {
        $this->sendAdmiMsg();
        die;
    }

    public function myErrorHandler($errno, $msg, $file, $line) {
        $this->admin_logs[] = "error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>" . ' Rule №' . $this->id;
        CronLogs::log("error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>" . ' Rule №' . $this->id, 'Price Auto Loader');
    }

    public function GetContents($dir, $files = array()) {
        if (!($res = opendir($dir)))
            exit("Can not find this directory...");
        while (($file = readdir($res)) == TRUE)
            if ($file != "." && $file != "..") {
                if (is_dir("$dir/$file")) {
                    array_push($files, "$dir/$file");
//                    $files = $this->GetContents("$dir/$file", $files);
                } else
                    array_push($files, "$dir/$file");
            }
        closedir($res);
//        print_r($files);
        return $files;
    }

    public function removeDirectory($dir) {
        if ($objs = glob($dir . "/*")) {
            print_r($objs);
            foreach ($objs as $obj) {
                is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    public function run($args)
    {
        $transaction = NULL;
        $path = realpath(Yii::app()->basePath . '/../upload_files/ftp') . '/';
        $path_history = realpath(Yii::app()->basePath . '/../upload_files/ftp/history') . '/';
        set_time_limit(600);
        $this->id = $id = $args[0];

        function myErrorHandler($errno, $msg, $file, $line)
        {
            global $id;
            $this->admin_logs[] = "error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>" . ' Rule №' . $this->id;
            $file = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $this->model->id;
            is_dir($file) ? @$this->removeDirectory($file) : @unlink($file);
            CronLogs::log("error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>" . ' Rule №' . $id, 'Price Auto Loader');
        }

        set_error_handler('myErrorHandler', E_ERROR);
        $download_count = 0;
        $model = $this->loadModel($id);
        $this->model = $model;
        $model->ftp_password = $model->_ftp_password;
        try {
            if ($model != NULL) {
                if ($model->active_state == 1) {
                    $flag_success = false;
                    $this->admin_logs[] = 'Starting the rule №' . $id;
                    CronLogs::log('Starting the rule №' . $id, 'Price Auto Loader');
                    $transaction = Yii::app()->db->beginTransaction();

                    if ($model->method_type == 'url') {
                        $this->admin_logs[] = 'Choose method Url';
                        //$mail = PricesFtpAutoloadMailboxes::model()->findByPk($model->mail_id);
                        if ($model != NULL) {
                            if (!file_exists(realpath(Yii::app()->basePath . '/../upload_files/url') . '/' . $model->id)) {
                                mkdir(realpath(Yii::app()->basePath . '/../upload_files/url') . '/' . $model->id);
                                mkdir(realpath(Yii::app()->basePath . '/../upload_files/url') . '/' . $model->id.'/zip');
                            } else {
                                $file = realpath(Yii::app()->basePath . '/../upload_files/url') . '/' . $model->id;
                                is_dir($file) ? @$this->removeDirectory($file) : @unlink($file);
                                mkdir(realpath(Yii::app()->basePath . '/../upload_files/url') . '/' . $model->id);
                                mkdir(realpath(Yii::app()->basePath . '/../upload_files/url') . '/' . $model->id.'/zip');
                            }

                            $path_url = realpath(Yii::app()->basePath . '/../upload_files/url') . '/' . $model->id . '/';

                            // соединяемся с сервером по url
                            $remote = file_get_contents($model->remote_url);

                            // если есть контент
                            if ($remote) {

                                $this->admin_logs[] = 'Receiving file ';
                                $this->admin_logs[] = 'Файл в обработке';
                                $flag_success = true;
                                $download_flag = true;

                                $urlArray = parse_url($model->remote_url);
                                $fileInfo = pathinfo($urlArray['path']);
                                $filename = $fileInfo['basename'];

                                file_put_contents($path_url . $filename, $remote);

                                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                                if (strtolower($ext) == 'zip') {
                                    $zip_files = array();
                                    $this->admin_logs[] = 'Processing a zip'.$filename;
                                    Yii::app()->zip->extractZip($path_url . $filename, $path_url .'zip/'.$filename);
                                    $temp_files = PhpDirectory::getDirectory($path_url . 'zip');
                                    foreach ($temp_files as $keyf => $valuef) {
                                        $zip_files[] = $valuef;
                                    }
                                    //Удаляем архив
                                    @unlink($path_url . $filename);
                                    foreach ($zip_files as $value2) {
                                        $name = pathinfo($value2, PATHINFO_BASENAME);
                                        $model->filename = $name;
                                        $this->admin_logs[] = "File $model->filename from zip";
                                        $name = md5($value2 . time()) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                        copy($value2, $path_url . $name);
                                        $new = $model->convert($path_url, $name);
                                        CronLogs::log('Text in file: ' . $new);
                                        if ($new != FALSE) {
                                            $price = fopen($path_url . 'temp_' . $name, 'w');
                                            CronLogs::log('Save file To: ' . $path_url . 'temp_' . $name);
                                            fwrite($price, $new);
                                            fclose($price);
                                            $download_count += $model->savePrice($path_url . 'temp_' . $name);
                                            //@unlink($path_url . 'temp_' . $name);
                                        }
                                        @unlink($path_url . $name);
                                        @unlink($value2);
                                    }
                                    @unlink($path_url . 'zip/'.$filename);
                                } elseif (strtolower($ext) == 'rar'){
                                    if(!class_exists('RarArchive')){
                                        echo 'Your PHP version does not support .rar archive functionality.'.PHP_EOL;
                                        exit;
                                    }

                                    // Check if archive is readable.
                                    if($rar = RarArchive::open($path_url . $filename)){

                                        // Check if destination is writable
                                        if (is_writeable($path_url .'zip')) {
                                            $entries = $rar->getEntries();
                                            foreach ($entries as $entry) {
                                                $entry->extract($path_url .'zip');
                                            }
                                            $rar->close();
                                            echo 'File extracted successfully.'.PHP_EOL;

                                            //обрабатываем файл
                                            $temp_files = PhpDirectory::getDirectory($path_url . 'zip');
                                            foreach ($temp_files as $keyf => $valuef) {
                                                $zip_files[] = $valuef;
                                            }
                                            //Удаляем архив
                                            @unlink($path_url .$filename);

                                            echo 'Удаляем архив'.PHP_EOL;

                                            //перебираем файлы
                                            foreach ($zip_files as $value2) {
                                                $name = pathinfo($value2, PATHINFO_BASENAME);
                                                $model->filename = $name;
                                                $this->admin_logs[] = "File $model->filename from zip";
                                                echo "File $model->filename from rar".PHP_EOL;
                                                $name = md5($value2 . time()) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                                copy($value2, $path_url . $name);
                                                $new = $model->convert($path_url, $name);
                                                if ($new != FALSE) {
                                                    $price = fopen($path_url . 'temp_' . $name, 'w');
                                                    CronLogs::log('Save file To: ' . $path_url . 'temp_' . $name);
                                                    fwrite($price, $new);
                                                    fclose($price);
                                                    $download_count += $model->savePrice($path_url . 'temp_' . $name);
                                                    //@unlink($path_url . 'temp_' . $name);
                                                }
                                                @unlink($path_url . $name);
                                                @unlink($value2);
                                            }
                                            @unlink($path_url . 'zip/'.$filename);

                                            //return true;
                                        }else{
                                            echo 'Directory not writeable by webserver.'.PHP_EOL;
                                            //return false;
                                        }
                                    }else{
                                        echo 'Cannot read .rar archive.'.PHP_EOL;
                                        //return false;
                                    }
                                }else {
                                    //Название прайса
                                    $name = $filename;

                                    if (preg_match('/[^a-z\.]+/i', $name)){
                                        $name = $model->primaryKey."-price.".$ext;
                                    }

                                    $model->filename = $name;
                                    $this->admin_logs[] = 'load model: ' . serialize($model);


                                    $fp = fopen($path_url.$name, "w+");
                                    fwrite($fp, $remote);
                                    fclose($fp);

                                    $new = $model->convert($path_url, $name);
                                    CronLogs::log("textFile: " . $new);
                                    if ($new != FALSE) {
                                        $price = fopen($path_url.'temp_' . $name, 'w');
                                        CronLogs::log("Save File to " . $path_url.'temp_' . $name);
                                        fwrite($price, $new);
                                        fclose($price);
                                        $download_count = $model->savePrice($path.'temp_'.$name);
                                        @unlink($path.'temp_'.$name);
                                    }
                                }

                            } else {
                                $this->admin_logs[] = 'No files in id';
                            }

                        } else {
                            $this->admin_logs[] = 'Not found postal addresses, it is removed';
                        }
                        //$file = realpath(Yii::app()->basePath . '/../upload_files/url') . '/' . $model->id;
                        //is_dir($file) ? @$this->removeDirectory($file) : @unlink($file);
//                        @unlink(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id);
                    } else {
                        if (!empty($model->ftp_server) && !empty($model->ftp_login)) {
                            $this->admin_logs[] = 'Choose method FTP';
//                    $path = realpath(Yii::app()->basePath . '/../upload_files/ftp') . '/';
                            $port = 21;
                            if (strpos($model->ftp_server, ':')) {
                                $temp_port = explode(':', $model->ftp_server);
                                $model->ftp_server = $temp_port[0];
                                $port = $temp_port[1];
                            }
//                            print_r('port=' . $port);
//                            print_r('server=' . $model->ftp_server . ' ');
//                            print_r('password=' . $model->ftp_password . ' ');
                            Yii::app()->params['ftp'] = array(
                                'host' => trim($model->ftp_server),
                                'port' => trim($port),
                                'username' => trim($model->ftp_login),
                                'password' => trim($model->ftp_password),
                                'ssl' => false,
                                'timeout' => 200,
                                'autoConnect' => true,
                            );
                            $ftp = Yii::createComponent('ext.ftp.EFtpComponent');
                            if (!$ftp->chdir(iconv('UTF-8', 'cp1251', $model->ftp_destination_folder))) {
                                if ($transaction != NULL) {
                                    $transaction->rollback();
                                }
                                CronLogs::log('Login failed into startup directory №' . $id, 'Price Auto Loader');
                                $this->admin_logs[] = 'Login failed into startup directory';
                                $this->sendDie();
                            }
                            $files = $ftp->listFiles($ftp->currentDir());
                            if (is_array($files))
                                foreach ($files as $value) {
                                    $name = str_replace($ftp->currentDir() . '/', '', $value);
                                    if ($name == '.' || $name == '..' || (!empty($model->search_file_criteria) && strpos(mb_strtoupper($name), mb_strtoupper($model->search_file_criteria)) === false)) {
                                        continue;
                                    }
                                    $flag_success = true;
                                    if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) == 'zip') {
                                        $this->admin_logs[] = "Processing a zip";
                                        $zip_files = array();
                                        $name = md5($value . time()) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                        $zip_name = md5('zip' . time() . $value) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                        $ftp->get($path . $name, $value, FTP_BINARY);
                                        Yii::app()->zip->extractZip($path . $name, $path . $zip_name);
                                        $temp_files = PhpDirectory::getDirectory($path . $zip_name);
                                        foreach ($temp_files as $keyf => $valuef) {
                                            $zip_files[] = $valuef;
                                        }
                                        @unlink($path . $name);
                                        foreach ($zip_files as $value2) {
                                            $name = pathinfo($value2, PATHINFO_BASENAME);
                                            $model->filename = $name;
                                            $this->admin_logs[] = "File $model->filename from zip";
                                            $name = md5($value2 . time()) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                            copy($value2, $path . $name);
                                            $new = $model->convert($path, $name);
                                            if ($new != FALSE) {
                                                $price = fopen($path . 'temp_' . $name, 'w');
                                                CronLogs::log('Save file To: ' . $path . 'temp_' . $name);
                                                fwrite($price, $new);
                                                fclose($price);
                                                $download_count += $model->savePrice($path . 'temp_' . $name);
                                                @unlink($path . 'temp_' . $name);
                                            }
                                            @unlink($path . $name);
                                            @unlink($value2);
                                        }
                                        @unlink($path . $zip_name);
                                    } else {
                                        $model->filename = $name;
                                        $name = md5($value . time()) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                        $ftp->get($path . $name, $value, FTP_BINARY);
                                        $new = $model->convert($path, $name);
                                        if ($new != FALSE) {
                                            $price = fopen($path . 'temp_' . $name, 'w');
                                            CronLogs::log('Save file To: ' . $path . 'temp_' . $name);
                                            fwrite($price, $new);
                                            fclose($price);
                                            $download_count += $model->savePrice($path . 'temp_' . $name);
                                            @unlink($path . 'temp_' . $name);
                                        }
                                        @unlink($path . $name);
                                    }
                                }
                        } else {
                            $this->admin_logs[] = 'Choose method With a local directory';
                            $files = PhpDirectory::getDirectory(realpath(Yii::app()->basePath . '/../' . $model->ftp_destination_folder));
                            foreach ($files as $key => $value) {
                                $name = pathinfo($value, PATHINFO_BASENAME);
                                if ($name == '.' || $name == '..' || (!empty($model->search_file_criteria) && strpos(mb_strtoupper($name), mb_strtoupper($model->search_file_criteria)) === false)) {
                                    continue;
                                }
                                $flag_success = true;
                                if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) == 'zip') {
                                    $this->admin_logs[] = 'Processing a zip';
                                    $name = md5($value . time()) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                    $zip_name = md5('zip' . time() . $value) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                    copy($value, $path . $name);
                                    CronLogs::log("Папка назначения: " . $path . $name);
                                    Yii::app()->zip->extractZip($path . $name, $path . $zip_name);
                                    $temp_files = PhpDirectory::getDirectory($path . $zip_name);
                                    foreach ($temp_files as $keyf => $valuef) {
                                        $zip_files[] = $valuef;
                                    }
                                    @unlink($path . $name);
                                    unset($files[$key]);
                                    foreach ($zip_files as $value2) {
                                        $name = pathinfo($value2, PATHINFO_BASENAME);
                                        $model->filename = $name;
                                        $this->admin_logs[] = "File $model->filename from zip";
                                        $name = md5($value2 . time()) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                        copy($value2, $path . $name);
                                        $new = $model->convert($path, $name);
                                        if ($new != FALSE) {
                                            $price = fopen($path . 'temp_' . $name, 'w');
                                            fwrite($price, $new);
                                            fclose($price);
                                            $download_count += $model->savePrice($path . 'temp_' . $name);
                                            @unlink($path . 'temp_' . $name);
                                        }
                                        @unlink($path . $name);
                                        @unlink($value2);
                                    }
                                    @unlink($path . $zip_name);
                                }
                            }
                            foreach ($files as $value) {
                                $name = pathinfo($value, PATHINFO_BASENAME);
                                if ($name == '.' || $name == '..' || (!empty($model->search_file_criteria) && strpos(mb_strtoupper($name), mb_strtoupper($model->search_file_criteria)) === false)) {
                                    continue;
                                }
                                $model->filename = $name;
                                $this->admin_logs[] = "File $model->filename ";
                                CronLogs::log("File $model->filename ");
                                $name = md5($value . time()) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                copy($value, $path . $name);
                                CronLogs::log("Copy File to " . $path . $name);
                                $new = $model->convert($path, $name);

                                if ($new != FALSE) {
                                    $price = fopen($path . 'temp_' . $name, 'w');
                                    CronLogs::log("Save File to " . $path . 'temp_' . $name);
                                    fwrite($price, $new);
                                    fclose($price);
                                    $download_count += $model->savePrice($path . 'temp_' . $name);
                                    unlink($path . 'temp_' . $name);
                                }
                                unlink($path . $name);
                            }
                        }
                    }
                    CronLogs::log('Rule  №' . $id . ' is finished', 'Price Auto Loader');
                    if ($flag_success) {
                        $this->admin_logs[] = "Data were loaded in an amount $download_count, loading time " . date('d.m.Y H:i:s');
                        $model = $this->loadModel($id);
                        $model->download_time = time();
                        $model->download_count = $download_count;
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
                }
                if ($transaction != NULL) {
                    $transaction->commit();
                    $this->sendAdmiMsg();
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($transaction != NULL) {
                $transaction->rollback();
            }
            //CronLogs::log($e->getMessage() . ' Rule №' . $id, 'Price Auto Loader');
            $this->admin_logs[] = $e->getMessage();
            $this->sendDie();
        }
    }
}