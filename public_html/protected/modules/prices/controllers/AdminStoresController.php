<?php
class AdminStoresController extends Controller {
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
		        'active' => true,
		    ),
		    array(
		        'name' => Yii::t('prices', 'Prices'),
		        'url' => array('/prices/admin/admin'),
		        'active' => false,
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
		        'name' =>Yii::t('shop_cart', 'Orders to suppliers'),
		        'url' => array('/shop_cart/adminItems/supplierOrder'),
		        'active' => false,
		    ),
			array(
				'name' =>Yii::t('prices', 'Search meta-tags'),
				'url' => array('/prices/adminMeta/admin'),
				'active' => false,
			),
            array(
                'name' =>Yii::t('brands', 'Brands'),
                'url' => array('/brands/admin/admin'),
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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('create', 'update', 'admin', 'delete', 'toggle', 'getFormBlock', 'deleteAllPrices'),
                'roles' => array('mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionGetFormBlock($id) {
        $model_store = $this->loadModel($id);

        $model = new Prices;
        $model->attributes = $model_store->getAttributes();

        $priceGroupsList = new PricesRulesGroups;
        $currencies = new Currencies;
        $this->renderPartial('_getFormBlock', array(
            'model' => $model,
            'priceGroupsList' => $priceGroupsList->getList(),
            'currencies' => $currencies->getList(),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Stores();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Stores'])) {
            $model->attributes = $_POST['Stores'];
            if ($model->save())
                $this->redirect(array('admin'));
        }

        $priceGroupsList = new PricesRulesGroups;
        $currencies = new Currencies;
        $this->render('create', array(
            'model' => $model,
            'priceGroupsList' => $priceGroupsList->getList(),
            'currencies' => $currencies->getList(),
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

        if (isset($_POST['Stores'])) {
            $model->attributes = $_POST['Stores'];
            if ($model->save())
                $this->redirect(array('admin', 'Stores_page' => (isset($_GET['Stores_page']) ? $_GET['Stores_page'] : '')));
        }

        $priceGroupsList = new PricesRulesGroups;
        $currencies = new Currencies;
        $this->render('update', array(
            'model' => $model,
            'priceGroupsList' => $priceGroupsList->getList(),
            'currencies' => $currencies->getList(),
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
            throw new CHttpException(400, Yii::t('prices', 'This page doesn\'t exist.'));
    }

    public function actionDeleteAllPrices($id) {
        $models = Prices::model()->findAllByAttributes(array('store_id' => $id));
        foreach ($models as $model) {
            $model->deleteAllSubPrices();
            $model->delete();
        }
        $this->redirect(array('admin'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Stores('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Stores']))
            $model->attributes = $_GET['Stores'];

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
        $model = Stores::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'stores-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}