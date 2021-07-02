<?php
class AdminUsersCarsController extends Controller {
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

    protected function beforeAction($action)
    {
        $url = Yii::app()->urlManager->parseUrl(Yii::app()->request);
        
        $this->admin_header = array(
            array(
                'name' => Yii::t('admin_layout', 'Clients'),
                'url' => array('/userControl/adminUserProfile/admin'),
                'active' => true,
            ),
            array(
                'name' => Yii::t('admin_layout', 'Create Client'),
                'url' => array('/userControl/adminUserProfile/createNewUser'),
                'active' => false,
            ),
            array(
                'name' => Yii::t('admin_layout', 'Rights to users'),
                'url' => array('/auth/assignment/index'),
                'active' => false,
            ),
            array(
                'name' => Yii::t('messages', 'Register of messages'),
                'url' => array('/userControl/adminUserMessages/admin'),
                'active' => false,
            ),
        );

        return true;
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('admin', 'delete', 'create', 'update', 'deleteDetail'),
                'roles' => array('managerNotDiscount', 'manager', 'mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionCreate() {
        $model = new UsersCars();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UsersCars'])) {
            $model->attributes = $_POST['UsersCars'];
            $model->user_id = $_GET['user_id'];
            if ($model->save())
                $this->redirect(array('admin', 'UsersCars[user_id]' => $_GET['user_id']));
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

        if (isset($_POST['UsersCars'])) {
            $model->attributes = $_POST['UsersCars'];
            if ($model->save())
                $this->redirect(array('admin', 'UsersCars[user_id]' => $model->user_id));
        }

        if (array_key_exists('UsersCarsDetails', $_POST)) {
            if (array_key_exists('id', $_POST)) {
                $draft = UsersCarsDetails::model()->findByPk(intval($_POST['id']));
                if (is_object($draft)) {
                    $model2 = $draft;
                }
            }

            $model2->attributes = $_POST['UsersCarsDetails'];

            $model2->user_id = $model->user_id;
            $model2->car_id = $id;
            if ($model2->save())
                $this->redirect(array('update', 'id' => $model->user_id));
        }

        $details = new UsersCarsDetails();
        $details->user_id = $model->user_id;
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
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, Yii::t('userControl', 'This page doesn\'t exist.'));
    }

    public function actionDeleteDetail($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = UsersCarsDetails::model()->findByPk(intval($id));
            if (is_object($model))
                $model->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, Yii::t('userControl', 'This page doesn\'t exist.'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new UsersCars('search');
        $model->unsetAttributes();  // clear any default values

        $name = '';
        $user_id = '';
        if (isset($_GET['UsersCars'])) {
            $model->attributes = $_GET['UsersCars'];

            if (array_key_exists('user_id', $_GET['UsersCars'])) {
                $user_id = intval($_GET['UsersCars']['user_id']);

                $up = UserProfile::model()->findByAttributes(array('uid' => $user_id));
                if (is_object($up)) {
                    $name = $up->getFullNameId();
                }
            }
        }

        $this->render('admin', array(
            'model'   => $model,
            'name'    => $name,
            'user_id' => $user_id,
        ));
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
