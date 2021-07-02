<?php
class AdminnewController extends Controller {
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
				'active' => false,
		    ),
		    array(
		        'name' => Yii::t('brands', 'New brands'),
		        'url' => array('/brands/adminnew/admin'),
		        'active' => true,
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
				'actions' => array('admin', 'create', 'update', 'delete', 'toggle', 'check', 'addBrand', 'adminIncorrect', 'returnInNewBrands'),
				'roles' => array('mainManager', 'admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionAdmin() {
		$model = new BrandsNew('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['BrandsNew']))
			$model->attributes = $_GET['BrandsNew'];
	
		$this->render('admin', array('model' => $model,));
	}

	public function actionCheck() {
		$request = Yii::app()->request;
		$ids = $request->getPost('ids');

		if (is_array($ids) && ($count = count($ids))) {
			$bn = BrandsNew::model()->findAll('id IN('.implode(', ', $ids).')');
			$count = count($bn);

			for ($i = 0; $i < $count; $i ++) {
				$b = new BrandsIncorrect();
				$b->name = $bn[$i]->name;
				$b->date = time();
				if ($b->save()) {
					$bn[$i]->delete();
				}
			}
		}
	}

	public function actionAddBrand() {
		$request = Yii::app()->request;
		$ids = $request->getPost('ids');

		if (is_array($ids) && ($count = count($ids))) {
			$bn = BrandsNew::model()->findAll('id IN('.implode(', ', $ids).')');
			$count = count($bn);

			for ($i = 0; $i < $count; $i ++) {
				$b = new Brands();
				$b->name = $bn[$i]->name;
				if ($b->save()) {
					$bn[$i]->delete();
				}
			}
		}
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
		$model = BrandsNew::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
		return $model;
	}

	public function actionAdminIncorrect() {
		$model = new BrandsIncorrect('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['BrandsIncorrect']))
			$model->attributes = $_GET['BrandsIncorrect'];
	
		$this->render('adminIncorrect', array('model' => $model,));
	}

	public function actionReturnInNewBrands() {
		$request = Yii::app()->request;
		$ids = $request->getPost('ids');

		if (is_array($ids) && ($count = count($ids))) {
			$bn = BrandsIncorrect::model()->findAll('id IN('.implode(', ', $ids).')');
			$count = count($bn);

			for ($i = 0; $i < $count; $i ++) {
				$b = new BrandsNew();
				$b->name = $bn[$i]->name;
				
				if ($b->save()) {
					$bn[$i]->delete();
				}
			}
		}
	}
}
?>