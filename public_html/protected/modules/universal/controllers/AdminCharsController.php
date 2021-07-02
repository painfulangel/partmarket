<?php
class AdminCharsController extends UniversalController {
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
	
	public function actions() {
		return array(
			'order' => array(
				'class' => 'ext.OrderColumn.OrderAction',
				'modelClass' => 'UniversalChars',
				'pkName' => 'id',
			),
		);
	}
	
	public function accessRules() {
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'toggle', 'order', 'copy'),
				'roles' => array('mainManager', 'admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}
	
	public function actionToggle($id, $attribute) {
		if (Yii::app()->request->isPostRequest) {
			$model = $this->loadCharModel($id);
			$model->$attribute = ($model->$attribute == 0) ? 1 : 0;
			$model->save(false);
	
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		} else
			throw new CHttpException(400, Yii::t('universal', 'This page doesn\'t exist.'));
	}
	
	public function actionAdmin() {
		$id = intval(Yii::app()->request->getQuery('id', 0));
		$razdel = $this->loadModel($id);
		
		$model = new UniversalChars('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['UniversalChars']))
			$model->attributes = $_GET['UniversalChars'];
	
		$model->id_razdel = $id;
		
		$this->render('admin', array(
			'model'  => $model,
			'razdel' => $razdel,
			'count'  => UniversalRazdel::model()->count()
		));
	}
	
	public function actionCreate() {
		$id = intval(Yii::app()->request->getQuery('id', 0));
		$razdel = $this->loadModel($id);
		
		$model = new UniversalChars('create');
		 
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		 
		if (isset($_POST['UniversalChars'])) {
			$model->attributes = $_POST['UniversalChars'];
			if ($model->save())
				$this->redirect(array('admin', 'id' => $id));
		}
		 
		$this->render('create', array(
			'model' => $model,
			'razdel' => $razdel,
		));
	}
	
	public function actionUpdate($id) {
		$model = $this->loadCharModel($id);
		$razdel = $this->loadModel($model->id_razdel);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		 
		if (isset($_POST['UniversalChars'])) {
			$model->attributes = $_POST['UniversalChars'];
	
			if ($model->save()) {
				$this->redirect(array('admin', 'id' => $razdel->primaryKey));
			}
		}
		 
		$this->render('update', array(
			'model' => $model,
			'razdel' => $razdel,
		));
	}
	
	public function actionDelete($id) {
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model = $this->loadCharModel($id);
			$id_razdel = $model->id_razdel;
			$model->delete();
	
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin', 'id' => $id_razdel));
		} else
			throw new CHttpException(400, Yii::t('tires', 'This Page not found.'));
	}
	
	public function actionCopy() {
		$id = intval(Yii::app()->request->getQuery('id', 0));
		$razdel = $this->loadModel($id);
		
		$model = new UniversalCharsCopy();
		$model->razdelId = $id;
		
		if (isset($_POST['UniversalCharsCopy'])) {
			$model->attributes = $_POST['UniversalCharsCopy'];
			if ($model->import())
				$this->redirect(array('admin', 'id' => $id));
		}
		
		$sections = array();
		$items = UniversalRazdel::model()->findAll(array('order' => 'name ASC'));
		foreach ($items as $item) {
			if ($item->primaryKey == $id) continue;
			
			$sections[$item->primaryKey] = $item->name;
		}
		
		$this->render('copy', array(
			'razdel'   => $razdel,
			'model'    => $model,
			'sections' => $sections,
		));
	}
	
    public function loadModel($id) {
    	$model = UniversalRazdel::model()->findByPk($id);
    	if ($model === null)
    		throw new CHttpException(404, Yii::t('universal', 'This page doesn\'t exist.'));
    	return $model;
    }
    
    public function loadCharModel($id) {
    	$model = UniversalChars::model()->findByPk($id);
    	if ($model === null)
    		throw new CHttpException(404, Yii::t('universal', 'This page doesn\'t exist.'));
    	return $model;
    }
}