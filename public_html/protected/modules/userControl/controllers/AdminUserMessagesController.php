<?php
class AdminUserMessagesController extends Controller {
	public $layout = '//layouts/admin_column2';
    public $admin_header = array();
    
    protected function beforeAction($action)
    {
    	$this->admin_header = array(
		    array(
		        'name' => Yii::t('admin_layout', 'Clients'),
		        'url' => array('/userControl/adminUserProfile/admin'),
		        'active' => false,
		    ),
		    array(
		        'name' => Yii::t('admin_layout', 'Create Client'),
		        'url' => array('/userControl/adminUserProfile/createNewUser'),
		        'active' => false,
		    ),
		    array(
		        'name' => Yii::t('admin_layout', 'Rights to users'),
		        'url' => array('/auth/assignment/index'),
		        'active' => false,
		    ),
		    array(
		        'name' => Yii::t('messages', 'Register of messages'),
		        'url' => array('/userControl/adminUserMessages/admin'),
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
	
	public function accessRules() {
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array('admin', 'messages', 'answer', 'toggle', 'close', 'open'),
				'roles' => array('managerNotDiscount', 'manager', 'mainManager', 'admin'),
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
			$model->save();
	
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		} else
			throw new CHttpException(400, Yii::t('message', 'This page doesn\'t exist.'));
	}
	
	public function actionAdmin() {
		$model = new UserMessageDialog('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['UserMessageDialog']))
			$model->attributes = $_GET['UserMessageDialog'];
		
		$this->render('admin', array(
			'model' => $model,
		));
	}
	
	public function actionMessages($id_dialog) {
		$dialog = $this->loadModel($id_dialog);
		
		//Admin read messages
		UserMessage::model()->updateAll(array('readed_admin' => 1), 'user_dialog_id = '.$id_dialog.' AND user_id = '.$dialog->user_id);
		
		$model = new UserMessage('search');
		$model->unsetAttributes();  // clear any default values
		$model->user_dialog_id = $id_dialog;
		
		$this->render('messages', array(
			'dialog' => $dialog,
			'model' => $model,
		));
	}
	
	public function actionAnswer($id_dialog) {
		$dialog = $this->loadModel($id_dialog);
		
		$last = UserMessage::model()->find(array('order' => 'id DESC'));
		
		$model = new UserMessage();
		$model->parent_id = $last->primaryKey;
		$model->user_dialog_id = $id_dialog;
		
		if (isset($_POST['UserMessage'])) {
			$model->attributes = $_POST['UserMessage'];
			if ($model->validate()) {
				if ($model->parent_id) {
					$parent = UserMessage::model()->findByPk($model->parent_id);
					if ($parent !== null)
						$model->appendTo($parent);
				}
				
				//if (!$model->primaryKey) $model->saveNode();
				$this->redirect(array('messages', 'id_dialog' => $id_dialog));
			}
		}
		
		$this->render('answer', array(
			'dialog' => $dialog,
			'model' => $model,
		));
	}
	
	public function actionClose($id_dialog) {
		$dialog = $this->loadModel($id_dialog);
		$dialog->closed = 1;
		if ($dialog->save()) {
			$this->redirect(array('messages', 'id_dialog' => $id_dialog));
		}
	}
    
	public function actionOpen($id_dialog) {
		$dialog = $this->loadModel($id_dialog);
		$dialog->closed = 0;
		if ($dialog->save()) {
			$this->redirect(array('messages', 'id_dialog' => $id_dialog));
		}
	}
	
    public function loadModel($id) {
    	$model = UserMessageDialog::model()->findByPk($id);
    	if ($model === null)
    		throw new CHttpException(404, Yii::t('messages', 'This page doesn\'t exist.'));
    	return $model;
    }
}