<?php

class AdminAutoloadRulesController extends Controller {

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

    public function actions() {
        return array(
            'toggle' => 'ext.jtogglecolumn.ToggleAction',
            'switch' => 'ext.jtogglecolumn.SwitchAction', // only if you need it
            'qtoggle' => 'ext.jtogglecolumn.QtoggleAction', // only if you need it
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
//            array('allow', // allow all users to perform 'index' and 'view' actions
//                'actions' => array('index', 'view'),
//                'users' => array('*'),
//            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'create', 'update', 'toggle', 'start'),
                'roles' => array('mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Запуск загрузки прайсов в ручную
     * @param $id
     * @throws CHttpException
     */
    public function actionStart($id) {
        $model = $this->loadModel($id);
        BackgroundProcess::launchBackgroundProcessStart('php ' . realpath(Yii::app()->basePath) . '/yiic.php FtpPriceLoad ' . $id);
//        BackgroundProcess::launchBackgroundProcessStart(realpath(Yii::app()->basePath) . '/yiic FtpPriceLoad ' . $id,realpath(Yii::app()->basePath) . '/runtime/delete/'.$id.'O.txt',realpath(Yii::app()->basePath) . '/runtime/delete/'.$id.'e.txt');
        $this->render('start', array('model' => $model));
    }

    public function actionLoadFiles() {
        
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new PricesFtpAutoloadRules;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PricesFtpAutoloadRules'])) {
            $model->attributes = $_POST['PricesFtpAutoloadRules'];
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

        if (isset($_POST['PricesFtpAutoloadRules'])) {
            $model->attributes = $_POST['PricesFtpAutoloadRules'];
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
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, Yii::t('prices', 'This page doesn\'t exist.'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PricesFtpAutoloadRules('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PricesFtpAutoloadRules']))
            $model->attributes = $_GET['PricesFtpAutoloadRules'];

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
        $model = PricesFtpAutoloadRules::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'prices-ftp-autoload-rules-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
