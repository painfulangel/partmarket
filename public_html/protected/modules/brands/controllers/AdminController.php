<?php
class AdminController extends Controller {
	public $layout = '//layouts/admin_column2';
	public $admin_header = array();
	
	protected function beforeAction($action)
	{
		$this->admin_header = array(
			array(
				'name' => Yii::t('prices', 'Editing warehouses'),
				'url' => array('/prices/adminStores/admin'),
				'active' => false,
			),
			array(
				'name' => Yii::t('prices', 'Prices'),
				'url' => array('/prices/admin/admin'),
				'active' => false,
			),
			array(
				'name' => Yii::t('crosses', 'Cross-tables'),
				'url' => array('/crosses/admin/admin'),
				'active' => false,
			),
			array(
				'name' => Yii::t('admin_layout', 'Suppliers'),
				'url' => array('/parsersApi/admin/admin'),
				'active' => false,
			),
			array(
				'name' => Yii::t('shop_cart', 'Orders to suppliers'),
				'url' => array('/shop_cart/adminItems/supplierOrder'),
				'active' => false,
			),
			array(
				'name' =>Yii::t('prices', 'Search meta-tags'),
				'url' => array('/prices/adminMeta/admin'),
				'active' => false,
			),
			array(
				'name' =>Yii::t('brands', 'Brands'),
				'url' => array('/brands/admin/admin'),
				'active' => true,
			),
		);

		$this->admin_subheader = array(
		    array(
				'name' =>Yii::t('brands', 'Brands'),
				'url' => array('/brands/admin/admin'),
				'active' => true,
		    ),
		    array(
		        'name' => Yii::t('brands', 'New brands'),
		        'url' => array('/brands/adminnew/admin'),
		        'active' => false,
		    ),
		);

    	return true;
	}

	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
    public function actions() {
        return array(
            'toggle' => 'ext.jtogglecolumn.ToggleAction',
            'switch' => 'ext.jtogglecolumn.SwitchAction', // only if you need it
            'qtoggle' => 'ext.jtogglecolumn.QtoggleAction', // only if you need it
        );
    }

	public function accessRules() {
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('admin', 'create', 'update', 'delete', 'toggle'),
				'roles' => array('mainManager', 'admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionAdmin() {
		$model = new Brands('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Brands']))
			$model->attributes = $_GET['Brands'];
	
		$this->render('admin', array('model' => $model,));
	}

	public function actionCreate() {
		$model = new Brands('create');
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
	
		if (isset($_POST['Brands'])) {
			$model->attributes = $_POST['Brands'];
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
	
		if (isset($_POST['Brands'])) {
			$model->attributes = $_POST['Brands'];
			//echo '<pre>'; print_r($model->attributes); print_r($_POST['Brands']); echo '</pre>'; exit;
			
			if ($model->save())
				$this->redirect(array('admin'));
		}
	
		$this->render('update', array(
				'model' => $model,
		));
	}
	
	public function actionDelete($id) {
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
	
			$model = $this->loadModel($id);
	
			$model->delete();
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		} else
			throw new CHttpException(400, Yii::t('prices', 'This page doesn\'t exist.'));
	}
	
	public function loadModel($id) {
		$model = Brands::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
		return $model;
	}
}
?>