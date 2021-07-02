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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'priceTable', 'priceSave', 'admin', 'delete', 'deletePrice', 'exportTable', 'toggle', 'ruleAdmin'),
                'roles' => array('mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionExportTable($id) {
        $model = $this->loadModel($id);
        header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
        header('Last-Modified: ' . gmdate("D,d M YH:i:s") . ' GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=export_prices_' . $id . '_cp1251.csv');

        echo $model->exportPrices();
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Prices('create');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Prices'])) {
            $model->attributes = $_POST['Prices'];
            if ($model->save())
                $this->redirect(array('admin'));
        }

        $priceGroupsList = new PricesRulesGroups;
        $stores = new Stores;
        $currencies = new Currencies;
        $this->render('create', array(
            'model' => $model,
            'priceGroupsList' => $priceGroupsList->getList(),
            'stores' => $stores->getList(),
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

        if (isset($_POST['Prices'])) {
            $model->attributes = $_POST['Prices'];
            if ($model->save())
                $this->redirect(array('admin', 'Prices_page' => (isset($_GET['Prices_page']) ? $_GET['Prices_page'] : '')));
        }

        $priceGroupsList = new PricesRulesGroups;
        $stores = new Stores;
        $currencies = new Currencies;
        $this->render('update', array(
            'model' => $model,
            'priceGroupsList' => $priceGroupsList->getList(),
            'stores' => $stores->getList(),
            'currencies' => $currencies->getList(),
        ));
    }

    /**
     * Sets the new prices sent from admin prices page by POST in arrays
     * new_price1 = array($id=>new_price1)
     * new_price2 = array($id=>new_price2)
     * $id corresponds to the record in database 'prices'
     */
    public function actionPriceSave() {
        $name = Yii::app()->request->getPost('name', array());
        $brand = Yii::app()->request->getPost('brand', array());
        $price = Yii::app()->request->getPost('price', array());
        $quantum = Yii::app()->request->getPost('quantum', array());
        $article = Yii::app()->request->getPost('article', array());
        $delivery = Yii::app()->request->getPost('delivery', array());

        foreach ($name as $id => $value) {
            if (!is_int($id))
                continue;
            if (!isset($brand[$id]) || !isset($price[$id]) || !isset($quantum[$id]) || !isset($article[$id]) || !isset($delivery[$id]))
                continue;
            if (empty($name[$id]) || empty($brand[$id]) || empty($price[$id]) ||
                    empty($quantum[$id]) || empty($article[$id]) || empty($delivery[$id]))
                continue;

            $model = PricesData::model()->findByPk($id);
            if ($model === null)
                continue;
            if ($model->name != $name[$id] || $model->brand != $brand[$id] ||
                    $model->price != $price[$id] || $model->quantum != $quantum[$id] ||
                    $model->original_article != $article[$id] || $model->delivery != $delivery[$id]
            ) {
                $model->name = $name[$id];
                $model->brand = $brand[$id];
                $model->price = $price[$id];
                $model->quantum = $quantum[$id];
                $model->original_article = $article[$id];
                $model->article = preg_replace("/[^a-zA-Z0-9]/", "", $model->original_article);
                $model->delivery = $delivery[$id];
                $model->save();
            }
        }
        echo CJSON::encode("Ok");
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionPriceTable($id) {
        $model = new PricesData('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PricesData']))
            $model->attributes = $_GET['PricesData'];

        $model->price_id = $id;

        $this->render('price_table', array(
            'model' => $model,
        ));
    }

    public function actionRuleAdmin($id) {
        $model = new PricesData('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PricesData']))
            $model->attributes = $_GET['PricesData'];

        $model->rule_id = $id;

        $this->render('price_table_rule', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDeletePrice($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request

            $this->loadPriceModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, Yii::t('prices', 'This page doesn\'t exist.'));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request

            $model = $this->loadModel($id);

            $model->deleteAllSubPrices();
            $model->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, Yii::t('prices', 'This page doesn\'t exist.'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Prices('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Prices']))
            $model->attributes = $_GET['Prices'];

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
        $model = Prices::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadPriceModel($id) {
        $model = PricesData::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'prices-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}