<?php

class FeedbacksController extends Controller {

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
                'actions' => array('index', 'create', 'captcha'),
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Feedbacks;
        $model->initUser();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Feedbacks'])) {
            $model->attributes = $_POST['Feedbacks'];
            if ($model->save()) {
                Yii::app()->user->setFlash('contact',Yii::t('requests', 'Thank you. Your review is accepted.') );
                $this->refresh();
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $criteria = new CDbCriteria;


        $criteria->compare('active_state', '1');
        $dataProvider = new CActiveDataProvider('Feedbacks', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Feedbacks::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('requests', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'feedbacks-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
