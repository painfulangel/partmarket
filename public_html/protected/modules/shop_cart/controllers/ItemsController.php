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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'delete',),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = $this->loadModel($id);
            if ($model->user_id == Yii::app()->user->id)
                $model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400,Yii::t('shop_cart', 'This page doesn\'t exist.'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex() {
        $model = new Items('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Items']))
            $model->attributes = $_GET['Items'];

        $model->user_id = Yii::app()->user->id;

        $itemStatus = new ItemsStatus;
        $this->render('index', array(
            'model' => $model,
            'itemStatus' => $itemStatus,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Items::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('shop_cart', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'items-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
