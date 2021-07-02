<?php

class WebPaymentsPayPalController extends Controller {

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
                'actions' => array('success', 'fail', 'result', 'pay'),
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
        $model = new WebPaymentsPaypal;
        if ($sum != 0) {
            $model->value = $sum;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['WebPaymentsPaypal'])) {
            $model->attributes = $_POST['WebPaymentsPaypal'];
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
        $model = new WebPaymentsPaypal;
        $out_summ = $_REQUEST["OutSum"];
        $inv_id = $_REQUEST["InvId"];
        $shp_item = $_REQUEST["Shp_item"];
        $crc = $_REQUEST["SignatureValue"];

        $crc = strtoupper($crc);
        $model = $this->loadModel($inv_id);
        if (strtoupper($model->getSign()) == $crc) {
            $model->scenario = 'finish';
            echo "OK$inv_id\n";
            $model->save();
        } else {
            echo "bad sign\n";
        }
    }

    public function actionSuccess() {
        $model = new WebPaymentsPaypal;
        $out_summ = $_REQUEST["OutSum"];
        $inv_id = $_REQUEST["InvId"];
        $shp_item = $_REQUEST["Shp_item"];
        $crc = $_REQUEST["SignatureValue"];
        $crc = strtoupper($crc);

        $model = $this->loadModel($inv_id);

        if (strtoupper($model->getSign()) == $crc) {
            $this->redirect(array('webPayments/view', 'token' => $model->auth_key));
        }
        throw new CHttpException('400', 'Неправильный запрос. Обратитесь к администратору для выяснения состояния своего платежа.');
    }

    public function actionFail() {
        $inv_id = $_REQUEST["InvId"];
        throw new CHttpException('400', 'Вы отказались от платежа');
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionPay($token) {
        $model = WebPaymentsPaypal::model()->findByAttributes(array('auth_key' => $token));


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
        $model = WebPaymentsPaypal::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
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
