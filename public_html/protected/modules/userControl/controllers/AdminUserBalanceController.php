<?php

class AdminUserBalanceController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/admin_column2';

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
                'actions' => array('admin', 'create'),
                'roles' => array('managerNotDiscount', 'manager', 'mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id) {
        $model = new UserBalanceOperations;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $model->user_id = $id;
        if (isset($_POST['UserBalanceOperations'])) {
            $model->attributes = $_POST['UserBalanceOperations'];

            if ($model->save())
                $this->redirect(array('admin', 'id' => $model->user_id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin($id) {
        $model = new UserBalanceOperations('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserBalanceOperations']))
            $model->attributes = $_GET['UserBalanceOperations'];

        $model->user_id = $id;

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
        $model = UserBalanceOperations::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('userControl', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-balance-operations-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
