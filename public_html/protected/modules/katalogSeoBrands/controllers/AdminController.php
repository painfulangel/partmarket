<?php
class AdminController extends BaseCatalogController
{
	public $layout = '//layouts/admin_column2';
	
	public function __construct($id, $module = null)
	{
		parent::__construct($id, $module);
		
	}
	
	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array('admin', 'brands', 'clearall', 'toggle'),
				'roles' => array('mainManager', 'admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}
	
    public function actionToggle($id, $attribute) {
        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModel($id);
            $model->$attribute = ($model->$attribute == 0) ? 1 : 0;
            $model->save(false);

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, Yii::t('katalogVavto', 'This Page not found.'));
    }

	public function actionAdmin() {
		$model = KatalogSeoBrandsSettings::model()->find();
		
		if (!is_object($model)) $model = new KatalogSeoBrandsSettings();
		
		if (isset($_POST['KatalogSeoBrandsSettings'])) {
			$model->attributes = $_POST['KatalogSeoBrandsSettings'];
			
			if ($model->save()) {
				KatalogSeoBrandsStores::model()->deleteAll();

				if (array_key_exists('stores', $_POST)) {
					$stores = array_map('intval', $_POST['stores']);
					foreach ($stores as $store) {
						$s = new KatalogSeoBrandsStores();
						$s->store_id = $store;
						$s->save();
					}
				}

				$this->redirect(array('admin'));
			}
		}

		$pricesrules = array();

		$data = PricesRulesGroups::model()->findAll();
		$count = count($data);
		for ($i = 0; $i < $count; $i ++) {
			$pricesrules[$data[$i]->primaryKey] = $data[$i]->name;
		}

		//Склады
		$stores = Stores::model()->findAll(array('order' => 'name'));

		//Активные склады
		$active_store = array();

		$s = KatalogSeoBrandsStores::model()->findAll();
		foreach ($s as $store) {
			$active_store[] = $store->store_id;
		}
		
		$this->render('admin', array(
			'model' => $model,
			'pricesrules' => $pricesrules,
			'stores' => $stores,
			'active_store' => $active_store,
		));
	}

	public function actionBrands() {
        $model = new KatalogSeoBrandsBrands('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['KatalogVavtoBrands']))
            $model->attributes = $_GET['KatalogVavtoBrands'];

        $this->render('brands', array(
            'model' => $model,
        ));
	}

	public function actionClearall() {
		KatalogSeoBrandsBrands::model()->deleteAll();
		KatalogSeoBrandsItems::model()->deleteAll();

		$prices = Prices::model()->findAll(array('condition' => 'processed = 1'));
		$count = count($prices);
		for ($i = 0; $i < $count; $i ++) {
			$prices[$i]->processed = 0;
			$prices[$i]->start = 0;
			$prices[$i]->save();
		}

		die(json_decode(array('success' => 1)));
	}

	public function loadModel($id) {
        $model = KatalogSeoBrandsBrands::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('katalogVavto', 'This Page not found.'));
        return $model;
    }
}
?>