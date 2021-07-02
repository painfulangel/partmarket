<?php
class AdminWebPaymentsController extends Controller {
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
                'roles' => array('managerNotDiscount', 'manager', 'mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new WebPayments('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['WebPayments']))
            $model->attributes = $_GET['WebPayments'];

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
        $model = WebPayments::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404,Yii::t('webPayments', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'web-payments-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
