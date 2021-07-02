<?php

class DefaultController extends Controller {

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
                'actions' => array('view', 'index'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('downloadPrice'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionDownloadPrice() {
        set_time_limit(0);
        header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
        header('Last-Modified: ' . gmdate("D,d M YH:i:s") . ' GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=price_from_' . date('d-m-Y_H-i-s') . '_.csv');
        echo Prices::model()->exportUserPrice();
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionView($id, $article_id = '') {
        $model = new PricesData('search');
        $model->unsetAttributes();  // clear any default values
        $model->price_id = $id;
        if (isset($_GET['PricesData']))
            $model->attributes = $_GET['PricesData'];

        $model->price_id = $id;
        if (!empty($article_id)) {
            $model->id = $article_id;
        }

        $model_price = Prices::model()->findByPk($id);
        if (is_object($model_price)) {
	        $model_store = Stores::model()->findByPk($model_price->store_id);
	
	        $this->render('view', array(
	            'model' => $model,
	            'model_store' => $model_store,
	            'model_price' => $model_price,
	        ));
        } else {
        	throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
        }
    }

    /**
     * Manages all models.
     */
    public function actionIndex() {
        $model = new Prices('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Prices']))
            $model->attributes = $_GET['Prices'];

        $model->search_state = 1;

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Prices::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadPriceModel($id) {
        $model = PricesData::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'prices-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
