<?php

class UsersCarsController extends Controller {

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
                'actions' => array('index', 'create', 'update', 'delete', 'getDetail', 'deleteDetail'),
                'users' => array('@'),
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
    public function actionCreate() {
        $model = new UsersCars;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UsersCars'])) {
            $model->attributes = $_POST['UsersCars'];
            if ($model->save())
                $this->redirect(array('index'));
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
        $model2 = new UsersCarsDetails();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (Yii::app()->user->id != $model->user_id)
            throw new CHttpException(404, Yii::t('userControl', 'You do not have access.'));

        if (isset($_POST['UsersCars'])) {
            $model->attributes = $_POST['UsersCars'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        if (array_key_exists('UsersCarsDetails', $_POST)) {
            if (array_key_exists('id', $_POST)) {
                $draft = UsersCarsDetails::model()->findByPk(intval($_POST['id']));
                if (is_object($draft) && (Yii::app()->user->id == $draft->user_id)) {
                    $model2 = $draft;
                }
            }

            $model2->attributes = $_POST['UsersCarsDetails'];

            $model2->user_id = Yii::app()->user->id;
            $model2->car_id = $id;
            if ($model2->save())
                $this->redirect(array('update', 'id' => $id));
        }

        $details = new UsersCarsDetails();
        $details->user_id = Yii::app()->user->id;
        $details->car_id = $id;

        $this->render('update', array(
            'model'   => $model,
            'details' => $details,
            'model2'  => $model2
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
            $model = $this->loadModel($id);
            if (Yii::app()->user->id == $model->user_id)
                $model->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, Yii::t('userControl', 'This page doesn\'t exist.'));
    }

    public function actionDeleteDetail($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = UsersCarsDetails::model()->findByPk(intval($id));
            if (Yii::app()->user->id == $model->user_id)
                $model->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, Yii::t('userControl', 'This page doesn\'t exist.'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new UsersCars('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UsersCars']))
            $model->attributes = $_GET['UsersCars'];
        $model->user_id = Yii::app()->user->id;
        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionGetDetail() {
        $request = Yii::app()->request;
        $id = $request->getPost('id');
        if ($id = intval($id)) {
            $detail = UsersCarsDetails::model()->findByPk($id);

            if ($detail->user_id == Yii::app()->user->id) {
                echo json_encode(array('id' => $detail->primaryKey, 'name' => $detail->name, 'brand' => $detail->brand, 'article' => $detail->article));
            }
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = UsersCars::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('userControl', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'users-cars-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
