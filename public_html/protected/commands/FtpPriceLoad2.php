<?php
class FtpPriceLoad2 extends CConsoleCommand {
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
            $message->addTo(Yii::app()->config->get('Site.AdminEmail'));
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
                    echo $model->method_type;
                    if ($model->method_type == 'email') {
                        $this->admin_logs[] = 'Choose method Email';
                        $mail = PricesFtpAutoloadMailboxes::model()->findByPk($model->mail_id);
                        if ($model != NULL) {
                            if (!file_exists(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id)) {
                                mkdir(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id);
                            } else {
                                $file = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id;
                                is_dir($file) ? @$this->removeDirectory($file) : @unlink($file);
                                mkdir(realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id);
                            }

                            $path_email = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id . '/';

                            // соединяемся с почтовым сервером
                            $connectImap = imap_open('{' . $mail->pop_adress . ':' . $mail->pop_port . '/pop3/novalidate-cert}INBOX', $mail->mailbox, $mail->password) OR $this->admin_logs[] = 'Error connecting to the postal address: '.imap_last_error();

                            //!!! Условия отбора писем
                            $criteria = array();
                            if ($mail->just_new == '1') {
                                $criteria[] = 'UNSEEN';
                                $this->admin_logs[] = 'Not reviewed';
                            } else {
                                $criteria[] = 'ALL';
                                $this->admin_logs[] = 'All messages';
                            }

                            $criteria[] = 'UNDELETED';

                            if (!empty($model->mail_from)) {
                                $criteria[] = 'FROM "'.$model->mail_from.'"';
                            }

                            if (!empty($model->mail_subject)) {
                                $criteria[] = 'SUBJECT "'.$model->mail_subject.'"';
                            }

                            $this->admin_logs[] = 'Mail conditions '.implode(' ', $criteria);
                            //!!! Условия отбора писем

                            // проверим ящик на наличие новых писем
                            $mails = imap_search($connectImap, implode(' ', $criteria));
                            // если есть письма, удовлетворяющие условиям
                            if ($mails) {
                                // открываем каждое новое письмо
                                foreach($mails as $oneMail) {
                                    $this->admin_logs[] = 'Receiving Messages id='.$oneMail;

                                    if (!$this->mailCheck($model, $connectImap, $oneMail)) continue;

                                    /* get mail structure */
                                    $structure = imap_fetchstructure($connectImap, $oneMail);

                                    $attachments = array();
 
                                    /* if any attachments found... */
                                    if(isset($structure->parts) && count($structure->parts)) {
                                        for($i = 0; $i < count($structure->parts); $i++) {
                                            $attachments[$i] = array(
                                                'is_attachment' => false,
                                                'filename' => '',
                                                'name' => '',
                                                'attachment' => ''
                                            );
                             
                                            if($structure->parts[$i]->ifdparameters) {
                                                foreach($structure->parts[$i]->dparameters as $object) {
                                                    if(strtolower($object->attribute) == 'filename') {
                                                        $attachments[$i]['is_attachment'] = true;
                                                        $attachments[$i]['filename'] = $object->value;
                                                    }
                                                }
                                            }
                             
                                            if($structure->parts[$i]->ifparameters) {
                                                foreach($structure->parts[$i]->parameters as $object) {
                                                    if(strtolower($object->attribute) == 'name') {
                                                        $attachments[$i]['is_attachment'] = true;
                                                        $attachments[$i]['name'] = $object->value;
                                                    }
                                                }
                                            }
                             
                                            if($attachments[$i]['is_attachment']) {
                                                $attachments[$i]['attachment'] = imap_fetchbody($connectImap, $oneMail, $i+1);
                                                
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
                                        }
                                    }
                             
                                    if (count($attachments)) {
                                        $download_flag = false;

                                        /* iterate through each attachment and save it */
                                        foreach($attachments as $attachment) {
                                            if($attachment['is_attachment'] == 1) {
                                                $filename = $attachment['name'];
                                                if(empty($filename)) $filename = $attachment['filename'];
                                 
                                                if(!empty($filename)) {
                                                    $model->search_file_criteria = trim($model->search_file_criteria);

                                                    if ((!empty($model->search_file_criteria)) && !strpos('1qq1' . mb_strtoupper($filename), mb_strtoupper($model->search_file_criteria))) {
                                                        $this->admin_logs[] = 'Файл '.$filename.' не прошел критерий '.$model->search_file_criteria.' в обработке';
                                                        continue;
                                                    }

                                                    $this->admin_logs[] = 'Файл '.$filename.' в обработке';
                                                    $flag_success = true;
                                                    $download_flag = true;

                                                    $ext = str_replace('?=', '', pathinfo($filename, PATHINFO_EXTENSION));

                                                    if (strtolower($ext) == 'zip') {
                                                        $this->admin_logs[] = 'Processing a zip'.$filename;
                                                    } else {
                                                        //Название прайса
                                                        $name = pathinfo($filename, PATHINFO_BASENAME);
                                                        
                                                        if (preg_match('/[^a-z\.]+/i', $name))
                                                            $name = $model->primaryKey."-price.".$ext;

                                                        //$this->admin_logs[] = $name.' - '.pathinfo($filename, PATHINFO_BASENAME).' - '.intval(preg_match('/[^a-z\.]+/i', pathinfo($filename, PATHINFO_BASENAME)));

                                                        $model->filename = $name;

                                                        $fp = fopen($path_email.$name, "w+");
                                                        fwrite($fp, $attachment['attachment']);
                                                        fclose($fp);

                                                        $new = $model->convert($path_email, $name);
                                                        if ($new != FALSE) {
                                                            $price = fopen($path.'temp_' . $name, 'w');
                                                            fwrite($price, $new);
                                                            fclose($price);
                                                            $download_count = $model->savePrice($path.'temp_'.$name);
                                                            @unlink($path.'temp_'.$name);
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        if ($download_flag) {
                                            $this->admin_logs[] = 'Set flag as viewed';
                                            imap_setflag_full($connectImap, $oneMail, '\\Seen');
                                        }
                                    } else {
                                        $this->admin_logs[] = 'No files in id='.$oneMail;
                                    }

                                    if (intval($mail->delete_old) == 1) {
                                        //Пометить сообщение для удаления
                                        $res = imap_delete($connectImap, $oneMail);

                                        $this->admin_logs[] = 'Message '.$oneMail.' is deleted '.intval($res);
                                    }
                                }

                                if (intval($mail->delete_old) == 1) {
                                    //Удалить все помеченные для удаления сообщения
                                    $res = imap_expunge($connectImap);

                                    $this->admin_logs[] = 'Delete result '.intval($res);
                                }
                            }
                        } else {
                            $this->admin_logs[] = 'Not found postal addresses, it is removed';
                        }
                        $file = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $model->id;
                        is_dir($file) ? @$this->removeDirectory($file) : @unlink($file);
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
            if ($transaction != NULL) {
                $transaction->rollback();
            }
            CronLogs::log($e->getMessage() . ' Rule №' . $id, 'Price Auto Loader');
            $this->admin_logs[] = $e->getMessage();
            $this->sendDie();
        }
    }
}