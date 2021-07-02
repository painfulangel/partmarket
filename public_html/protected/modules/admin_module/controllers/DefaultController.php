<?php

class DefaultController extends Controller {

    public $layout = '//layouts/admin_column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function actionIndex() {
        $this->render('index');
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index'),
                'roles' => array('manager', 'texts', 'managerNotDiscount', 'mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

}
