<?php

class AdminController extends Controller {

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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('order', 'create', 'delete', 'index', 'toggle', 'update'),
                'roles' => array('texts', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            'order' => array(
                'class' => 'ext.OrderColumn.NestedSetOrderAction',
                'modelClass' => 'Page',
                'pkName' => 'id',
                'parentIdName' => 'parent_id',
                'rootName' => 'root',
                'levelName' => 'level',
                'lftName' => 'lft',
            ),
        );
    }

    public function actionCreate() {
        $model = new Page;

        $this->performAjaxValidation($model);

        if (isset($_POST['Page'])) {
            $model->attributes = $_POST['Page'];
            $model->original_data = true;
            if ($model->validate()) {
                if ($model->parent_id) {
                    $parent = Page::model()->findByPk($model->parent_id);
                    if ($parent !== null)
                        $model->appendTo($parent);
                }

                if ($model->saveNode()) {
                    Yii::app()->user->setFlash('success', Yii::t('pages', 'Page') . " «{$model->page_title}» " . Yii::t('pages', 'successfully created.'));
                    $this->redirect(array('update', 'id' => $model->id));
                }
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        if (isset($_POST['Page'])) {
            $model->attributes = $_POST['Page'];
            $model->original_data = true;
            if ((int) $model->parent_id !== (int) $model->_parent_id) {
                if (empty($model->parent_id))
                    $result = $model->moveAsRoot();
                else {
                    $parent = Page::model()->findByPk($model->parent_id);
                    if ($parent !== null)
                        $result = $model->moveAsFirst($parent);
                }
            }

            if ($model->saveNode()) {
                Yii::app()->user->setFlash('success', Yii::t('pages', 'Page') . " «{$model->page_title}» " . Yii::t('pages', 'successfully edited.'));
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->deleteNode();

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, Yii::t('pages', 'This Page not found.'));
    }

    public function actionIndex() {
        $model = new Page('search');
        $model->unsetAttributes();
        if (isset($_GET['Page']))
            $model->attributes = $_GET['Page'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionToggle($id, $attribute) {
        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModel($id);
            $model->$attribute = ($model->$attribute == 0) ? 1 : 0;
            $model->saveNode(false);

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, Yii::t('pages', 'This Page not found.'));
    }

    public function loadModel($id) {
        $model = Page::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('pages', 'This Page not found.'));
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'page-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
