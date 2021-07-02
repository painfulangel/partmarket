<?php
class AdminDeliveryTransportController extends Controller {
    public $layout = '//layouts/admin_column2';
    public $admin_header = array();
    
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
                'actions' => array('index', 'create', 'update', 'delete', 'toggle'),
                'roles' => array('mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    public function actionToggle($id, $attribute) {
        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModel($id);
            $model->$attribute = ($model->$attribute == 0) ? 1 : 0;
            $model->save(false);
            
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, Yii::t('Delivery', 'This Page not found.'));
    }
    
    protected function beforeAction($action)
    {
        $url = Yii::app()->urlManager->parseUrl(Yii::app()->request);
        
        $this->admin_header = array(
            array(
                'name' => Yii::t('shop_cart', 'Orders'),
                'url' => array('/shop_cart/adminOrders/admin'),
                'active' => false,
            ),
            array(
                'name' => Yii::t('shop_cart', 'Goods'),
                'url' => array('/shop_cart/adminItems/admin'),
                'active' => false,
            ),
            array(
                'name' => Yii::t('delivery', 'Delivery'),
                'url' => array('/shop_cart/adminDelivery/index'),
                'active' => false,
            ),
            array(
                'name' => Yii::t('delivery', 'Transport companies'),
                'url' => array('/shop_cart/adminDeliveryTransport/index'),
                'active' => true,
            ),
        );

        return true;
    }
    
    public function actionIndex() {
        $model = new DeliveryTransport('search');
        $model->unsetAttributes();
        
        if (isset($_GET['DeliveryTransport']))
            $model->attributes = $_GET['DeliveryTransport'];
            
            $this->render('index', array(
                'model' => $model,
            ));
    }
    
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new DeliveryTransport;
        
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
        if (isset($_POST['DeliveryTransport'])) {
            $model->attributes = $_POST['DeliveryTransport'];
            $model->original_data = true;
            if ($model->validate()) {
                if ($model->save()) {
                    $this->redirect(array('index'));
                }
            }
        }
        
        $this->render('create', array(
            'model' => $model,
        ));
    }
    
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
        if (isset($_POST['DeliveryTransport'])) {
            $model->attributes = $_POST['DeliveryTransport'];
            $model->original_data = true;
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }
        
        $this->render('update', array(
            'model' => $model,
        ));
    }
    
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        //if (Yii::app()->request->isPostRequest) {
        // we only allow deletion via POST request
        $this->loadModel($id)->delete();
        
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = DeliveryTransport::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('Delivery', 'This Page not found.'));
            return $model;
    }
}