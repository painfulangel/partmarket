<?php

class ModificationController extends UsedController
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
				'actions'=>array('index','view','listModels'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','createMain','update','admin','adminMod','delete', 'adminView','applicat'),
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

	public function actionAdminView($id)
	{
		$this->render('viewAdmin',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new UsedMod;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedMod']))
		{
			$model->attributes=$_POST['UsedMod'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionCreateMain()
	{
		$model = new UsedMod;

		$model_id = Yii::app()->request->getParam('model_id');

		$usedModel = UsedModels::model()->findByPk($model_id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedMod']))
		{
			$model->attributes=$_POST['UsedMod'];
			if($model->save())
				$this->redirect(array('adminMod', 'UsedMod[model_id]'=>$model->model_id));
		}

		$this->renderPartial('createMain',array(
			'model'=>$model,
			'usedModel'=>$usedModel,
		), false, true);
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

		if(isset($_POST['UsedMod']))
		{
			$model->attributes=$_POST['UsedMod'];
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
		$dataProvider=new CActiveDataProvider('UsedMod');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new UsedMod('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UsedMod']))
		{
			$model->attributes = $_GET['UsedMod'];
		}


		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionAdminMod()
	{
		$model = new UsedMod('search');
		$model->unsetAttributes();  // clear any default values
		$models = array();
		if(isset($_GET['UsedMod']))
		{
			$model->attributes = $_GET['UsedMod'];
			$models = UsedModels::model()->findByPk($model->model_id);
		}


		$this->render('adminMod',array(
			'model'=>$model,
			'models'=>$models
		));
	}

	public function actionListModels()
	{
		$post = Yii::app()->request->getPost('UsedMod');
		$models = UsedModels::model()->findAllByAttributes(array('brand_id'=>$post['brand_id']));
		$data = CHtml::listData($models,'id','name');
		echo CHtml::tag('option', array('value'=>null),'Выберите модель',true);
		foreach($data as $value => $name)
		{
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
		Yii::app()->end();
	}

	public function actionApplicat($brand_id)
	{
		//$models = UsedMod::model()->findAllByAttributes(array('brand_id'=>$brand_id));
		$sql = "select * from `used_mod` WHERE `brand_id`={$brand_id} ORDER BY name";
		$models = UsedMod::model()->findAllBySql($sql);
		echo $this->renderPartial('_additional_applicat', array('models'=>$models));
		//echo CVarDumper::dump($models,10,true);exit;
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UsedMod the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = UsedMod::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UsedMod $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='used-mod-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
