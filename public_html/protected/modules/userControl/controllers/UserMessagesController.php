<?php
class UserMessagesController extends Controller
{
	public $layout = '//layouts/column2';
	
	public function filters()
	{
		return array(
			'accessControl',
		);
	}
	
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('index', 'messages', 'new', 'answer'),
				'users' => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}
	
	public function actionIndex()
	{
		$model = new UserMessageDialog('search');
		$model->user_id = Yii::app()->user->id;
		
		$this->render('index', array(
			'model' => $model,
		));
	}

	public function actionMessages($id_dialog) {
		$dialog = $this->loadModel($id_dialog);
	
		//User read messages
		UserMessage::model()->updateAll(array('readed_user' => 1), 'user_dialog_id = '.$id_dialog.' AND user_id != '.$dialog->user_id);
	
		$model = new UserMessage('search');
		$model->unsetAttributes();  // clear any default values
		$model->user_dialog_id = $id_dialog;
	
		$this->render('messages', array(
			'dialog' => $dialog,
			'model' => $model,
		));
	}
	
	public function actionNew()
	{
		$model = new UserMessage();
		
		if (isset($_POST['UserMessage'])) {
			$model->scenario = 'new';
			$model->attributes = $_POST['UserMessage'];
			
			if ($model->validate()) {
				if ($model->saveNode()) {
					$this->redirect(array('index'));
				}
			}
		}
		
		$this->render('new', array('model' => $model));
	}
	
	public function actionAnswer($id_dialog) {
		$dialog = $this->loadModel($id_dialog);
	
		if ($dialog->closed) throw new CHttpException(404, Yii::t('messages', 'This page doesn\'t exist.'));
		
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
	
    public function loadModel($id) {
    	$model = UserMessageDialog::model()->findByPk($id);
    	if (($model === null) || (!Yii::app()->user->checkAccess('admin') && ($model->user_id != Yii::app()->user->id))) {
    		throw new CHttpException(404, Yii::t('messages', 'This page doesn\'t exist.'));
    	}
    	return $model;
    }
}