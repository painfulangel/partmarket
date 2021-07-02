<?php

class AdminController extends Controller {

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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'admin', 'delete', 'toggle'),
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
        $model = new Parsers;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Parsers'])) {
            $model->attributes = $_POST['Parsers'];
            if ($model->save())
                $this->redirect(array('admin'));
        }
        $priceGroupsList = new PricesRulesGroups;
        $currencies = new Currencies;
        $this->render('create', array(
            'model' => $model,
            'priceGroupsList' => $priceGroupsList->getList(),
            'currencies' => $currencies->getList(),
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

        if (isset($_POST['Parsers'])) {
            $model->attributes = $_POST['Parsers'];
            if ($model->save())
                $this->redirect(array('admin', 'Parsers_page' => (isset($_GET['Parsers_page']) ? $_GET['Parsers_page'] : '')));
        }

        $priceGroupsList = new PricesRulesGroups;
        $currencies = new Currencies;
        $this->render('update', array(
            'model' => $model,
            'priceGroupsList' => $priceGroupsList->getList(),
            'currencies' => $currencies->getList(),
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
            throw new CHttpException(400, Yii::t('parsers', 'This page doesn\'t exist.'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Parsers('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Parsers']))
            $model->attributes = $_GET['Parsers'];

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
        $model = Parsers::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('parsers', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'parsers-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
