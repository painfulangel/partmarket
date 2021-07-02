<?php
class AdminTiresPropertyController extends TiresController {
	public $layout = '//layouts/admin_column2';
	
	public function __construct($id, $module = null) {
		parent::__construct($id, $module);
		
	}
	
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	public function actionToggle($id, $attribute) {
		if (Yii::app()->request->isPostRequest) {
			if ($attribute == 'closed') {
				$model = TiresProperty::model()->findByPk($id);
			} else {
				$model = TiresPropertyValues::model()->findByPk($id);
			}
			
			$model->$attribute = ($model->$attribute == 0) ? 1 : 0;
			$model->save(false);
			
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		} else
			throw new CHttpException(400, Yii::t('tires', 'This page doesn\'t exist.'));
	}
	
	public function actionOrder($pk, $name, $value, $move) {
		$model = TiresPropertyValues::model()->findByPk($pk);
		$table = $model->tableName();
		if ($move === 'up') {
			$op = '<=';
			$inOrder = 'DESC';
		} else if ($move === 'down') {
			$op = '>=';
			$inOrder = 'ASC';
		}
		
		$sql = "SELECT {$table}.{$name} FROM $table WHERE id_property = ".$model->id_property." AND $table.$name $op " . $model->{$name} . " AND $table.id!=$pk ORDER BY $table.$name $inOrder LIMIT 1";
		
		$order = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = "SELECT {$table}.id FROM $table WHERE id_property = ".$model->id_property." AND $table.$name $op " . $model->{$name} . " AND $table.id!=$pk ORDER BY $table.$name $inOrder LIMIT 1";
		
		$id_to = Yii::app()->db->createCommand($sql)->queryScalar();
		
		if (empty($id_to))
			return;
		
		$highestOrder = Yii::app()->db->createCommand("SELECT {$table}.{$name} FROM {$table} WHERE id_property = ".$model->id_property." ORDER BY {$table}.{$name} DESC LIMIT 1")->queryScalar();
		
		$model_to = TiresPropertyValues::model()->findByPk($id_to);
		$model_to->{$name} = $model->{$name};
		$model_to->save(false);
		$model->{$name} = $order;
		$model->save(false);
	}
	
	public function accessRules() {
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array('admin', 'property', 'toggle', 'order', 'create', 'update', 'delete'),
				'roles' => array('mainManager', 'admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}
	
	public function actionAdmin() {
		$model = new TiresProperty('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Tires']))
			$model->attributes = $_GET['Tires'];
	
		$this->render('admin', array(
				'model' => $model,
		));
	}
	
	public function actionProperty($id) {
		//Show property values with this id
		$model = $this->loadModel($id);
		
		$model2 = TiresPropertyValues::model();
		$model2->id_property = $model->primaryKey;
		
		$this->render('property', array('model' => $model, 'model2' => $model2));
	}
	
	public function actionCreate($id_property) {
		$model = new TiresPropertyValues('create');
		 
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		 
		if (isset($_POST['TiresPropertyValues'])) {
			$model->attributes = $_POST['TiresPropertyValues'];
			if ($model->save())
				$this->redirect(array('property', 'id' => $id_property));
		}
		
		$model->id_property = $id_property;
		
		$this->render('create', array(
			'model' => $model,
		));
	}
	
	public function actionUpdate($id) {
		$model = $this->loadPropertyValueModel($id);
		 
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		 
		if (isset($_POST['TiresPropertyValues'])) {
			$model->attributes = $_POST['TiresPropertyValues'];
	
			if ($model->save()) {
				$this->redirect(array('property', 'id' => $model->id_property));
			}
		}
		 
		$this->render('update', array(
			'model' => $model,
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
			throw new CHttpException(400, Yii::t('tires', 'This Page not found.'));
	}
	
	public function loadModel($id) {
		$model = TiresProperty::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('tires', 'This page doesn\'t exist.'));
		return $model;
	}
	
	public function loadPropertyValueModel($id) {
		$model = TiresPropertyValues::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('tires', 'This page doesn\'t exist.'));
		return $model;
	}
}