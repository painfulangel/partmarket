<?php
class AdminPropertyController extends MaslaController {
	public $layout = '//layouts/admin_column2';
	
	public function __construct($id, $module = null)
	{
		parent::__construct($id, $module);
	
	}
	
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function actionToggle($id, $attribute) {
		if (Yii::app()->request->isPostRequest) {
			if ($attribute == 'popular') {
				$model = MaslaPropertyValues::model()->findByPk($id);
			} else {
				$model = $this->loadModel($id);
			}
			
			$model->$attribute = ($model->$attribute == 0) ? 1 : 0;
			$model->save(false);
	
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		} else
			throw new CHttpException(400, Yii::t('masla', 'This page doesn\'t exist.'));
	}
	
    public function accessRules() {
    	return array(
    		array('allow', // allow all users to perform 'index' and 'view' actions
    			'actions' => array('index', 'property', 'toggle', 'create', 'update', 'delete'),
    			'roles' => array('mainManager', 'admin'),
    		),
    		array('deny', // deny all users
    			'users' => array('*'),
    		),
    	);
    }
    
    public function actionIndex() {
    	$model = new MaslaProperty('search');
    	$model->unsetAttributes();  // clear any default values
    	if (isset($_GET['MaslaProperty']))
    		$model->attributes = $_GET['MaslaProperty'];
    		 
    	$this->render('index', array(
    		'model' => $model,
    	));
    }
    
    public function actionProperty($id) {
    	//Show property values with this id
    	$model = $this->loadModel($id);
    
    	$model2 = MaslaPropertyValues::model();
    	$model2->id_chars = $model->primaryKey;
    
    	$this->render('property', array('model' => $model, 'model2' => $model2));
    }

    public function actionCreate($id_property) {
    	$property = $this->loadModel($id_property);
    	
    	$model = new MaslaPropertyValues('create');
    		
    	// Uncomment the following line if AJAX validation is needed
    	// $this->performAjaxValidation($model);
    		
    	if (isset($_POST['MaslaPropertyValues'])) {
    		$model->attributes = $_POST['MaslaPropertyValues'];
    		
    		if ($model->save())
    			$this->redirect(array('property', 'id' => $id_property));
    	}
    
    	$model->id_chars = $id_property;
    
    	$this->render('create', array(
    		'model' => $model,
    		'property' => $property,
    	));
    }
    
    public function actionUpdate($id) {
    	$model = $this->loadPropertyValueModel($id);
    	
    	$property = $this->loadModel($model->id_chars);
    	
    	// Uncomment the following line if AJAX validation is needed
    	// $this->performAjaxValidation($model);
    		
    	if (isset($_POST['MaslaPropertyValues'])) {
    		$model->attributes = $_POST['MaslaPropertyValues'];
    
    		if ($model->save()) {
    			$this->redirect(array('property', 'id' => $model->id_chars));
    		}
    	}
    		
    	$this->render('update', array(
    		'model' => $model,
    		'property' => $property,
    	));
    }
    
    public function actionDelete($id) {
    	if (Yii::app()->request->isPostRequest) {
    		// we only allow deletion via POST request
    		$this->loadPropertyValueModel($id)->delete();
    
    		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    		if (!isset($_GET['ajax']))
    			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    	} else
    		throw new CHttpException(400, Yii::t('masla', 'This Page not found.'));
    }
    
    public function loadModel($id) {
    	$model = MaslaProperty::model()->findByPk($id);
    	if ($model === null)
    		throw new CHttpException(404, Yii::t('masla', 'This page doesn\'t exist.'));
    	return $model;
    }
	
	public function loadPropertyValueModel($id) {
		$model = MaslaPropertyValues::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('masla', 'This page doesn\'t exist.'));
		return $model;
	}
}