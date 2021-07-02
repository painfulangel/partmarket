<?php

class KatalogTOController extends Controller {

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
                'actions' => array('brands', 'models', 'types', 'items'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Lists all models.
     */
    public function actionBrands() {
        $model = new WToCars('search');
        $model->unsetAttributes();
        $model->is_active = 1;
        $this->render('brands', array(
            'model' => $model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionModels($id) {
        $model = new WToModels('search');
        $model->unsetAttributes();
        $model->is_active = 1;
        $model->car_id = $id;
        $model2 = new WToCars;
        $seo_model = $this->loadModel($id, $model2);
        $this->render('models', array(
            'model' => $model,
            'seo_model' => $seo_model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionTypes($id) {
        $model = new WToTypes('search');
        $model->unsetAttributes();
        $model->is_active = 1;
        $model->model_id = $id;
        $model2 = new WToModels;
        $seo_model = $this->loadModel($id, $model2);
        $this->render('types', array(
            'model' => $model,
            'seo_model' => $seo_model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionItems($id) {
        $model = new WTo('search');
        $model->unsetAttributes();
//        $model->is_active = 1;
        $model->type_id = $id;
        $model2 = new WToTypes;
        $seo_model = $this->loadModel($id, $model2);
        $this->render('items', array(
            'model' => $model,
            'seo_model' => $seo_model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $model) {

        $model2 = $model->findByPk($id);
        if ($model2 === null)
            throw new CHttpException(404, Yii::t('katalogTO', 'This page doesn\'t exist.'));
        return $model2;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'wto-cars-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
