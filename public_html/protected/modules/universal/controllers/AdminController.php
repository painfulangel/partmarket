<?php
class AdminController extends UniversalController {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/admin_column2';
    
    public function __construct($id, $module = null) {
    	parent::__construct($id, $module);
    
    }
    
    public function filters() {
    	return array(
    		'accessControl', // perform access control for CRUD operations
    	);
    }
    
    public function accessRules() {
    	return array(
    			array('allow', // allow all users to perform 'index' and 'view' actions
    					'actions' => array('view', 'create', 'update', 'admin', 'delete', 'toggle', 'order'),
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
    		throw new CHttpException(400, Yii::t('universal', 'This page doesn\'t exist.'));
    }
    
    public function actionAdmin() {
    	$model = new UniversalRazdel('search');
    	$model->unsetAttributes();  // clear any default values
    	if (isset($_GET['UniversalRazdel']))
    		$model->attributes = $_GET['UniversalRazdel'];
    
    	$this->render('admin', array(
    		'model' => $model,
    	));
    }
    
    public function actionCreate() {
    	$model = new UniversalRazdel('create');
    	
    	// Uncomment the following line if AJAX validation is needed
    	// $this->performAjaxValidation($model);
    	
    	if (isset($_POST['UniversalRazdel'])) {
    		$model->attributes = $_POST['UniversalRazdel'];
    		if ($model->save())
    			$this->redirect(array('admin'));
    	}
    	
    	$this->render('create', array(
    		'model' => $model,
    	));
    }
    
    public function actionUpdate($id) {
    	$model = $this->loadModel($id);
    	
    	// Uncomment the following line if AJAX validation is needed
    	// $this->performAjaxValidation($model);
    	
    	if (isset($_POST['UniversalRazdel'])) {
    		$model->attributes = $_POST['UniversalRazdel'];
    		
    		if ($model->save()) {
    			$this->redirect(array('admin'));
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
    		throw new CHttpException(400, Yii::t('tires', 'This Page not found.'));
    }
    
    public function loadModel($id) {
    	$model = UniversalRazdel::model()->findByPk($id);
    	if ($model === null)
    		throw new CHttpException(404, Yii::t('universal', 'This page doesn\'t exist.'));
    	return $model;
    }
}