<?php
class AdminUserProfileController extends Controller {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/admin_column2';
    public $admin_header = array();
    
    protected function beforeAction($action)
    {
    	$url = Yii::app()->urlManager->parseUrl(Yii::app()->request);
    	
    	$this->admin_header = array(
		    array(
		        'name' => Yii::t('admin_layout', 'Clients'),
		        'url' => array('/userControl/adminUserProfile/admin'),
		        'active' => strpos($url, '/adminUserProfile/admin') !== false,
		    ),
		    array(
		        'name' => Yii::t('admin_layout', 'Create Client'),
		        'url' => array('/userControl/adminUserProfile/createNewUser'),
		        'active' => strpos($url, 'createNewUser') !== false,
		    ),
		    array(
		        'name' => Yii::t('admin_layout', 'Rights to users'),
		        'url' => array('/auth/assignment/index'),
		        'active' => false,
		    ),
		    array(
		        'name' => Yii::t('messages', 'Register of messages'),
		        'url' => array('/userControl/adminUserMessages/admin'),
		        'active' => false,
		    ),
		);

        return true;
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
                'actions' => array('view', 'admin', 'delete', 'toggle', 'update', 'loginAsUser', 'logoutAsUser', 'createNewUser', 'successUser'),
                'roles' => array('managerNotDiscount', 'manager', 'mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            'toggle' => 'ext.jtogglecolumn.ToggleAction',
            'switch' => 'ext.jtogglecolumn.SwitchAction', // only if you need it
            'qtoggle' => 'ext.jtogglecolumn.QtoggleAction', // only if you need it
        );
    }

    public function actionSuccessUser($id) {
        $model = $this->loadModel($id);

        $password = Yii::app()->cache->get('userPasswod' . $id);
        if (empty($password)) {
            throw new CHttpException(400, Yii::t('userControl', 'For security reasons, your password has been deleted, see the generated password is not possible, please use the form password recovery.'));
        } else {
            Yii::app()->cache->set('userPasswod' . $id, '');
        }
        $this->render('successUser', array('model' => $model, 'password' => $password));
    }

    public function actionCreateNewUser() {
//die;
        $model_profile = new UserProfile;
        $model_profile->email = '';
//        print_r($model_profile);
        $model_profile->scenario = 'main';

        $this->performAjaxValidation($model_profile);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UserProfile'])) {
            $model_profile->attributes = $_POST['UserProfile'];



            $password = md5($model_profile->email . $model_profile->first_name . time() . $model_profile->organization_name);
            if ($model_profile->validate()) {
                $authIdentity = new LEmailService;
                $authIdentity->email = $model_profile->email;
                $authIdentity->password = $password;
                $authIdentity->user = null;
                $authIdentity->rememberMe = false;
//                die;
                if ($authIdentity->authenticate(true, true, true)) {
//                    die;
                    $identity = new LUserIdentity($authIdentity);
                    $identity->authenticate();
                    $model_profile->uid = $identity->account->uid;
                    $model_profile->save();
                    Yii::app()->cache->set('userPasswod' . $model_profile->uid, $password);
                    Yii::app()->db->createCommand("UPDATE `lily_user` SET inited='1' WHERE uid='$model_profile->uid' LIMIT 1")->query();
                    $this->redirect(array('successUser', 'id' => $model_profile->uid));
//                    $result[] = $model_profile->uid;
                }
//Special redirect to fire popup window closing
//                $authIdentity->cancel();
            }
        }
        $this->render('createNewUser', array('model' => $model_profile));
    }

    public function actionLoginAsUser($id) {
        $model = $this->loadModel($id);

        $model->loginAsUser();

        $this->redirect(Yii::app()->homeUrl);
        //$this->render('loginAsUser', array('model' => $model));
    }

    public function actionLogoutAsUser($id = '') {
        UserProfile::logoutAsUserStatic();

        $this->render('logoutAsUser');
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $model->scenario = 'main';
        $old = $model->legal_entity;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UserProfile'])) {

            $model->attributes = $_POST['UserProfile'];
//            if (empty($model->legal_entity))
//                $model->legal_entity = $old;
//            if ($old != $model->legal_entity && $model->legal_entity == 0)
//                $model->price_group = 2;
//            if ($old != $model->legal_entity && ( $model->legal_entity == 2 || $model->legal_entity == 1))
//                $model->price_group = 3;

            if ($model->save())
                $this->redirect(array('admin'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = $this->loadModel($id);
            $model->deleteUserAccounts();
            $model->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, Yii::t('userControl', 'This page doesn\'t exist.'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new UserProfile('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserProfile']))
            $model->attributes = $_GET['UserProfile'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = UserProfile::model()->findByAttributes(array('uid' => $id));
        if ($model === null)
            throw new CHttpException(404, Yii::t('userControl', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-profile-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}