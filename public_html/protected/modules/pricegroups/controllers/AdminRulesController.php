<?php

class AdminRulesController extends Controller {

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
                'actions' => array('create', 'update', 'admin', 'delete'),
                'roles' => array('mainManager', 'admin'),
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
    public function actionCreate($id = '') {
        $model = new PricesRules;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $model->group_id = $id;
        $model->brand = 0;
        $model->top_value = 0;

        if (isset($_POST['PricesRules'])) {
            $model->attributes = $_POST['PricesRules'];
            if ($model->save())
                $this->redirect(array('admin', 'id' => $model->group_id));
        }

        $priceGroupsList = new PricesRulesGroups;
        $this->render('create', array(
            'priceGroupsList' => $priceGroupsList->getList(),
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



        if (isset($_POST['PricesRules'])) {
            $model->attributes = $_POST['PricesRules'];
            if ($model->save())
                $this->redirect(array('admin', 'id' => $model->group_id, 'PricesRules_page' => (isset($_GET['PricesRules_page']) ? $_GET['PricesRules_page'] : '')));
        }
        $priceGroupsList = new PricesRulesGroups;
        $this->render('update', array(
            'priceGroupsList' => $priceGroupsList->getList(),
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
            throw new CHttpException(400, Yii::t('pricegroups', 'This page doesn\'t exist.'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin($id = '') {
        $model = new PricesRules('search');

        $model->unsetAttributes();  // clear any default values
        if (!empty($id))
            $model->group_id = $id;
        if (isset($_GET['PricesRules']))
            $model->attributes = $_GET['PricesRules'];

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
        $model = PricesRules::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('pricegroups', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'prices-rules-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
