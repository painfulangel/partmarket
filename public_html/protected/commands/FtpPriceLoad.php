<?php
class FtpPriceLoad extends CConsoleCommand
{
    public $admin_logs = array();
    public $id = 0;
    public $model = null;

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = PricesFtpAutoloadRules::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function mailCheck($model, $msg)
    {
        if (!empty($model->mail_from) && (!(strpos('qtemp1' . mb_strtoupper($msg->fromName), mb_strtoupper($model->mail_from))) || !(strpos('qtemp1' . mb_strtoupper($msg->fromAddress), mb_strtoupper($model->mail_from))))) {
            return false;
        }
        if (!empty($model->mail_subject) && (!(strpos('qtemp1' . mb_strtoupper($msg->subject), mb_strtoupper($model->mail_subject))))) {
            return false;
        }
        if (!empty($model->mail_body)) {
            $temp = explode(';', $model->mail_body);
            $flag = false;
            foreach ($temp as $value) {
                if ((strpos('qtemp1' . mb_strtoupper($msg->textPlain), mb_strtoupper($value)))) {
                    $flag = true;
                }
            }
            if (!$flag)
                return false;
        }
        return true;
    }

    public function sendAdmiMsg()
    {
        print_r($this->admin_logs);
        if ($this->model->send_admin_mail == '1') {
            $message = new YiiMailMessage();
            $message->setBody(implode("<br>\n", $this->admin_logs), 'text/html');
            $message->setSubject('Price Auto Loader ' . Yii::app()->config->get('Site.SiteName'));
            $message->addTo(Yii::app()->config->get('Site.AdminEmail'));
            $message->from = Yii::app()->config->get('Site.NoreplyEmail');
            Yii::app()->mail->send($message);
        }
    }

    public function sendDie()
    {
        $this->sendAdmiMsg();
        die;
    }

    public function myErrorHandler($errno, $msg, $file, $line)
    {
        $this->admin_logs[] = "error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>" . ' Rule ???' . $this->id;
        CronLogs::log("error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>" . ' Rule ???' . $this->id, 'Price Auto Loader');
    }

