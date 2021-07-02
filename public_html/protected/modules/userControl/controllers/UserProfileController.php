<?php
class UserProfileController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        	'ajaxOnly + changeCurrency',
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'view', 'cabinet', 'settings', 'successUser', 'changeCurrency'),
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('registration'),
                'users' => array('?'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionSettings()
    {
        $id = Yii::app()->user->id;

//        if (!Yii::app()->user->checkAccess('editProfile', array('uid' => $id)))
//            throw new CHttpException(403);

        $model = $this->loadModel(null, $id);

        $this->render('settings', array(
            'model' => $model,
        ));
    }

    public function actionCabinet()
    {
        $id = Yii::app()->user->id;

//        if (!Yii::app()->user->checkAccess('editProfile', array('uid' => $id)))
//            throw new CHttpException(403);

        $model = $this->loadModel(null, $id);

        $this->render('cabinet', array(
            'model' => $model,
        ));
    }

    public function actionSuccessUser()
    {
        $this->render('successUser');
    }

    public function actionRegistration()
    {
        $model_profile = new UserProfile;
        $model_profile->email = '';
        $model_profile->scenario = 'main_regs';
        $this->performAjaxValidation($model_profile);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UserProfile'])) {
            $model_profile->attributes = $_POST['UserProfile'];

            if ($model_profile->validate()) {
                $authIdentity = new LEmailService;
                $authIdentity->email = $model_profile->email;
                $authIdentity->password = $model_profile->reg_password;
                $authIdentity->user = null;
                $authIdentity->rememberMe = false;
                if ($authIdentity->authenticate(true, true, true)) {
                    $identity = new LUserIdentity($authIdentity);
                    $identity->authenticate();
                    $model_profile->uid = $identity->account->uid;
                    $model_profile->save();
                    Yii::app()->cache->set('userPasswod' . $model_profile->uid, $model_profile->reg_password);
                    Yii::app()->db->createCommand("UPDATE `lily_user` SET inited='1' WHERE uid='$model_profile->uid' LIMIT 1")->query();
                    $user = (isset($identity->user) ? $identity->user : $identity->session->user);
                    if ($user->state == LUser::BANNED_STATE && Yii::app()->authManager->checkAccess('unbanUser', $user->uid, array('uid' => $user->uid))) {
                        $user->state = LUser::ACTIVE_STATE;
                        if (!$user->save())
                            throw new CDbException("failed to save user");
                    }

                    $result = Yii::app()->user->login($identity, 86400);
                    if ($result)
                        $this->redirect(array('successUser'));
                    else
                        throw new LException("login() returned false");
                } else {

                }
            } else {

            }
        }
        $this->render('registration', array('model' => $model_profile));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id = '')
    {
        if (empty($id))
            $id = Yii::app()->user->id;

        if (!Yii::app()->user->checkAccess('editProfile', array('uid' => $id)))
            throw new CHttpException(403);

        $model = $this->loadModel(null, $id);

        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {

        $model = new UserProfile;
        $model->scenario = 'main';

        $this->performAjaxValidation($model);


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UserProfile'])) {
            $model->attributes = $_POST['UserProfile'];
            $model->uid = Yii::app()->user->id;
            $model->merged = 0;
            if ($model->save()) {
                if (LilyModule::instance()->userIniter->isStarted) {
                    LilyModule::instance()->userIniter->nextStep();
                }
//                $this->redirect(array('/' . LilyModule::route('user/view'), 'uid' => $model->uid));
                $this->redirect(array('view'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id = '')
    {
        if (empty($id))
            $id = Yii::app()->user->id;

        if (!Yii::app()->user->checkAccess('editProfile', array('uid' => $id)))
            throw new CHttpException(403);

        $model = $this->loadModel(null, $id);
        $model->scenario = 'main';

        $old = $model->legal_entity;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UserProfile'])) {
            $model->attributes = $_POST['UserProfile'];
            if (empty($model->legal_entity))
                $model->legal_entity = $old;

            //uncomment to enable avto change price groups
//            if ($old != $model->legal_entity && $model->legal_entity == 0)
//                $model->price_group = 2;
//            if ($old != $model->legal_entity && ( $model->legal_entity == 2 || $model->legal_entity == 1))
//                $model->price_group = 3;
            if ($model->save())
                $this->redirect(array('view'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }
    
    public function actionChangeCurrency() {
    	if ($id_currency = intval(Yii::app()->request->getPost('id_currency'))) {
    		$model = $this->loadModel(null, Yii::app()->user->id);
    		$model->currency_type = $id_currency;
    		$model->save();
    	}
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id = '', $uid = '')
    {
        if (empty($uid))
            $model = UserProfile::model()->findByPk($id);
        else
            $model = UserProfile::model()->findByAttributes(array('uid' => $uid));
        if ($model === null)
            throw new CHttpException(404, Yii::t('userControl', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-profile-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}