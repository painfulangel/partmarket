<?php
class AdminWebPaymentsSystemController extends Controller {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/admin_column2';

    public function beforeAction($action) {
        $this->admin_header = array (
            array(
                'name' => Yii::t('admin_layout', 'Price politics'),
                'url' => array('/pricegroups/adminGroups/admin'),
                'active' => false,
            ),
            array(
                'name' => Yii::t('admin_layout', 'Payment system'),
                'url' => array('/webPayments/adminWebPaymentsSystem/admin'),
                'active' => true,
            ),
            array(
                'name' => Yii::t('admin_layout', 'Currency'),
                'url' => array('/currencies/admin/admin'),
                'active' => false,
            ),
            array (
                'name' => Yii::t('admin_layout', 'Statistics'),
                'url' => array ('/statistics/admin/admin'),
                'active' => false
            ),
        );

        if (!defined('TURNON_CITIES') || (TURNON_CITIES === true)) {
            $this->admin_header[] = array (
                'name' => Yii::t('cities', 'Cities'),
                'url' => array ('/cities/admin/admin'),
                'active' => false
            );
        }

        return parent::beforeAction($action);
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update', 'admin', 'toggle'),
                'roles' => array('mainManager', 'admin'),
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new WebPaymentsSystem;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['WebPaymentsSystem'])) {
            $model->attributes = $_POST['WebPaymentsSystem'];
            $model->original_data = true;
            if ($model->save())
                $this->redirect(array('admin'));
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

        if (isset($_POST['WebPaymentsSystem'])) {
            $model->attributes = $_POST['WebPaymentsSystem'];
            $model->original_data = true;
            if ($model->save())
                $this->redirect(array('admin'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new WebPaymentsSystem('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['WebPaymentsSystem']))
            $model->attributes = $_GET['WebPaymentsSystem'];

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
        $model = WebPaymentsSystem::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('webPayments', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'web-payments-system-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}