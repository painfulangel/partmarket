<?php

class YandexACController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('success', 'fail', 'check', 'result', 'pay'),
                'users' => array('*'),
            ),
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('create'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($sum = 0) {
        $model = new WebPaymentsYandex;
        if ($sum != 0) {
            $model->value = $sum;
        }
// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['WebPaymentsYandex'])) {
            $model->attributes = $_POST['WebPaymentsYandex'];
            if ($sum != 0 && $model->value < $sum) {
                $model->value = $sum;
            }
            if ($model->save())
                $this->redirect(array('pay', 'token' => $model->auth_key));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionResult() {
        Yii::log('Yandex result' . date('d.m.Y'));
        $model = $this->loadModel($_REQUEST["orderNumber"]);
        if ($model != NULL) {
            if ($_REQUEST["md5"] != strtoupper($model->getSign())) {
                echo '<?xml version="1.0" encoding="UTF-8" ?>' .
                '<paymentAvisoResponse  performedDatetime="' . date(DATE_ATOM) . '" code="1" invoiceId="' . $_REQUEST["invoiceId"] . '" shopId="' . $model->system_login . '" message="Данные при проверке запроса не соппадают, при повторении ошибки свяжитесь с администратором." techMessage="Данные при проверке запроса не соппадают, ид транзакции сайта ' . $_REQUEST["orderNumber"] . '"/>';
            } else {
                Yii::log('Yandex result2 ' . date('d.m.Y'));
                if ($model != NULL) {
                    echo '<?xml version="1.0" encoding="UTF-8"?>' .
                    '<paymentAvisoResponse  performedDatetime="' . date(DATE_ATOM) . '" code="0" invoiceId="' . $_REQUEST["invoiceId"] . '" shopId="' . $model->system_login . '"/>';
                    $model->scenario = 'finish';
                    $model->save();
                } else {
                    Yii::log('Yandex result3 ' . date('d.m.Y'));
                    echo '<?xml version = "1.0" encoding = "UTF-8" ?>' .
                    '<paymentAvisoResponse performedDatetime="' . date(DATE_ATOM) . '" ' .
                    'code=200" invoiceId = "' . $_REQUEST["invoiceId"] . '" shopId = "' . $model->system_login . '" ' .
                    'message = "Данные при проверке запроса не соппадают, при повторении ошибки свяжитесь с администратором." ' .
                    'techMessage = "Данные при проверке запроса не соппадают, ид транзакции сайта ' . $_REQUEST["orderNumber"] . '" />';
                }
            }
        } else {
            Yii::log('Yandex Check not find model' . date('d.m.Y'));
        }

        $model = new WebPaymentsYandex;
    }

    public function actionCheck() {

        Yii::log('Yandex Check' . date('d.m.Y'));
        $model = $this->loadModel($_POST["orderNumber"]);
        if ($model != NULL) {
            if ($_POST["md5"] != strtoupper($model->getSign())) {
                header("Content-type: text/xml; charset=utf-8");
                echo '<?xml version = "1.0" encoding = "UTF-8" ?><checkOrderResponse performedDatetime="' . date(DATE_ATOM) . '" '
                . 'code="1" invoiceId="' . $_POST["invoiceId"] . '" '
                . 'shopId="' . $model->system_login . '" '
                . 'message="Данные при проверке запроса не соппадают, при повторении ошибки свяжитесь с администратором." '
                . 'techMessage="Данные при проверке запроса не соппадают, ид транзакции сайта ' . $_POST["orderNumber"] . '"/>';
            } else
            if ($model != NULL) {
                header("Content-type: text/xml; charset=utf-8");
                echo '<?xml version="1.0" encoding="UTF-8"?> <checkOrderResponse performedDatetime="' . date(DATE_ATOM) . '" code="0" invoiceId="' . $_POST["invoiceId"] . '" shopId="' . $model->system_login . '"/>';
            } else {
                header("Content-type: text/xml; charset=utf-8");
                echo '<?xml version = "1.0" encoding = "UTF-8"?> <checkOrderResponse performedDatetime="' . date(DATE_ATOM) . '"'
                . ' code="100" invoiceId="' . $_POST["invoiceId"] . '"'
                . ' shopId="' . $model->system_login . '" message="Данные при проверке запроса не соппадают, при повторении ошибки свяжитесь с администратором."'
                . ' techMessage="Данные при проверке запроса не соппадают, ид транзакции сайта ' . $_POST["orderNumber"] . '"/>';
            }
        } else {
            Yii::log('Yandex Check not find model' . date('d.m.Y'));
        }
    }

    public function actionSuccess() {
        Yii::log('Yandex Success' . date('d.m.Y'));
        //        $model = new WebPaymentsYandex;
        //        $out_summ = $_REQUEST["OutSum"];
        //        $inv_id = $_REQUEST["InvId"];
        //        $shp_item = $_REQUEST["Shp_item"];
        //        $crc = $_REQUEST["SignatureValue"];
        //        $crc = strtoupper($crc);

        $model = $this->loadModel($_REQUEST["orderNumber"]);

        if ($model != NULL) {
            $this->redirect(array('webPayments/view', 'token' => $model->auth_key));
        }
        //        throw new CHttpException('400', 'Неправильный запрос. Обратитесь к администратору для выяснения состояния своего платежа.');
    }

    public function actionFail() {
        Yii::log('Yandex Fail' . date('d.m.Y'));
        $inv_id = $_REQUEST["InvId"];
        throw new CHttpException('400', Yii::t('webPayments', 'You refused payment'));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionPay($token) {
        $model = WebPaymentsYandex::model()->findByAttributes(array('auth_key' => $token));


        $this->renderPartial('pay', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = WebPaymentsYandex::model()->findByPk($id);
        if ($model === null)
            return NULL;
        //            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'web-payments-robokassa-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
