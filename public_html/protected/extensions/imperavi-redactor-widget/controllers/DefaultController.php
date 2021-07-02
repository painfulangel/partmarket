<?php

class DefaultController extends Controller {

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('deleteFile', 'getImages', 'getFiles', 'uploadImage', 'uploadFile'),
                'roles' => array('texts', 'managerNotDiscount', 'manager', 'mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionDeleteFile($link) {
//        throw new Exception;
//        echo realpath(Yii::app()->basePath . '/..')  . $link;
        if (file_exists(realpath(Yii::app()->basePath . '/..') . '/' . $link)) {
            unlink(realpath(Yii::app()->basePath . '/..') . '/' . $link);
        }
    }

    public function actionGetImages() {
        $a = FileHelper::findFiles(realpath(Yii::app()->basePath . '/../images/imperavi_upload'), array('url' => '/images/imperavi_upload'), 0);
        echo stripslashes(CJSON::encode($a));
    }

    public function actionGetFiles() {
        $a = FileHelper::findFiles(realpath(Yii::app()->basePath . '/../images/imperavi_upload'), array('url' => '/images/imperavi_upload'), 1);
        echo stripslashes(CJSON::encode($a));
    }

    public function actionUploadImage() {
//        Yii::log('fdfd');
//        foreach ($_FILES as $k => $v) {
//            Yii::log($k . ':' . $v);
//        }
        $directory = realpath(Yii::app()->basePath . '/../images/imperavi_upload/') . '/';
        //echo $directory . 'text.txt';
        //return;
//        $t = fopen($directory . 'text.txt', 'w');
//        fwrite($t, $_FILES['file']['name'] . "\n");
//        fwrite($t, '3423423432' . "\n");
//        fclose($t);
        $file = md5(date('YmdHis')) . '.' . pathinfo(@$_FILES['file']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file(@$_FILES['file']['tmp_name'], $directory . $file)) {
            //fwrite($t, $directory . $file);
            $array = array(
                'filelink' => '/images/imperavi_upload/' . $file,
                    //'filename' => $file,
            );
        }
        //  fclose($t);
        echo stripslashes(CJSON::encode($array));
        exit;
    }

    public function actionUploadFile() {
        Yii::log('fdfd');
        foreach ($_FILES as $k => $v) {
            Yii::log($k . ':' . $v);
        }
        $directory = realpath(Yii::app()->basePath . '/../images/imperavi_upload/') . '/';
        //echo $directory . 'text.txt';
        //return;
//        $t = fopen($directory . 'text.txt', 'w');
//        fwrite($t, $_FILES['file']['name'] . "\n");
//        fwrite($t, '3423423432' . "\n");
//        fclose($t);
        $file = md5(date('YmdHis')) . '.' . pathinfo(@$_FILES['file']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file(@$_FILES['file']['tmp_name'], $directory . $file)) {
            //fwrite($t, $directory . $file);
            $array = array(
                'filelink' => '/images/imperavi_upload/' . $file,
                'filename' => $file,
            );
        }
        //  fclose($t);
        echo stripslashes(CJSON::encode($array));
        exit;
    }

}
