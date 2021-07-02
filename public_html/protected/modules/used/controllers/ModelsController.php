<?php

class ModelsController extends UsedController
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
				'actions'=>array('create','createMain','update','admin','adminModels','delete'),
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
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new UsedModels;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedModels']))
		{
			$model->attributes = $_POST['UsedModels'];
			//echo CVarDumper::dump($_POST,10,true);
			//echo CVarDumper::dump($model->attributes,10,true);exit;
			if($model->save())
			{
				$this->redirect(array('admin'));
			}

		}

		$this->render('create',array(
			'model'=>$model,
		));
	}


	public function actionCreateMain()
	{
		$model = new UsedModels;

		$brand_id = Yii::app()->request->getParam('brand_id');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedModels']))
		{
			$model->attributes = $_POST['UsedModels'];
			if($model->save())
			{
				$this->redirect(array('adminModels', 'UsedModels[brand_id]'=>$model->brand_id));
			}

		}

		$this->renderPartial('createMain',array(
			'model'=>$model,
			'brand_id'=>$brand_id
		), false, true);
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		
		//$oldImage = $model->image;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedModels']))
		{

			$model->attributes=$_POST['UsedModels'];
			
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
		$dataProvider=new CActiveDataProvider('UsedModels');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new UsedModels('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UsedModels']))
		{
			$model->attributes = $_GET['UsedModels'];
		}


		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionAdminModels()
	{
		$model = new UsedModels('search');
		$model->unsetAttributes();  // clear any default values
		$brand = array();
		if(isset($_GET['UsedModels']))
		{
			$model->attributes = $_GET['UsedModels'];
			$brand = UsedBrands::model()->findByPk($model->brand_id);
		}


		$this->render('adminModels',array(
			'model'=>$model,
			'brand'=>$brand,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UsedModels the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UsedModels::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UsedModels $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='used-models-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
