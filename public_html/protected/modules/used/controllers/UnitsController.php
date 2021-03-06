<?php

class UnitsController extends UsedController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin_column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','update','admin','delete', 'createAjax', 'deplist'),
				'roles' => array('mainManager', 'admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id, $mod)
	{
		$items = UsedItems::model()->findAllByAttributes(array('mod_id'=>$mod, 'unit_id'=>$id));
		$this->renderPartial('view',array(
			'items'=>$items,
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new UsedUnits;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedUnits']))
		{
			$model->attributes=$_POST['UsedUnits'];
			if($model->save())
			{
				$this->redirect(array('admin'));
			}
				//$this->redirect(Yii::app()->request->urlReferrer);
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionCreateAjax()
	{
		$model=new UsedUnits;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedUnits']))
		{
			$model->attributes=$_POST['UsedUnits'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->renderPartial('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedUnits']))
		{
			$model->attributes=$_POST['UsedUnits'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('UsedUnits');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new UsedUnits('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UsedUnits']))
			$model->attributes=$_GET['UsedUnits'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionDeplist()
	{
		$node_id = CHtml::encode($_POST['node_id']);

		$data = UsedUnits::model()->findAll('node_id=:node_id', array(':node_id'=>$node_id));
		$data = CHtml::listData($data, 'id', 'name');
		echo CHtml::tag('option',
			array('value'=>''), '',true);
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',
				array('value'=>$value), CHtml::encode($name),true);
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UsedUnits the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UsedUnits::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UsedUnits $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='used-units-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
