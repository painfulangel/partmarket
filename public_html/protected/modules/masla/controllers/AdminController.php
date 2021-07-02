<?php
class AdminController extends MaslaController {
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
    		$model = $this->loadModel($id);
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
    			'actions' => array('index', 'create', 'update', 'delete', 'toggle'),
    			'roles' => array('mainManager', 'admin'),
    		),
    		array('deny', // deny all users
    			'users' => array('*'),
    		),
    	);
    }
    
    public function actionIndex() {
    	$model = new Masla('search');
    	$model->unsetAttributes();  // clear any default values
    	if (isset($_GET['Masla']))
    		$model->attributes = $_GET['Masla'];
    	
    	$this->render('index', array(
    		'model' => $model,
    	));
    }

    public function actionCreate() {
    	$model = new Masla('create');
    	 
    	// Uncomment the following line if AJAX validation is needed
    	// $this->performAjaxValidation($model);
    	 
    	if (isset($_POST['Masla'])) {
    		$model->attributes = $_POST['Masla'];
    		if ($model->save())
    			$this->redirect(array('index'));
    	}
    	 
    	$this->render('create', array(
    		'model' => $model,
    	));
    }
    
    public function actionUpdate($id) {
    	$model = $this->loadModel($id);
    	
    	// Uncomment the following line if AJAX validation is needed
    	// $this->performAjaxValidation($model);
    	
    	if (isset($_POST['Masla'])) {
    		$model->attributes = $_POST['Masla'];
    		
    		if ($model->save()) {
    			$this->redirect(array('index'));
    		}
    	}
    	
    	$this->render('update', array(
    		'model' => $model,
    	));
    }
    
    public function actionDelete($id) {
    	if (Yii::app()->request->isPostRequest) {
    		// we only allow deletion via POST request
    		$this->loadModel($id)->delete();
    
    		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    		if (!isset($_GET['ajax']))
    			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    	} else
    		throw new CHttpException(400, Yii::t('masla', 'This Page not found.'));
    }
    
    public function loadModel($id) {
    	$model = Masla::model()->findByPk($id);
    	if ($model === null)
    		throw new CHttpException(404, Yii::t('masla', 'This page doesn\'t exist.'));
    	return $model;
    }
}