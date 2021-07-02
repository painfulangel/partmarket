<?php

class ItemsController extends UsedController
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
				'actions'=>array('index','view','viewAjax','listModels','listMod','listNodes'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('exportToPrices','export','create','createAjax','update','admin','delete','deleteAjax'),
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

	public function actionViewAjax($id)
	{
		$this->renderPartial('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new UsedItems;
		$images = new UsedImages();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedItems']))
		{
			//echo CVarDumper::dump($_FILES,10,true);
			//echo CVarDumper::dump($_POST,10,true);exit;
			$model->attributes = $_POST['UsedItems'];

			$model->images = CUploadedFile::getInstancesByName('UsedItems[images]');

			//echo CVarDumper::dump($model->images,10,true);exit;
			
			if($model->save())
			{
				if(isset($_POST['UsedItemSets']))
				{
					$sets = $_POST['UsedItemSets'];
					foreach($sets as $set)
					{
						$modelSet = new UsedItemSets();
						$modelSet->attributes = $set;
						$modelSet->item_id = $model->id;
						$modelSet->save();
					}
				}

				if(isset($_POST['UsedItemsUsage']))
				{
					$usage = $_POST['UsedItemsUsage'];
					if($usage['mod_id'])
					{
						foreach ($usage['mod_id'] as $us) {
							$modelUsage = new UsedItemsUsage();
							$modelUsage->mod_id = $us;
							$modelUsage->item_id = $model->id;
							if(!$modelUsage->save())
							{
								echo CVarDumper::dump($modelUsage->getErrors(),10,true);exit;
							}
						}
					}

				}

				/**
				 * После сохранения детали делаем запись в прайс
				 */
				$this->addToPrices($model);

				Yii::app()->user->setFlash('success', 'Деталь добавлена');
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Добавление детали аяксом
	 * @param $mid
	 * @throws CException
	 */
	public function actionCreateAjax($mid)
	{
		$model = new UsedItems;
		$images = new UsedImages();
		$modification = UsedMod::model()->findByPk($mid);

		parse_str(Yii::app()->request->queryString, $output);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedItems']))
		{
			//echo CVarDumper::dump($_FILES,10,true);
			//echo CVarDumper::dump($_POST,10,true);exit;
			$model->attributes = $_POST['UsedItems'];

			$model->images = CUploadedFile::getInstancesByName('UsedItems[images]');

			//echo CVarDumper::dump($model->images,10,true);exit;
			//echo CVarDumper::dump($model->attributes,10,true);exit;

			if($model->save())
			{
				if(isset($_POST['UsedItemSets']))
				{
					$sets = $_POST['UsedItemSets'];
					foreach($sets as $set)
					{
						$modelSet = new UsedItemSets();
						$modelSet->attributes = $set;
						$modelSet->item_id = $model->id;
						$modelSet->save();
					}
				}

				if(isset($_POST['UsedItemsUsage']))
				{
					$usage = $_POST['UsedItemsUsage'];
					if($usage['mod_id'])
					{
						foreach ($usage['mod_id'] as $us) {
							$modelUsage = new UsedItemsUsage();
							$modelUsage->mod_id = $us;
							$modelUsage->item_id = $model->id;
							if(!$modelUsage->save())
							{
								echo CVarDumper::dump($modelUsage->getErrors(),10,true);exit;
							}
						}
					}

				}

				/**
				 * После сохранения детали делаем запись в прайс
				 */
				$this->addToPrices($model);

				Yii::app()->user->setFlash('success', 'Деталь добавлена');
				//$this->redirect(array('/used/modification/adminView', 'id'=>$mid));
				echo '<script>location.reload();</script>';
			}
			else
			{
				//echo CVarDumper::dump($model->getErrors(),10,true);exit;
				$this->renderPartial('createAjax',array(
					'model'=>$model,
					'mod'=>$mid,
					'modification'=>$modification,
					'node'=>$output['node'],
					'unit'=>$output['unit'],
				), false, true);
				Yii::app()->end();
			}
		}

		///$params = explode('&', Yii::app()->request->queryString);

		//echo CVarDumper::dump($output['node'],10,true);

		$this->renderPartial('createAjax',array(
			'model'=>$model,
			'mod'=>$mid,
			'modification'=>$modification,
			'node'=>$output['node'],
			'unit'=>$output['unit'],
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
		
		$images = $model->usedImages;

		$modification = UsedMod::model()->findByPk($model->mod_id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsedItems']))
		{
			$model->attributes = $_POST['UsedItems'];

			$model->images = CUploadedFile::getInstancesByName('UsedItems[images]');
			
			if($model->save())
			{
				if(isset($_POST['UsedItemSets']))
				{
					$sets = $_POST['UsedItemSets'];
					foreach($sets as $set)
					{
						$modelSet = new UsedItemSets();
						$modelSet->attributes = $set;
						$modelSet->item_id = $model->id;
						$modelSet->save();
					}
				}

				if(isset($_POST['UsedItemsUsage']))
				{
					$usages = UsedItemsUsage::model()->findAllByAttributes(array('item_id'=>$id));
					if($usages)
					{
						foreach ($usages as $u) {
							$u->delete();
						}
					}

					$usage = $_POST['UsedItemsUsage'];
					if($usage['mod_id'])
					{
						foreach ($usage['mod_id'] as $us) {
							$modelUsage = new UsedItemsUsage();
							$modelUsage->mod_id = $us;
							$modelUsage->item_id = $model->id;
							if(!$modelUsage->save())
							{
								echo CVarDumper::dump($modelUsage->getErrors(),10,true);exit;
							}
						}
					}

				}
				else
				{
					$usages = UsedItemsUsage::model()->findAllByAttributes(array('item_id'=>$model->id));
					if($usages)
					{
						foreach ($usages as $u) {
							$u->delete();
						}
					}
				}

				/**
				 * Редактируем запись в прайсе
				 */
				$this->editToPrices($model);

				Yii::app()->user->setFlash('success', 'Деталь отредактирована');

				$this->redirect(array('admin'));
			}

		}

		$this->render('update',array(
			'model'=>$model,
			'mod'=>$modification->id,
		));
	}


	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$this->deleteToPrice($model);//удалить запись из прайса
		if($model->delete())
		{
			Yii::app()->user->setFlash('success', 'Данные успешно удалены.');
		}
		else
		{
			Yii::app()->user->setFlash('error', 'Ошибка! Не удалось удалить деталь.');
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Удаление детали аяксом
	 * @param $id
	 * @throws CDbException
	 * @throws CHttpException
	 */
	public function actionDeleteAjax($id)
	{
		$model = $this->loadModel($id);

		if($model)
		{
			$this->deleteToPrice($model);//удалить запись из прайса
			if($model->delete())
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		else
		{
			echo 0;
		}
		Yii::app()->end();
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('UsedItems');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new UsedItems('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UsedItems']))
			$model->attributes=$_GET['UsedItems'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionListModels()
	{
		$post = Yii::app()->request->getPost('UsedItems');
		$models = UsedModels::model()->findAllByAttributes(array('brand_id'=>$post['brand_id']));
		$data = CHtml::listData($models,'id','name');
		echo CHtml::tag('option', array('value'=>null),'Выберите модель',true);
		foreach($data as $value => $name)
		{
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
		Yii::app()->end();
	}
	
	public function actionListMod()
	{
		$post = Yii::app()->request->getPost('UsedItems');
		$models = UsedMod::model()->findAllByAttributes(array('model_id'=>$post['model_id']));
		$data = CHtml::listData($models,'id','name');
		echo CHtml::tag('option', array('value'=>null),'Выберите модификацию',true);
		foreach($data as $value => $name)
		{
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
		Yii::app()->end();
	}
	
	public function actionListNodes()
	{
		$post = Yii::app()->request->getPost('UsedItems');
		$models = UsedUnits::model()->findAllByAttributes(array('node_id'=>$post['node_id']));
		$data = CHtml::listData($models,'id','name');
		echo CHtml::tag('option', array('value'=>null),'Выберите агрегат',true);
		foreach($data as $value => $name)
		{
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
		Yii::app()->end();
	}

	/**
	 * Экспорт прайслиста в файл
	 */
	public function actionExport()
	{
		$model = new UsedItems();
		header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
		header('Last-Modified: ' . gmdate("D,d M YH:i:s") . ' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=export_used_items_cp1251.csv');

		echo $model->export();
	}

	/**
	 * Первичный импорт данных в таблицу prices_data
	 * Перезаписывает все данные в прайсе
	 */
	public function actionExportToPrices()
	{
		$errors = array();
		$price = Prices::model()->findByAttributes(array('name'=>UsedItems::PRICE_NAME));

		if(!$price)
		{
			Yii::app()->user->setFlash('error', 'Ошибка! Шаблон прайс-листа был удален. Загрузите шаблон прайс-листа. Для этого воспользуйтесь интерфейсом управления прайс-листов. Просто выберите файл-шаблон на вашем компьютере. Файл должен называться default_part_use.xls. Шаблон файл был вам предоставлен ранее. Если у вас отстутствует такой файл, обратитесь к разработчику.');
			$this->redirect('/prices/admin/admin');
		}
		else
		{
			$items = UsedItems::model()->findAll();

			/**
			 * Перед импортом, удалить все записи из прайса
			 */
			PricesData::model()->deleteAllByAttributes(array('price_id'=>$price->id));

			foreach ($items as $item)
			{
				$priceData = new PricesData();
				$priceData->price_id = $price->id;
				$priceData->name = $item->name;
				$priceData->brand = $item->brandItem->name;
				$priceData->price = $item->price;
				$priceData->quantum = $item->availability;
				$priceData->article = ($item->original_num)?$item->original_num:0;
				$priceData->original_article = ($item->original_num)?$item->original_num:0;
				$priceData->extra_field1 = $item->vendor_code;
				$priceData->delivery = $item->delivery_time;
				if(!$priceData->save())
				{
					$errors[] = $priceData->getErrors();
				}
			}

			if($errors)
			{
				echo CVarDumper::dump($errors,10,true);exit;
			}
			else
			{
				Yii::app()->user->setFlash('success', 'Данные успешно импортированы');
				$this->redirect(Yii::app()->request->urlReferrer);
			}
		}



	}


	/**
	 * Добавление позиции в прайс
	 * @param $item
	 * @return bool
	 */
	private function addToPrices($item)
	{
		/**
		 * Проверить есть прайс или нет,
		 * если нет вернуть false
		 */
		$price = Prices::model()->findByAttributes(array('name' => UsedItems::PRICE_NAME));

		if (!$price) {
			return false;
		}

		/**
		 * Добавляем позицию в прайс
		 */
		$priceData = new PricesData();
		$priceData->price_id = $price->id;
		$priceData->name = $item->name;
		$priceData->brand = $item->brandItem->name;
		$priceData->price = $item->price;
		$priceData->quantum = $item->availability;
		$priceData->article = ($item->original_num)?$item->original_num:0;
		$priceData->original_article = ($item->original_num)?$item->original_num:0;
		$priceData->extra_field1 = UsedItems::GEN_PREFIX_VENDOR_CODE.$item->id;
		$priceData->delivery = $item->delivery_time;
		if (!$priceData->save())
		{
			return false;
		}

		return true;

	}

	/**
	 * Изменение позиции в прайсе
	 * @param $item
	 * @return bool
	 */
	private function editToPrices($item)
	{
		/**
		 * Проверить есть прайс или нет,
		 * если нет вернуть false
		 */
		$price = Prices::model()->findByAttributes(array('name' => UsedItems::PRICE_NAME));

		if (!$price) {
			return false;
		}

		/**
		 * Изменяем позицию в прайсе
		 */
		$priceData = PricesData::model()->findByAttributes(array('price_id'=>$price->id, 'article'=>$item->vendor_code));
		$priceData->name = $item->name;
		$priceData->brand = $item->brandItem->name;
		$priceData->price = $item->price;
		$priceData->quantum = $item->availability;
		$priceData->article = ($item->original_num)?$item->original_num:0;
		$priceData->original_article = ($item->original_num)?$item->original_num:0;
		$priceData->extra_field1 = $item->vendor_code;
		$priceData->delivery = $item->delivery_time;
		if (!$priceData->save())
		{
			return false;
		}

		return true;

	}

	/**
	 * Удаление позиции из прайса
	 * @param $item
	 * @return bool
	 * @throws CDbException
	 */
	private function deleteToPrice($item)
	{
		/**
		 * Проверить есть прайс или нет,
		 * если нет вернуть false
		 */
		$price = Prices::model()->findByAttributes(array('name' => UsedItems::PRICE_NAME));

		if (!$price) {
			return false;
		}

		/**
		 * Изменяем позицию в прайсе
		 */
		$priceData = PricesData::model()->findByAttributes(array('price_id'=>$price->id, 'extra_field1'=>$item->vendor_code));

		if (!$priceData->delete())
		{
			return false;
		}

		return true;

	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UsedItems the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UsedItems::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UsedItems $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='used-items-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
