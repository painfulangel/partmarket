<?php
class AdminPricesExportRulesController extends Controller {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/admin_column2';
    public $admin_header = array();
    
    protected function beforeAction($action)
    {
    	$this->admin_header = array(
			array(
				'name' => Yii::t('prices', 'Editing warehouses'),
				'url' => array('/prices/adminStores/admin'),
				'active' => false,
			),
			array(
				'name' => Yii::t('prices', 'Prices'),
				'url' => array('/prices/admin/admin'),
				'active' => true,
			),
			array(
				'name' => Yii::t('crosses', 'Cross-tables'),
				'url' => array('/crosses/admin/admin'),
				'active' => false,
			),
			array(
				'name' => Yii::t('admin_layout', 'Suppliers'),
				'url' => array('/parsersApi/admin/admin'),
				'active' => false,
			),
			array(
				'name' => Yii::t('shop_cart', 'Orders to suppliers'),
				'url' => array('/shop_cart/adminItems/supplierOrder'),
				'active' => false,
			),
    	);

        return true;
    }
    
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
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
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions'=>array('index','view'),
//				'users'=>array('*'),
//			),

            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'create', 'update', 'test', 'toggle', 'start'),
                'roles' => array('mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionStart($id) {
        $model = $this->loadModel($id);
        
        BackgroundProcess::launchBackgroundProcessStart('php ' . realpath(Yii::app()->basePath) . '/yiic.php PriceExport ' . $id);
        $this->render('start', array('model' => $model));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new PricesExportRules;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PricesExportRules'])) {
            $model->attributes = $_POST['PricesExportRules'];
            $model->initStores();
            foreach ($model->_stores as $key => $value) {
                $model->_stores[$key]['row_data'] = isset($_POST['stores'][$key]) ? $_POST['stores'][$key] : '';
//                $model->_stores[$key]['row_id'] = isset($_POST['stores_row'][$key]) ? $_POST['stores_row'][$key] : '';
            }
            if ($model->save())
                $this->redirect(array('admin'));
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

        if (isset($_POST['PricesExportRules'])) {
            $model->attributes = $_POST['PricesExportRules'];
            $model->initStores();
            foreach ($model->_stores as $key => $value) {
                $model->_stores[$key]['row_data'] = isset($_POST['stores'][$key]) ? $_POST['stores'][$key] : '';
            }
            if ($model->save())
                $this->redirect(array('admin'));
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
            throw new CHttpException(400,Yii::t('prices', 'This page doesn\'t exist.'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PricesExportRules('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PricesExportRules']))
            $model->attributes = $_GET['PricesExportRules'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = PricesExportRules::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'prices-export-rules-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}