<?php
class RequestVinController extends Controller {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
                'actions' => array('create', 'captcha'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
            ),
        );
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new RequestVin;
        $model->initUser();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['RequestVin']) && (array_key_exists('g-recaptcha-response', $_POST) || !Yii::app()->user->isGuest)) {
            $model->attributes = $_POST['RequestVin'];
            
            $save = !Yii::app()->user->isGuest;
            
            if (!$save) $save = ReCaptcha::isGoodCaptcha($_POST['g-recaptcha-response']);
            
            if ($save) {
	            if ($model->save()) {
	                Yii::app()->user->setFlash('contact', Yii::t('requests', 'Your request has been accepted. We will contact You as soon as possible.') );
	                $this->refresh();
	            }
            } else {
            	$model->addError('verifyCode', Yii::t('requests', 'The response parameter is invalid or malformed'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        	'recaptchakey' => Yii::app()->config->get('Site.recaptchakey'),
        ));
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = RequestVin::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('requests', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'request-vin-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}