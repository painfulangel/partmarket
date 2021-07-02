<?php
class ItemsController extends Controller {
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
                'actions' => array('captcha', 'index', 'view', 'create', 'update', 'admin', 'delete', 'search'),
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

    public function actionSearch() {
        $model = new KatalogVavtoItems('search');
        $model->unsetAttributes();
        if (isset($_GET['KatalogVavtoItems'])) {
            $model->attributes = $_GET['KatalogVavtoItems'];
        }

        $this->render('search', array(
            'model' => $model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
		Yii::import('requests.models.RequestGetPrice');

    	$model = $this->loadModel($id);
    	
    	//Form
    	$formSend = false;
    	
    	$model2 = new RequestGetPrice;
    	$model2->initUser();
    	
    	if (isset($_POST['RequestGetPrice']) && (array_key_exists('g-recaptcha-response', $_POST) || !Yii::app()->user->isGuest)) {
    		$formSend = true;
    		
    		$model2->attributes = $_POST['RequestGetPrice'];
    	
    		$save = !Yii::app()->user->isGuest;
    	
    		if (!$save) {
				Yii::import('requests.models.ReCaptcha');
		
    			$save = ReCaptcha::isGoodCaptcha($_POST['g-recaptcha-response']);
    		}
    	
    		if ($save) {
    			if ($model2->save()) {
    				Yii::app()->user->setFlash('contact', Yii::t('requests', 'Your request has been accepted. We will contact You as soon as possible.') );
    				$this->refresh();
    			}
    		} else {
    			$model2->addError('verifyCode', Yii::t('requests', 'The response parameter is invalid or malformed'));
    		}
    	}
    	//Form
    	
        $this->render('view', array(
        	'formSend' => $formSend,
            'model'    => $model,
        	'model2'   => $model2,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id = '') {
        $model = new KatalogVavtoItems;
        if (!empty($id))
            $model->cathegory_id = $id;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['KatalogVavtoItems'])) {
            $model->attributes = $_POST['KatalogVavtoItems'];
            if ($model->save())
                $this->redirect(array('admin', 'KatalogVavtoItems[cathegory_id]' => $model->cathegory_id));
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
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['KatalogVavtoItems'])) {
            $model->attributes = $_POST['KatalogVavtoItems'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, Yii::t('katalogVavto', 'This Page not found.'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('KatalogVavtoItems');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new KatalogVavtoItems('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['KatalogVavtoItems']))
            $model->attributes = $_GET['KatalogVavtoItems'];

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
        $model = KatalogVavtoItems::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('katalogVavto', 'This Page not found.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'katalog-accessories-items-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}