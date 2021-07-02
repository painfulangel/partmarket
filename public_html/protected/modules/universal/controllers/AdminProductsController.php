<?php
class AdminProductsController extends UniversalController {
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
				'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'toggle', 'order', 'upload', 'download'),
				'roles' => array('mainManager', 'admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}
	
	public function actionAdmin() {
		$id = intval(Yii::app()->request->getQuery('id', 0));
		$razdel = $this->loadRazdelModel($id);
		
		$model = new UniversalProduct('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['UniversalProduct']))
			$model->attributes = $_GET['UniversalProduct'];
		
		$model->id_razdel = $id;
		
		$this->render('admin', array(
			'model' => $model,
			'razdel' => $razdel,
		));
	}
	
	public function actionCreate() {
		$id = intval(Yii::app()->request->getQuery('id', 0));
		$razdel = $this->loadRazdelModel($id);
		
		$model = new UniversalProduct('create');
		 
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		 
		if (isset($_POST['UniversalProduct'])) {
			$model->attributes = $_POST['UniversalProduct'];
			if ($model->save())
				$this->redirect(array('admin', 'id' => $id));
		}
		
		//Product characteristics
		$chars = UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$razdel->primaryKey, 'order' => '`order` ASC'));
		foreach ($chars as $char) {
			$name = 'char'.$char->primaryKey;
				
			$model->{$name} = '';
				
			$model->addAttributeLabel($name, $char->name);
		}
		//Product characteristics
		
		$this->render('create', array(
			'model' => $model,
			'razdel' => $razdel,
			'chars'  => $chars,
		));
	}
	
	public function actionUpdate($id) {
		$model = $this->loadModel($id);
		$razdel = $this->loadRazdelModel($model->id_razdel);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		 
		if (isset($_POST['UniversalProduct'])) {
			$model->attributes = $_POST['UniversalProduct'];
			
			if ($model->save()) {
				$this->redirect(array('admin', 'id' => $razdel->primaryKey));
			}
		}
		
		//Product characteristics
		$chars = UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$razdel->primaryKey, 'order' => '`order` ASC'));
		foreach ($chars as $char) {
			$name = 'char'.$char->primaryKey;
			
			$model->{$name} = $char->getValue($id);
			
			$model->addAttributeLabel($name, $char->name);
		}
		//Product characteristics
		
		$this->render('update', array(
			'model'  => $model,
			'razdel' => $razdel,
			'chars'  => $chars,
		));
	}
	
	public function actionUpload() {
		$id = intval(Yii::app()->request->getQuery('id', 0));
		
		$razdel = $this->loadRazdelModel($id);
		
		$model = new UniversalProductImport();
		$model->razdelId = $id;
		
		if (isset($_POST['UniversalProductImport'])) {
			$model->attributes = $_POST['UniversalProductImport'];
			if ($model->import())
				$this->redirect(array('admin', 'id' => $id));
		}
		
		$this->render('upload', array(
			'razdel' => $razdel,
			'model'  => $model,
		));
	}
	
	public function actionDownload() {
		$id = intval(Yii::app()->request->getQuery('id', 0));
		
		$razdel = $this->loadRazdelModel($id);
		
		header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
		header('Last-Modified: '.gmdate("D,d M YH:i:s").' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=export_'.$razdel->alias.'_cp1251.csv');
		
		echo $razdel->export();
	}

	public function actionDelete($id) {
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
	
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		} else
			throw new CHttpException(400, Yii::t('universal', 'This Page not found.'));
	}
	
	public function loadRazdelModel($id) {
		$model = UniversalRazdel::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('universal', 'This page doesn\'t exist.'));
		return $model;
	}
	
	public function loadModel($id) {
		$model = UniversalProduct::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('universal', 'This page doesn\'t exist.'));
		return $model;
	}
}