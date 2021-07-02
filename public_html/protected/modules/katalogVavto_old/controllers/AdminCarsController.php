<?php
class AdminCarsController extends BaseCatalogController {
    public $layout = '//layouts/admin_column2';
    
    public function __construct($id, $module = null)
    {
    	parent::__construct($id, $module);
        
    }
    
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
            'order' => array(
                'class' => 'ext.OrderColumn.OrderAction',
                'modelClass' => 'KatalogVavtoCars',
                'pkName' => 'id',
            ),
        );
    }

    public function actionToggle($id, $attribute) {
        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModel($id);
            $model->$attribute = ($model->$attribute == 0) ? 1 : 0;
            $model->save(false);

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, Yii::t('katalogVavto', 'This Page not found.'));
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'toggle', 'order', 'export', 'import'),
                'roles' => array('mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionExport() {
        $model = new KatalogVavtoCars;
        header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
        header('Last-Modified: ' . gmdate("D,d M YH:i:s") . ' GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=export_katalogVavto_cars_cp1251.csv');

        echo $model->export();
    }

    public function actionImport() {
        $model = new KatalogVavtoImportCarsModel;

        if (isset($_POST['KatalogVavtoImportCarsModel'])) {
            $model->attributes = $_POST['KatalogVavtoImportCarsModel'];
            if ($model->import())
                $this->redirect(array('admin'));
        }

        $this->render('import', array(
            'model' => $model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->redirect(array('adminItems/admin', 'KatalogVavtoItems[cathegory_id]' => $id));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new KatalogVavtoCars;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['KatalogVavtoCars'])) {
            $model->attributes = $_POST['KatalogVavtoCars'];
            $model->original_data = true;
            if ($model->validate()) {
                if ($model->save()) {
                    $this->redirect(array('admin'));
//                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
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

        if (isset($_POST['KatalogVavtoCars'])) {
            $model->attributes = $_POST['KatalogVavtoCars'];
            $model->original_data = true;
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
            throw new CHttpException(400, Yii::t('katalogVavto', 'This Page not found.'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('KatalogVavtoCars');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new KatalogVavtoCars('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['KatalogVavtoCars']))
            $model->attributes = $_GET['KatalogVavtoCars'];

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
        $model = KatalogVavtoCars::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('katalogVavto', 'This Page not found.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'katalog-accessories-cathegorias-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
