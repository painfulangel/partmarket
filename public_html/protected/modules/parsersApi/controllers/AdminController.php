<?php
class AdminController extends Controller {
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
		        'active' => true,
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
    
    public function actions() {
        return array(
            'toggle' => 'ext.jtogglecolumn.ToggleAction',
            'switch' => 'ext.jtogglecolumn.SwitchAction', // only if you need it
            'qtoggle' => 'ext.jtogglecolumn.QtoggleAction', // only if you need it
        );
    }

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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'admin', 'delete', 'toggle'),
                'roles' => array('mainManager', 'admin'),
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
    public function actionCreate() {
        $model = new ParsersApi;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ParsersApi'])) {
            $model->attributes = $_POST['ParsersApi'];
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

        if (isset($_POST['ParsersApi'])) {
            $model->attributes = $_POST['ParsersApi'];
            if ($model->save())
                $this->redirect(array('admin', 'ParsersApi_page' => (isset($_GET['ParsersApi_page']) ? $_GET['ParsersApi_page'] : '')));
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
            throw new CHttpException(400, Yii::t('crosses', 'This page doesn\'t exist.'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        Yii::app()->controller->module->UpdateData();

        $model = new ParsersApi('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ParsersApi']))
            $model->attributes = $_GET['ParsersApi'];

        $model_all = new ParsersApiAll('search');
        $model_all->unsetAttributes();  // clear any default values
        if (isset($_GET['ParsersApiAll']))
            $model_all->attributes = $_GET['ParsersApiAll'];

        $this->render('admin', array(
            'model' => $model,
            'model_all' => $model_all,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = ParsersApi::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('crosses', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'parsers-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}