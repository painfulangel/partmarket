<?php

class AdminMakeOrdersController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/admin_column2';

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
                'actions' => array('order', 'initStep', 'create'),
                'roles' => array('managerNotDiscount', 'manager', 'mainManager', 'admin'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('getOrderTotalBlock'),
                'roles' => array('managerNotDiscount', 'manager', 'mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionGetOrderTotalBlock($type) {
        $this->renderPartial('_order_total_block', array(
            'type' => $type,
        ));
    }

    public function actionInitStep() {

        $this->redirect(array('create'));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($force = 0) {

        $model = new Orders;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);
        if (isset($_POST['Orders'])) {
            $model->user_id = UserProfile::getUserActiveId();
            $model->attributes = $_POST['Orders'];

            if ($model->save()) {
                Yii::app()->user->setState('order_state', CJSON::encode(array()));

                $this->redirect(array('/shop_cart/adminOrders/admin'));
            }
        } else {
            $user = UserProfile::model()->findByAttributes(array('uid' => UserProfile::getUserActiveId()));
            $model->initUserData($user);
        }

        $this->render('create', array(
            'model' => $model,
            'model_cart' => new ShopProducts,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Orders::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('shop_cart', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'orders-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