    public function GetContents($dir, $files = array())
    {
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

    public function removeDirectory($dir)
    {
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
        //???????? ?? ???????????????????? ???????????????????? ??????????????
        $path = realpath(Yii::app()->basePath . '/../upload_files/ftp') . '/';
        //???????? ?? ???????????????????? ??????????????(???? ?????????????????????????)
        $path_history = realpath(Yii::app()->basePath . '/../upload_files/ftp/history') . '/';

        set_time_limit(600);
        $this->id = $id = $args[0];

        function myErrorHandler($errno, $msg, $file, $line)
        {
            global $id;
            $this->admin_logs[] = "error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>" . ' Rule ???' . $this->id;
            $file = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $this->model->id;
            is_dir($file) ? @$this->removeDirectory($file) : @unlink($file);
            CronLogs::log("error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>" . ' Rule ???' . $id, 'Price Auto Loader');
        }

        set_error_handler('myErrorHandler', E_ERROR);
        $download_count = 0;
        //???????????????? ???????????? ?????????????? ?????? ???????????????? ???? ftp
        $model = $this->loadModel($id);
        $this->model = $model;
        //?????????? ???? ???????????????? ???????????????????? ?? ???????????????????? ??_??
        $model->ftp_password = $model->_ftp_password;

        try {
            //???????? ???????????? ???? ??????????????, ?????????? ???????????????? ?? catch
            if ($model != NULL) {
                if ($model->active_state == 1) {
                    $flag_success = false;
                    //?????????? ????????
                    $this->admin_logs[] = 'Starting the rule ???' . $id;
                    CronLogs::log('Starting the rule ???' . $id, 'Price Auto Loader');
                    //???????????????? ????????????????????
                    $transaction = Yii::app()->db->beginTransaction();
                    //?????????????? ?? ?????????????? ?????? ?????????? ????????????????
                    echo $model->method_type;
                    //???????? ???????????????? ?? email
                    if ($model->method_type == 'email') {
                        //???????????????? ?????????????????? ?????????????????? ?????????? ???? id
                        $mail = PricesFtpAutoloadMailboxes::model()->findByPk($model->mail_id);
                        //?????????? ???????????? ?????????? ?????????????????????? ???? $mail != null, ???? ?????????????????????? ?????????????? $model
                        //?? ??????????????
                        if ($mail != NULL) {
                            //?????????????????????? ????????????????????
                            Yii::import('ext.EIMap.EIMap', true);
                            //???????????????? ???????????????????????????? ???????????? ?????? ?????????????????? ??????????
                            $imap = new EIMap('{' . $mail->pop_adress . ':' . $mail->pop_port . '/pop3/novalidate-cert}INBOX', $mail->mailbox, $mail->password);
                            //???????? ?????????? ?????? ???????????????? ?????????? ???? ????????????????????, ?????????? ???? ??????????????
                            if (!file_exists(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id)) {
                                mkdir(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id);
                            } else {
                                $file = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id;
                                is_dir($file) ? @$this->removeDirectory($file) : @unlink($file);
                                mkdir(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id);
                            }
                            //?????????????????????????? ?? ???????????????? ?????????????? imap, ???????? ?? ???????????????????? ???????????????? ??????????
                            echo $imap->setAttachmentsDirectory(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id);
                            echo $imap->getAttachmentsDirectory();

                            //?????????? ?????????? ???? ?????????????????? ???????????????? ?? ??????????????????????
                            $path_email = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id . '/';
                            $this->admin_logs[] = 'Choose method Email';

                            //???????? ??????????????  ???????????????????????? ?? ??????????
                            if ($imap->connect()) {
                                $this->admin_logs[] = 'Connection is success';
                                //???????????????????????????? ?????????? ???????????? ?????? ?????????????????????????? ??????????
                                $unseen = array();

                                $criteria = array();
                                //???????? ?????????? ???????? ???????????????? ???????????? ??????????
                                if ($mail->just_new == '1') {
                                    //?????????????????????????? ?????????????? "?????????? ???? ??????????????????????"
                                    $criteria[] = EIMap::SEARCH_UNSEEN;
                                    $this->admin_logs[] = 'Not reviewed';
                                    //$unseen = $imap->searchmails(EIMap::SEARCH_UNSEEN);
                                } else {
                                    //?????????? ?????????????????????????? ???????????????? "???????????????? ?????? ????????????"
                                    $criteria[] = EIMap::SEARCH_ALL;
                                    $this->admin_logs[] = 'All messages';
                                    //$unseen = $imap->searchmails(EIMap::SEARCH_ALL);
                                }
                                //???????????? ???? ??????????????????
                                $criteria[] = EIMap::SEARCH_UNDELETED;

                                //???????? ?? ???????????????? ???????????????? ???????????? ??????????????????????, ?????????????????? ????????????????
                                if (!empty($model->mail_from)) {
                                    $criteria[] = EIMap::SEARCH_FROM . ' "' . $model->mail_from . '"';
                                }
                                //echo $model->mail_subject;
                                //???????? ?????????????? ?? ????????????????, ???????????????? ?????????? ?? ???????? ?????? ???????? ????????????
                                //?????????????????? ????????????????
                                if (!empty($model->mail_subject)) {
                                    $criteria[] = EIMap::SEARCH_SUBJECT . ' "' . $model->mail_subject . '"';
                                }
                                //???????????????? ???????????? ?? ????????????
                                $unseen = $imap->searchmails(implode(' ', $criteria));

                                if ($unseen && is_array($unseen)) {
                                    foreach ($unseen as $msgId) {
                                        //???????????? ????????????
                                        $msg = $imap->getMail($msgId);
                                        $this->admin_logs[] = 'Receiving Messages id=' . $msgId;
                                        //?????? ???? ?????????????????? ?? ????????????
                                        if ($this->mailCheck($model, $msg)) {
                                            $this->admin_logs[] = 'Filter is success for message  id=' . $msgId;
                                            //???????????????? ???? ???????????? attachment
                                            $atch = $imap->getAttachments($msgId);
                                            //???????? ????????????????(??) ????????
                                            if (count($atch) > 0) {
                                                $download_flag = false;
                                                //?????????????????? ????????????????
                                                foreach ($atch as $atach_file) {
                                                    //print_r($this->admin_logs);
                                                    $model->search_file_criteria = trim($model->search_file_criteria);
                                                    //echo empty($model->search_file_criteria).$atach_file['filename'];
                                                    echo $model->search_file_criteria . "\n";
                                                    echo mb_strtoupper($model->search_file_criteria) . "\n";
                                                    if ((!empty($model->search_file_criteria)) && !strpos('1qq1' . mb_strtoupper($atach_file['filename']), mb_strtoupper($model->search_file_criteria))) {
                                                        $this->admin_logs[] = '???????? ' . $atach_file['filename'].' ???? ???????????? ???????????????? '.$model->search_file_criteria . ' ?? ??????????????????';
                                                        continue;
                                                    }
                                                    //el

                                                    $this->admin_logs[] = '???????? ' . $atach_file['filename'] . ' ?? ??????????????????';
                                                    $flag_success = true;
                                                    $download_flag = true;

                                                    if (strtolower(pathinfo($atach_file['filename'], PATHINFO_EXTENSION)) == 'zip') {
                                                        $this->admin_logs[] = 'Processing a zip' . $atach_file['filename'];
                                                        $name = md5($atach_file['filename'] . time()) . '.' . pathinfo($atach_file['filename'], PATHINFO_EXTENSION);
                                                        $zip_name = md5('zip' . time() . $atach_file['filename']);
                                                        copy($path_email . $atach_file['filename'], $path . $name);
                                                        Yii::app()->zip->extractZip($path . $name, $path . $zip_name);
                                                        $temp_files = PhpDirectory::getDirectory($path . $zip_name);
                                                        //  $this->admin_logs[] = print_r($temp_files, true);
                                                        $zip_files = array();
                                                        foreach ($temp_files as $keyf => $valuef) {
                                                            $zip_files[] = $valuef;
                                                            @unlink($path . $name);
                                                        }
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
                                                    } else {
                                                        $name = pathinfo($atach_file['filepath'], PATHINFO_BASENAME);
                                                        $model->filename = $name;
                                                        //$name = md5($value2 . time()) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                                        //copy($value2, $path_email . $name);
                                                        $new = $model->convert($path_email, $name);
                                                        if ($new != FALSE) {
                                                            $price = fopen($path . 'temp_' . $name, 'w');
                                                            fwrite($price, $new);
                                                            fclose($price);
                                                            $download_count += $model->savePrice($path . 'temp_' . $name);
                                                            @unlink($path . 'temp_' . $name);
                                                        }
                                                        //@unlink($atach_file['filepath']);
                                                        //@unlink($value2);
                                                        //print_r($this->admin_logs);

                                                    }
                                                }

                                                //???????????????? ???????????? ?????? ??????????????????????
                                                if ($download_flag) {
                                                    $this->admin_logs[] = 'Set flag as viewed';
                                                    $imap->markMailAsRead($msgId);
                                                }
                                                //echo '<pre>' . ( CVarDumper::dumpAsString($atch) ) . '</pre>';
                                                //echo '<pre>' . ( CVarDumper::dumpAsString($mail) ) . '</pre>';
                                            } else {
                                                $this->admin_logs[] = 'No files in id=' . $msgId;
                                            }

                                            //???????? ?????????? ???????? ?????????????? ????????????, ?????????????? ????????????
                                            if ($mail->delete_old == '1') {
                                                $this->admin_logs[] = 'Message is deleted';
                                                //echo 'd';
                                                @$imap->deleteMail($msgId);
                                            }
                                        }
                                    }
                                }
                                $imap->close(); // close connection	
                            } else {
                                $this->admin_logs[] = 'Error connecting to the postal address';
                            }
                        } else {
                            $this->admin_logs[] = 'Not found postal addresses, it is removed';
                        }

                        //?????????????? ????????????
                        $file = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id;
                        is_dir($file) ? @$this->removeDirectory($file) : @unlink($file);
                        //@unlink(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id);
                    } else {
                        //???????? ?????? ???????????????? ?? ftp, ?????????????????? ?????????????????? ftp
                        if (!empty($model->ftp_server) && !empty($model->ftp_login)) {
                            $this->admin_logs[] = 'Choose method FTP';
                            //$path = realpath(Yii::app()->basePath . '/../upload_files/ftp') . '/';
                            $port = 21;
                            if (strpos($model->ftp_server, ':')) {
                                $temp_port = explode(':', $model->ftp_server);
                                $model->ftp_server = $temp_port[0];
                                $port = $temp_port[1];
                            }
                            //print_r('port=' . $port);
                            //print_r('server=' . $model->ftp_server . ' ');
                            //print_r('password=' . $model->ftp_password . ' ');
                            Yii::app()->params['ftp'] = array(
                                'host' => trim($model->ftp_server),
                                'port' => trim($port),
                                'username' => trim($model->ftp_login),
                                'password' => trim($model->ftp_password),
                                'ssl' => false,
                                'timeout' => 200,
                                'autoConnect' => true,
                            );
                            //???????????????????????????? ?????????????????? ???????????????? ???? ftp
                            $ftp = Yii::createComponent('ext.ftp.EFtpComponent');
                            //???????? ???????? ???????????????? ?? ????????????????????, ???????????????????? ????????????????????
                            if (!$ftp->chdir(iconv('UTF-8', 'cp1251', $model->ftp_destination_folder))) {
                                if ($transaction != NULL) {
                                    $transaction->rollback();
                                }
                                CronLogs::log('Login failed into startup directory ???' . $id, 'Price Auto Loader');
                                $this->admin_logs[] = 'Login failed into startup directory';
                                $this->sendDie();
                            }
                            //???????????????? ?????????? ???? ftp
                            $files = $ftp->listFiles($ftp->currentDir());

                            //???????? ?????????? ???????? ?????????????????? ??????????
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
                                            fwrite($price, $new);
                                            fclose($price);
                                            $download_count += $model->savePrice($path . 'temp_' . $name);
                                            @unlink($path . 'temp_' . $name);
                                        }
                                        @unlink($path . $name);
                                    }
                                }
                        } else {
                            //???????????????? ???? ?????????????????? ????????????????????
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
                            //?????? ?????? ???????????????????? ??????????, ???????????? ?????? ???????????? ??????????????????????????
                            foreach ($files as $value) {
                                $name = pathinfo($value, PATHINFO_BASENAME);
                                if ($name == '.' || $name == '..' || (!empty($model->search_file_criteria) && strpos(mb_strtoupper($name), mb_strtoupper($model->search_file_criteria)) === false)) {
                                    continue;
                                }
                                $model->filename = $name;
                                $this->admin_logs[] = "File $model->filename ";
                                $name = md5($value . time()) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                                copy($value, $path . $name);
                                $new = $model->convert($path, $name);
                                if ($new != FALSE) {
                                    $price = fopen($path . 'temp_' . $name, 'w');
                                    fwrite($price, $new);
                                    fclose($price);
                                    $download_count += $model->savePrice($path . 'temp_' . $name);
                                    unlink($path . 'temp_' . $name);
                                }
                                unlink($path . $name);
                            }
                        }
                    }


                    CronLogs::log('Rule  ???' . $id . ' is finished', 'Price Auto Loader');

                    if ($flag_success) {
                        $this->admin_logs[] = "Data were loaded in an amount $download_count, loading time " . date('d.m.Y H:i:s');
                        $model = $this->loadModel($id);
                        //???????????? ?????????? ????????????????
                        $model->download_time = time();
                        //???????????? ??????-???? ?????????????????????? ????????????
                        $model->download_count = $download_count;
                        $model->scenario = 'runCronTab';
                        //?????????????????? ????????????
                        $model->save();
                        //???????????????????? ???????????? ?? ??????
                        foreach ($model->getErrors() as $row) {
                            $this->admin_logs[] = "Failed to update data downloading, transaction is canceled";
                            if ($transaction != NULL) {
                                $transaction->rollback();
                            }
                            CronLogs::log($row . ' Rule ???' . $id, 'Price Auto Loader');
                        }

                    } else {
                        $this->admin_logs[] = "The data have not been loaded, no error is detected";
                    }
                }
                //?????????????????? ???????????????????? ?? ???????????????????? ???????????? ??????????????????
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
            CronLogs::log($e->getMessage() . ' Rule ???' . $id, 'Price Auto Loader');
            $this->admin_logs[] = $e->getMessage();
            $this->sendDie();
        }
    }
}