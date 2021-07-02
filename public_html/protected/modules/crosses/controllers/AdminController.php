<?php
class AdminController extends Controller {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/admin_column2';
    
    public $top_menu = array();
    
    protected function beforeAction($action)
    {
    	$this->top_menu = array(
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
		        'active' => true,
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
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function actions()
    {
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
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                  'actions' => array('newCrossBase', 'updateCrossBase', 'deleteCrossBase', 'crossFiles', 'create', 'update', 'crossBase', 'createElement', 'crossSave', 'admin', 'delete', 'deleteCross', 'exportBase', 'toggle'),
                  'roles' => array('mainManager', 'admin'),
            ),
        	array('allow', // allow all users to perform 'index' and 'view' actions
        		  'actions' => array('processCrossFiles'),
        		  'users' => array('*'),
        	),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    /**
     * Просмотр всех баз кроссов
     */
    public function actionAdmin($successCreate = 0)
    {
    	/*if (array_key_exists('i', $_GET) && ($_GET['i'] == 1)) {
    		Yii::import('application.modules.shop_cart.components.*');
    		
    		echo get_class(Yii::app()->mail).'<br>';
    		
    		$order = Orders::model()->findByPk(71);
    		
    		$res = $order->sendEmailNotification(array(), Orders::$NEW_ORDER);
    		echo $res;
    	}*/
    	
    	if ($successCreate == 1) {
    		Yii::app()->user->setFlash('info', Yii::t('crosses', 'Saved.'));
    	}
    	$model = new CrossesBase('search');
    	$model->unsetAttributes();  // clear any default values
    	if (isset($_GET['CrossesBase']))
    		$model->attributes = $_GET['CrossesBase'];
    
    	$this->render('admin', array(
    			'model' => $model,
    			'top_menu' => $this->top_menu,
    	));
    }
    
    /**
     * Добавление новой базы кроссов
     */
    public function actionNewCrossBase()
    {
    	$model = new CrossesBase();
    	
    	if (is_array($_POST) && array_key_exists('CrossesBase', $_POST)) {
    		$model->attributes = $_POST['CrossesBase'];
    		if ($model->save())
    			$this->redirect(array('admin'));
    	}
    	
    	$this->render('create_cross_base', array(
            'model' => $model,
    		'top_menu' => $this->top_menu,
        ));
    }
    
    /**
     * Обновление существующей базы кроссов
     */
    public function actionUpdateCrossBase($id)
    {
    	$model = $this->loadModel($id);
    
    	// Uncomment the following line if AJAX validation is needed
    	// $this->performAjaxValidation($model);
    
    	if (isset($_POST['CrossesBase'])) {
    		$model->attributes = $_POST['CrossesBase'];
    		if ($model->save())
    			$this->redirect(array('admin'));
    	}
    
    	$this->render('update_cross_base', array(
    			'model' => $model,
    			'top_menu' => $this->top_menu,
    	));
    }
    
    
    /**
     * Удаление базы кроссов
     */
    public function actionDeleteCrossBase($id)
    {
    	if (Yii::app()->request->isPostRequest) {
    		// we only allow deletion via POST request
    		$model = $this->loadModel($id);
    
    		$model->deleteAllSubCrosses($id);
    		$model->delete();
    		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    		if (!isset($_GET['ajax']))
    			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    	} else
    		throw new CHttpException(400, Yii::t('crosses', 'This page doesn\'t exist.'));
    }
    
    /**
     * Добавление к базе нового кросса
     */
    public function actionCreateElement($base_id=0)
    {
        $model = new CrossesData;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['CrossesData'])) {
            $model->attributes = $_POST['CrossesData'];
            
        	$model->cross_id = 0;
        	
            if ($model->validate() && $model->saveElement()) {
                $this->redirect(array('crossBase', 'base_id' => $base_id));
            }
        }
        
        $model->base_id = $base_id;

        $this->render('create_element', array(
            'model' => $model,
    		'top_menu' => $this->top_menu,
        	'base_id' => $base_id,
        ));
    }

    /**
     * Экспорт базы кроссов
     */
    public function actionExportBase($id)
    {
        $model = $this->loadModel($id);
        header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
        header('Last-Modified: ' . gmdate("D,d M YH:i:s") . ' GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=export_croses_' . $id . '_cp1251.csv');

        echo $model->exportCroses();
    }
    
    /**
     * Просмотр данных базы кроссов
     */
    public function actionCrossBase($base_id = '')
    {
    	$table = $this->loadModel($base_id);
    	
    	if (is_object($table)) {
	    	$model = new CrossesData('search');
	    	$model->unsetAttributes();  // clear any default values
	    	if (isset($_GET['CrossesData']))
	    		$model->attributes = $_GET['CrossesData'];
	    	if (!empty($base_id))
	    		$model->base_id = $base_id;
	    
	    	$this->render('cross_base', array(
	    			'model' => $model,
		    		'top_menu' => $this->top_menu,
	    			'cross' => $table,
	    	));
    	} else {
    		$this->redirect(array('admin'));
    	}
    }
    
    /**
     * Загрузка файла с кроссами
     */
    public function actionCreate($base_id)
    {
        $model = new Crosses('create');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Crosses'])) {
            $model->attributes = $_POST['Crosses'];
            $model->base_id = $base_id;
            if ($model->save())
            	$this->redirect(array('admin'));
                //$this->redirect(array('crossBase', 'base_id' => $base_id));
        }

        $this->render('create', array(
            'model' => $model,
    		'top_menu' => $this->top_menu,
        	'base_id' => intval($base_id),
        ));
    }

    /**
     * Sets the new prices sent from admin prices page by POST in arrays
     * new_price1 = array($id=>new_price1)
     * new_price2 = array($id=>new_price2)
     * $id corresponds to the record in database 'prices'
     */
    public function actionCrossSave()
    {
        $original_articles = Yii::app()->request->getPost('original_article', array());
//        $cross_articles = Yii::app()->request->getPost('cross_article', array());
        $original_brands = Yii::app()->request->getPost('original_brand', array());
        $partsid = Yii::app()->request->getPost('partsid', array());

        foreach ($original_articles as $id => $original_article) {
            if (!is_int($id))
                continue;
            if (!isset($partsid[$id]) || !isset($original_brands[$id]) )
                continue;
            if (empty($original_articles[$id]) )
                continue;

            $model = CrossesData::model()->findByPk($id);
            if ($model === null)
                continue;
            if ($model->origion_article != $original_articles[$id] || $model->partsid != $partsid[$id] ||
                $model->origion_brand != $original_brands[$id]
            ) {
                $model->origion_article = $original_articles[$id];
                $model->partsid= $partsid[$id];
                $model->origion_brand = $original_brands[$id];
                $model->save();
            }
        }
        echo CJSON::encode("Ok");
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDeleteCross($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request

            $this->loadCrossDataModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, Yii::t('crosses', 'This page doesn\'t exist.'));
    }
    
    public function loadCrossModel($id)
    {
        $model = Crosses::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('crosses', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
    	$model = CrossesBase::model()->findByPk($id);
    	if ($model === null)
    		throw new CHttpException(404, Yii::t('crosses', 'This page doesn\'t exist.'));
    	return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadCrossDataModel($id)
    {
        $model = CrossesData::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('crosses', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'crosses-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    public function actionProcessCrossFiles() {
    	$cross = Crosses::model()->find('processed = 0 AND file_count > 0');
    	
    	if (is_object($cross)) {
    		$cross->isProgramWork = false;
    		$cross->process();
    	}
    }
}