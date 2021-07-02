<?php
class AdminItemsController extends Controller {
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
                'active' => true,
            ),
            array(
                'name' => Yii::t('delivery', 'Delivery'),
                'url' => array('/shop_cart/adminDelivery/index'),
                'active' => false,
            ),
            array(
                'name' => Yii::t('delivery', 'Transport companies'),
                'url' => array('/shop_cart/adminDeliveryTransport/index'),
                'active' => false,
            ),
    	);

        return true;
    }
    
    public function actions() {
        return array(
            'toggle' => 'ext.jtogglecolumn.ToggleAction',
            'switch' => 'ext.jtogglecolumn.SwitchAction', // only if you need it
            'qtoggle' => 'ext.jtogglecolumn.QtoggleAction', // only if you need it
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update', 'admin', 'delete', 'save', 'updateStatus', 'create', 'toggle', 'supplierOrder', 'getSupplierOrderFile'),
                'roles' => array('managerNotDiscount','manager','mainManager','admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionGetSupplierOrderFile() {
        header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
        header('Last-Modified: ' . gmdate("D,d M YH:i:s") . ' GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=export_supplier_cp1251.csv');
        $model = new SupplierItems;
        echo $model->export();
    }

    public function actionSupplierOrder() {
        $model = new SupplierItems('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SupplierItems']))
            $model->attributes = $_GET['SupplierItems'];

        $orderStatus = new OrdersStatus;
        $this->render('suppliers', array(
            'model' => $model,
            'orderStatus' => $orderStatus,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Items;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Items'])) {
            $model->attributes = $_POST['Items'];
            if ($model->save()) {
                $this->redirect(array('adminOrders/update', 'id' => $model->order_id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdateStatus() {
        $model_id = Yii::app()->request->getPost('model_id', null);
        $status_id = Yii::app()->request->getPost('status_id', null);


        $model = new ItemsStatus;

        $msg = $model->changeStatus(Items::model()->findByPk($model_id), $status_id);
        echo CJSON::encode(array('msg' => $msg));
    }

    public function actionSave() {
        $model = $this->loadModel(Yii::app()->request->getPost('id', 0));

        if (intval(Yii::app()->request->getPost('front', 0)) == 0) {
            $model->status = Yii::app()->request->getPost('status', '');
            $model->supplier = Yii::app()->request->getPost('supplier', '');
            $model->delivery = Yii::app()->request->getPost('delivery', '');
            
            $model->price = Yii::app()->request->getPost('price', '');
            $model->article = Yii::app()->request->getPost('article', '');
            $model->name = Yii::app()->request->getPost('name', '');
            $model->brand = Yii::app()->request->getPost('brand', '');
        }
        
        $model->quantum = Yii::app()->request->getPost('quantum', '');

        $model->save();

        echo CJSON::encode(array('msg' => Yii::t('shop_cart', 'Data is saved')));
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

        if (isset($_POST['Items'])) {
            $model->attributes = $_POST['Items'];
            if ($model->save())
                $this->redirect(array('admin', 'Items_page' => (isset($_GET['Items_page']) ? $_GET['Items_page'] : '')));
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
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, Yii::t('shop_cart', 'This page doesn\'t exist.'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Items('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Items']))
            $model->attributes = $_GET['Items'];

        $itemStatus = new ItemsStatus;
        $this->render('admin', array(
            'model' => $model,
            'itemStatus' => $itemStatus,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Items::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('shop_cart', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'items-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
