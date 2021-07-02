<?php

class IcApiController extends Controller {

    public function checkAuth($auth_key) {

        function myErrorHandler($errno, $msg, $file, $line) {
            Yii::log("error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>");
            echo "error:<b>$errno</b>!\n";
            echo "File: <tt>$file</tt>, line $line.\n";
            echo "Text: <i>$msg</i>\n";
        }

        set_error_handler("myErrorHandler", E_ALL);
        return trim($auth_key) == trim(Yii::app()->config->get('1C.ApiKey'));
    }

    public function actionLoadFromSite($type, $auth_key = '', $date_from = '00-00-0000') {

        if (!$this->checkAuth($auth_key)) {
            Yii::log('Error login to 1C');
            return false;
        }

        if ($type == 'orders') {
//            echo "success\n";
            header('Content-Type: application/xml; charset=utf-8');
            echo Orders::model()->exportOrders1c(Orders::model()->get1cFieldList(), strtotime($date_from));
        }
        if ($type == 'operations') {
            header('Content-Type: application/xml; charset=utf-8');
            echo UserProfile::model()->exportUsersOperations1c(UserProfile::model()->get1cOperationsFieldList());
        }
        if ($type == 'clients') {
            header('Content-Type: application/xml ');
            echo UserProfile::model()->exportUsers1c(array(), UserProfile::model()->get1cFieldList());
        }
    }

    function actionLoadToSite($type, $mode, $auth_key = '', $date_from = '00-00-0000') {
//        print_r($_GET);
//        print_r($_POST);
//        die;
//        echo $auth_key;
//        Yii::log($auth_key);
        if (!$this->checkAuth($auth_key)) {
            Yii::log('Error login to 1C');
            return false;
        }
        if ($mode == "auth")
            echo "success\nauth_cookie\n";
        else if ($mode == "query") {
            
        } else if ($mode == "checkauth") {
            echo "success\ncook\ncookval\n";
        } else if ($mode == "init") {
            echo "zip=no\nfile_limit=100000000\n";
        } else if ($mode == "file") {

            $file = file_get_contents('php://input');
            if (!empty($file)) {
                $path = realpath(Yii::app()->basePath . '/../upload_files/1c_data') . '/';
                $temp_xml = fopen($path . $type . '.xml', 'w');
                fwrite($temp_xml, $file);
                fclose($temp_xml);
                $path = realpath(Yii::app()->basePath . '/../upload_files/1c_data') . '/';
                if ($type == 'clients') {
                    UserProfile::model()->import1CUsers($path . $type . '.xml');
                    echo "success\n";
                }
                if ($type == 'katalog') {
                    Prices::model()->importPriceFrom1c($path . $type . '.xml');
                    echo "success\n";
                }

                if ($type == 'orders') {
                    Orders::model()->import1COrders($path . $type . '.xml');
                    echo "success\n";
                }
                if ($type == 'stop_list') {
                    UserProfile::model()->import1CStopList($path . $type . '.xml');
                    echo "success\n";
                }
                if ($type == 'operations') {
                    UserProfile::model()->import1CBalanceOperations($path . $type . '.xml');
                    echo "success\n";
                }
            } else {
                echo "success\n";
            }
        }
    }

//    function actionTest() {
//
//        function myErrorHandler($errno, $msg, $file, $line) {
//            Yii::log("error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>");
//            echo "error:<b>$errno</b>!\n";
//            echo "File: <tt>$file</tt>, line $line.\n";
//            echo "Text: <i>$msg</i>\n";
//        }
//
//        set_error_handler("myErrorHandler", E_ALL);
//        $path = realpath(Yii::app()->basePath . '/../upload_files/1c_data') . '/';
////        header('Content-Type: application/xml; charset=utf-8');
//        $type = 'clients';
//        UserProfile::model()->import1CUsers($path . $type . '.xml');
//        echo "success\n";
//    }

}
