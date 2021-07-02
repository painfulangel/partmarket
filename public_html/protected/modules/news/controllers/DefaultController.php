<?php

class DefaultController extends Controller {

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
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($link) {
        $this->render('view', array(
            'model' => $this->loadModel(null, $link),
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('News', array(
            'criteria' => array(
                'condition' => 'active_state=1',
                'order' => 'id DESC',
            ),
            'countCriteria' => array(
                'condition' => 'active_state=1',
            // 'order' and 'with' clauses have no meaning for the count query
            ),
            'pagination' => array(
                'pageSize' => Yii::app()->controller->module->perPage,
            ),
        ));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $link = '') {
        if (empty($link))
            $model = News::model()->findByPk($id);
        else
            $model = News::model()->findByAttributes(array('link' => $link));
        if ($model === null)
            throw new CHttpException(404, Yii::t('news', 'This page doesn\'t exist.'));
        return $model;
    }

}
