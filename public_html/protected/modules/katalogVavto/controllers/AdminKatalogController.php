<?php
class AdminKatalogController extends BaseCatalogController{
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
				'actions' => array('admin'),
				'roles' => array('mainManager', 'admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}
	
	public function actionAdmin() {
		$model = KatalogSettings::model()->find();
		
		if (!is_object($model)) $model = new KatalogSettings();
		
		if (isset($_POST['KatalogSettings'])) {
			$model->attributes = $_POST['KatalogSettings'];
			
			if ($model->save())
				$this->redirect(array('admin'));
		}
		
		$this->render('admin', array(
			'model' => $model,
		));
	}
}
?>