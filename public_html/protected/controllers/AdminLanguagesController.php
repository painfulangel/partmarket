<?php
class AdminLanguagesController extends Controller {
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
    
    public function actions()
    {
    	return array(
    			'toggle' => 'ext.jtogglecolumn.ToggleAction',
    			'switch' => 'ext.jtogglecolumn.SwitchAction', // only if you need it
    			'qtoggle' => 'ext.jtogglecolumn.QtoggleAction', // only if you need it
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
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'create', 'update', 'download', 'toggle'),
                'roles' => array('mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function getContents($dir, $files = array()) {
        if (!($res = opendir($dir)))
            return array();
        while (($file = readdir($res)) == TRUE)
//                echo $file;
            if ($file != "." && $file != "..") {
                if (!is_dir("$dir/$file")) {
                    array_push($files, "$dir/$file");
                }
            }
        closedir($res);
        return $files;
    }

    public function actionDownload($link_name) {
        if (file_exists(Yii::app()->basePath . '/messages/' . $link_name)) {
            $files = $this->getContents(Yii::app()->basePath . '/messages/' . $link_name);
            if (!file_exists(Yii::app()->basePath . '/runtime/translated_zip'))
                mkdir(Yii::app()->basePath . '/runtime/translated_zip');
            $zipname = Yii::app()->basePath . '/runtime/translated_zip/' . $link_name . '.zip';
            if (file_exists($zipname))
                @unlink($zipname);
            $zip = new ZipArchive;
            $zip->open($zipname, ZipArchive::CREATE);
            foreach ($files as $file) {
//                echo $file;
                $zip->addFile($file, basename($file));
            }
            $zip->close();

///Then download the zipped file.
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $link_name . '.zip');
            header('Content-Length: ' . filesize($zipname));
            readfile($zipname);
            @unlink($zipname);
        } else {
            throw new CHttpException(404, Yii::t('languages', 'This Page not found.'));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Languages;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Languages'])) {
            $model->attributes = $_POST['Languages'];
            if ($model->save())
                $this->redirect(array('admin'));
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

        if (isset($_POST['Languages'])) {
            $model->attributes = $_POST['Languages'];
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
            throw new CHttpException(400, Yii::t('languages', 'This Page not found.'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Languages('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Languages']))
            $model->attributes = $_GET['Languages'];

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
        $model = Languages::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('languages', 'This Page not found.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'languages-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}