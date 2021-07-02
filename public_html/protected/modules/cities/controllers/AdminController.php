<?php
	class AdminController extends Controller {
		public $layout = '//layouts/admin_column2';

		public function beforeAction($action) {
			if (defined('TURNON_CITIES') && (TURNON_CITIES === false)) {
				throw new CHttpException(404, Yii::t('tires', 'This page doesn\'t exist.'));
			}

			$this->admin_header = array (
				array (
					'name' => Yii::t('admin_layout', 'Price politics'),
					'url' => array ('/pricegroups/adminGroups/admin'),
					'active' => false 
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
				array (
					'name' => Yii::t('cities', 'Cities'),
					'url' => array ('/cities/admin/admin'),
					'active' => true
				),
			);

			return parent::beforeAction($action);
		}

		public function filters() {
			return array (
					'accessControl'  // perform access control for CRUD operations
			);
		}
		
		public function accessRules() {
			return array (
				array (
					'allow', // allow authenticated user to perform 'create' and 'update' actions
					'actions' => array (
							'create',
							'update',
							'admin',
							'delete', 'toggle'
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

	    public function actions() {
	        return array(
	            'toggle' => 'ext.jtogglecolumn.ToggleAction',
	        );
	    }
    
		public function actionAdmin() {
			$model = new Cities('search');
			$model->unsetAttributes (); // clear any default values
			if (isset($_GET ['Cities']))
				$model->attributes = $_GET ['Cities'];
			
			$this->render('admin', array (
				'model' => $model 
			));
		}

		public function actionCreate() {
			$model = new Cities();
			
			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);
			
			if (isset($_POST ['Cities'])) {
				$model->attributes = $_POST ['Cities'];
				if ($model->save ())
					$this->redirect(array('admin'));
			}
			
			$this->render('create', array(
				'model' => $model 
			));
		}
		
		public function actionUpdate($id) {
			$model = $this->loadModel($id);
			
			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);
			
			if (isset($_POST ['Cities'])) {
				$model->attributes = $_POST ['Cities'];
				if ($model->save ())
					$this->redirect(array('admin'));
			}
			
			$this->render('update', array (
					'model' => $model 
			));
		}
		
		public function actionDelete($id) {
			if (Yii::app ()->request->isPostRequest) {
				// we only allow deletion via POST request
				$this->loadModel($id)->delete ();
				
				// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
				if (! isset($_GET ['ajax']))
					$this->redirect(isset($_POST ['returnUrl']) ? $_POST ['returnUrl'] : array('admin'));
			} else
				throw new CHttpException(400, Yii::t('cities', 'This page doesn\'t exist.'));
		}

		public function loadModel($id) {
			$model = Cities::model ()->findByPk($id);
			if ($model === null)
				throw new CHttpException(404, Yii::t('cities', 'This page doesn\'t exist.'));
			return $model;
		}
	}
?>