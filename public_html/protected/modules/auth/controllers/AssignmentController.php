<?php
/**
 * AssignmentController class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2012-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package auth.controllers
 */

/**
 * Controller for assignment related actions.
 */
class AssignmentController extends AuthController {
	public $admin_header = array();
	
	protected function beforeAction($action)
    {
		$this->admin_header = array(
			array(
				'name' => Yii::t('admin_layout', 'Clients'),
				'url' => array(
						'/userControl/adminUserProfile/admin' 
				),
				'active' => false 
			),
			array(
				'name' => Yii::t('admin_layout', 'Create Client'),
				'url' => array(
						'/userControl/adminUserProfile/createNewUser' 
				),
				'active' => false 
			),
			array(
				'name' => Yii::t('admin_layout', 'Rights to users'),
				'url' => array(
						'/auth/assignment/index' 
				),
				'active' => true 
			),
			array(
				'name' => Yii::t('messages', 'Register of messages'),
				'url' => array(
						'/userControl/adminUserMessages/admin' 
				),
				'active' => false 
			) 
		);

        return true;
	}
	
	/**
	 * Displays the a list of all the assignments.
	 */
	public function actionIndex() {
		$model = new $this->module->userClass();
		$model->scenario = 'search';
		$model->unsetAttributes(); // clear any default values
		if(isset($_GET [$this->module->userClass]))
			$model->attributes = $_GET [$this->module->userClass];
		$criteria = new CDbCriteria();
		
		$criteria->compare('uid', $model->uid);
		$criteria->compare('email', $model->email, true);
		$criteria->order = 'id DESC';
		
		$dataProvider = new CActiveDataProvider($this->module->userClass, array(
			'criteria' => $criteria 
		));
		
		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'model' => $model 
		));
	}
	
	/**
	 * Displays the assignments for the user with the given id.
	 * 
	 * @param string $id
	 *        	the user id.
	 */
	public function actionView($id) {
		$formModel = new AddAuthItemForm();
		
		/* @var $am CAuthManager|AuthBehavior */
		$am = Yii::app()->getAuthManager();
		
		if(isset($_POST ['AddAuthItemForm'])) {
			$formModel->attributes = $_POST ['AddAuthItemForm'];
			if($formModel->validate()) {
				if(! $am->isAssigned($formModel->items, $id)) {
					$am->assign($formModel->items, $id);
					if($am instanceof CPhpAuthManager) {
						$am->save();
					}
					
					if($am instanceof ICachedAuthManager) {
						$am->flushAccess($formModel->items, $id);
					}
				}
			}
		}
		
		$model = CActiveRecord::model($this->module->userClass)->findByAttributes(array(
				'uid' => $id 
		));
		
		$assignments = $am->getAuthAssignments($id);
		$authItems = $am->getItemsPermissions(array_keys($assignments));
		$authItemDp = new AuthItemDataProvider();
		$authItemDp->setAuthItems($authItems);
		
		$assignmentOptions = $this->getAssignmentOptions($id);
		if(! empty($assignmentOptions)) {
			$assignmentOptions = array_merge(array(
					'' => Yii::t('auth_main', 'Select item') . ' ...' 
			), $assignmentOptions);
		}
		
		$this->render('view', array(
				'model' => $model,
				'authItemDp' => $authItemDp,
				'formModel' => $formModel,
				'assignmentOptions' => $assignmentOptions 
		));
	}
	
	/**
	 * Revokes an assignment from the given user.
	 * 
	 * @throws CHttpException if the request is invalid.
	 */
	public function actionRevoke() {
		if(isset($_GET ['itemName'], $_GET ['userId'])) {
			$itemName = $_GET ['itemName'];
			$userId = $_GET ['userId'];
			
			/* @var $am CAuthManager|AuthBehavior */
			$am = Yii::app()->getAuthManager();
			
			if($am->isAssigned($itemName, $userId)) {
				$am->revoke($itemName, $userId);
				if($am instanceof CPhpAuthManager) {
					$am->save();
				}
				
				if($am instanceof ICachedAuthManager) {
					$am->flushAccess($itemName, $userId);
				}
			}
			
			if(! isset($_POST ['ajax'])) {
				$this->redirect(array(
						'view',
						'id' => $userId 
				));
			}
		} else {
			throw new CHttpException(400, Yii::t('auth_main', 'Invalid request.'));
		}
	}
	
	/**
	 * Returns a list of possible assignments for the user with the given id.
	 * 
	 * @param string $userId
	 *        	the user id.
	 * @return array the assignment options.
	 */
	protected function getAssignmentOptions($userId) {
		$options = array();
		
		/* @var $am CAuthManager|AuthBehavior */
		$am = Yii::app()->authManager;
		
		$assignments = $am->getAuthAssignments($userId);
		$assignedItems = array_keys($assignments);
		
		/* @var $authItems CAuthItem[] */
		$authItems = $am->getAuthItems();
		
		foreach($authItems as $itemName => $item) {
			if(! in_array($itemName, $assignedItems)) {
				if($item->type != 2)
					continue;
				$options [$this->capitalize($this->getItemTypeText($item->type, true))] [$itemName] = $item->description;
			}
		}
		return $options;
	}
}
