<?php

class BrandsItemsController extends UsedController
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
				'actions'=>array('create','createAjax','update','admin','delete','list'),
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
		$model=new UsedBrandsItems;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedBrandsItems']))
		{
			$model->attributes=$_POST['UsedBrandsItems'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionCreateAjax()
	{
		$model = new UsedBrandsItems;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedBrandsItems']))
		{
			$model->attributes = $_POST['UsedBrandsItems'];
			if($model->save()){
				echo $model->id;
				Yii::app()->end();
			}
		}
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

		if(isset($_POST['UsedBrandsItems']))
		{
			$model->attributes=$_POST['UsedBrandsItems'];
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
	 * Возвращает список названий производителей запчастей
	 * формат array($brand->id=>$brand->name)
	 * @param $q
	 */
	public function actionList()
	{
		$term = Yii::app()->request->getQuery('q');
		$brands = UsedBrandsItems::model()->findAll('name LIKE :term', array(':term'=>'%'.$term.'%'));

		$lists = array();
		foreach($brands as $key => $brand)
		{
			$lists[$key]['id'] = $brand->id;
			$lists[$key]['text'] = $brand->name;
		}
		//echo CVarDumper::dump($lists,10,true);exit;
		$results['results']=$lists;
		echo json_encode($results);
		Yii::app()->end();
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('UsedBrandsItems');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new UsedBrandsItems('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UsedBrandsItems']))
			$model->attributes=$_GET['UsedBrandsItems'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UsedBrandsItems the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UsedBrandsItems::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UsedBrandsItems $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='used-brands-items-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
