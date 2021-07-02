<?php
class AdminGroupsController extends Controller {
	/**
	 *
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 *      using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/admin_column2';

	public function beforeAction($action) {
		$this->admin_header = array (
			array (
				'name' => Yii::t('admin_layout', 'Price politics'),
				'url' => array ('/pricegroups/adminGroups/admin'),
				'active' => true 
			),
			array (
				'name' => Yii::t('admin_layout', 'Payment system'),
				'url' => array ('/webPayments/adminWebPaymentsSystem/admin'),
				'active' => false 
			),
			array (
				'name' => Yii::t('admin_layout', 'Currency'),
				'url' => array ('/currencies/admin/admin'),
				'active' => false 
			),
			array (
				'name' => Yii::t('admin_layout', 'Statistics'),
				'url' => array ('/statistics/admin/admin'),
				'active' => false
			),
		);

		if (!defined('TURNON_CITIES') || (TURNON_CITIES === true)) {
			$this->admin_header[] = array (
				'name' => Yii::t('cities', 'Cities'),
				'url' => array ('/cities/admin/admin'),
				'active' => false
			);
		}

		return parent::beforeAction($action);
	}
	
	/**
	 *
	 * @return array action filters
	 */
	public function filters() {
		return array (
				'accessControl'  // perform access control for CRUD operations
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * 
	 * @return array access control rules
	 */
	public function accessRules() {
		return array (
				array (
						'allow', // allow authenticated user to perform 'create' and 'update' actions
						'actions' => array (
								'create',
								'update',
								'admin',
								'delete' 
						),
						'roles' => array (
								'mainManager',
								'admin' 
						) 
				),
				array (
						'deny', // deny all users
						'users' => array (
								'*' 
						) 
				) 
		);
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate() {
		$model = new PricesRulesGroups ();
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if (isset($_POST ['PricesRulesGroups'])) {
			$model->attributes = $_POST ['PricesRulesGroups'];
			if ($model->save ())
				$this->redirect(array (
						'admin' 
				));
		}
		
		$this->render('create', array (
				'model' => $model 
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @param integer $id
	 *        	the ID of the model to be updated
	 */
	public function actionUpdate($id) {
		$model = $this->loadModel($id);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if (isset($_POST ['PricesRulesGroups'])) {
			$model->attributes = $_POST ['PricesRulesGroups'];
			if ($model->save ())
				$this->redirect(array (
						'admin',
						'PricesRulesGroups_page' => (isset($_GET ['PricesRulesGroups_page']) ? $_GET ['PricesRulesGroups_page'] : '') 
				));
		}
		
		$this->render('update', array (
				'model' => $model 
		));
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * 
	 * @param integer $id
	 *        	the ID of the model to be deleted
	 */
	public function actionDelete($id) {
		if (Yii::app ()->request->isPostRequest) {
			// we only allow deletion via POST request
			$this->loadModel($id)->delete ();
			
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (! isset($_GET ['ajax']))
				$this->redirect(isset($_POST ['returnUrl']) ? $_POST ['returnUrl'] : array (
						'admin' 
				));
		} else
			throw new CHttpException(400, Yii::t('pricegroups', 'This page doesn\'t exist.'));
	}
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin() {
		$model = new PricesRulesGroups('search');
		$model->unsetAttributes (); // clear any default values
		if (isset($_GET ['PricesRulesGroups']))
			$model->attributes = $_GET ['PricesRulesGroups'];
		
		$this->render('admin', array (
				'model' => $model 
		));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * 
	 * @param
	 *        	integer the ID of the model to be loaded
	 */
	public function loadModel($id) {
		$model = PricesRulesGroups::model ()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('pricegroups', 'This page doesn\'t exist.'));
		return $model;
	}
	
	/**
	 * Performs the AJAX validation.
	 * 
	 * @param
	 *        	CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if (isset($_POST ['ajax']) && $_POST ['ajax'] === 'prices-rules-groups-form') {
			echo CActiveForm::validate($model);
			Yii::app ()->end ();
		}
	}
}