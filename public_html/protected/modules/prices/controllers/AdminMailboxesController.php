<?php

class AdminMailboxesController extends Controller {

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
                'actions' => array('admin', 'delete', 'create', 'update', 'addSource', 'viewSources', 'updateSource', 'deleteSource', 'loadedFiles', 'deleteAllFiles', 'checkNow', 'ruleFiles'),
                'roles' => array('mainManager', 'admin'),
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
        $model = new PricesFtpAutoloadMailboxes;

        $model->imap_port = PricesFtpAutoloadMailboxes::IMAP_PORT;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PricesFtpAutoloadMailboxes'])) {
            $model->attributes = $_POST['PricesFtpAutoloadMailboxes'];
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

        if (isset($_POST['PricesFtpAutoloadMailboxes'])) {
            $model->attributes = $_POST['PricesFtpAutoloadMailboxes'];
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
            throw new CHttpException(400, Yii::t('prices', 'This page doesn\'t exist.'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PricesFtpAutoloadMailboxes('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PricesFtpAutoloadMailboxes']))
            $model->attributes = $_GET['PricesFtpAutoloadMailboxes'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionAddSource($id) {

        $mailbox = $this->loadModel($id);

        $model = new PricesFtpSourcesRules();

        if (isset($_POST['PricesFtpSourcesRules'])) {
            $model->attributes = $_POST['PricesFtpSourcesRules'];
            if ($model->save()){
                $this->redirect(array('admin'));
            }else{
                echo CVarDumper::dump($model->getErrors(), 10, true);exit;
            }
        }

        $this->render('add_source', array(
            'model' => $model,
            'mailbox'=>$mailbox
        ));
    }

    public function actionUpdateSource($id)
    {

        $model = PricesFtpSourcesRules::model()->findByPk($id);

        $mailbox = $model->mailBox;



        if (isset($_POST['PricesFtpSourcesRules'])) {
            $model->attributes = $_POST['PricesFtpSourcesRules'];
            if ($model->save()){
                $this->redirect(array('admin'));
            }else{
                echo CVarDumper::dump($model->getErrors(), 10, true);exit;
            }
        }

        $this->render('update_source', array(
            'model' => $model,
            'mailbox'=>$mailbox
        ));
    }

    /**
     * Удаление правила
     * @param $id
     * @throws CDbException
     */
    public function actionDeleteSource($id)
    {

        $model = PricesFtpSourcesRules::model()->findByPk($id);

        if($model){
            $model->delete();
        }

        Yii::app()->user->setFlash('success', 'Правило успешно удалено!');

        $this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionViewSources($id) {

        $model = $this->loadModel($id);

        $dataProvider = new CActiveDataProvider('PricesFtpSourcesRules', array(
            'criteria'=>array(
                'condition'=>'mail_id='.$model->id,
            ),
            'pagination'=>array(
                'pageSize'=>20,
            ),
        ));

        $this->render('view_sources', array(
            'dataProvider' => $dataProvider,
            'model'=>$model
        ));
    }

    public function actionLoadedFiles($id)
    {
        $out = array();

        $path_email = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $id;

        $files = PhpDirectory::getDirectory($path_email);

        //echo CVarDumper::dump($files,10,true);

        foreach ($files as $key => $file) {
            $root = Yii::getPathOfAlias('webroot');
            $path = str_replace($root, '', $file);
            $out[$key]['id'] = $key;
            $out[$key]['path'] = $path;
            $out[$key]['name'] = pathinfo($file, PATHINFO_BASENAME);
            $info = stat($file);
            $out[$key]['date'] = date('d.m.Y H:i:s', $info['mtime']);

        }

        $dataProvider = new CArrayDataProvider($out, array(
            'id'=>'files',
            'sort'=>array(
                'attributes'=>array(
                    'id', 'name', 'date',
                ),
            ),
            'pagination'=>array(
                'pageSize'=>20,
            ),
        ));

        $this->render('loaded_files', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionRuleFiles($id)
    {
        $rule = PricesFtpSourcesRules::model()->findByPk($id);
        $out = array();

        $path_email = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $rule->mail_id;

        foreach (glob("$path_email/*-{$id}-*") as $key => $file) {
            $root = Yii::getPathOfAlias('webroot');
            $path = str_replace($root, '', $file);
            $out[$key]['id'] = $key;
            $out[$key]['path'] = $path;
            $out[$key]['name'] = pathinfo($file, PATHINFO_BASENAME);
            $info = stat($file);
            $out[$key]['date'] = date('d.m.Y H:i:s', $info['mtime']);
        }
        //echo CVarDumper::dump($out,10,true);exit;

        $dataProvider = new CArrayDataProvider($out, array(
            'id'=>'files',
            'sort'=>array(
                'attributes'=>array(
                    'id', 'name', 'date',
                ),
            ),
            'pagination'=>array(
                'pageSize'=>20,
            ),
        ));

        $this->render('loaded_files', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Удалить все файлы загруженные этим ящиком
     * @param $id
     */
    public function actionDeleteAllFiles($id)
    {
        $errors = 0;

        $model = PricesFtpAutoloadMailboxes::model()->findByPk($id);

        $sources = $model->sources;

        $path_email = realpath(Yii::app()->basePath . '/../upload_files/email') . '/' . $id;
        $files = PhpDirectory::getDirectory($path_email);

        foreach ($files as $file) {
            if(is_file($file)){
                //Use the unlink function to delete the file.
                if(!unlink($file)){
                    $errors++;
                }
            }
        }

        if(!$errors){
            if($sources){
                foreach ($sources as $source) {
                    PricesAutoloadQueue::model()->deleteAllByAttributes(array(
                        'rule_id'=>$source->id
                    ));
                }
            }

            $model->download_count = 0;
            $model->download_time = 0;
            $model->save();
        }else{
            echo $errors; exit;
        }

        Yii::app()->user->setFlash('success', 'Все файлы успешно удалены!');

        $this->redirect(Yii::app()->request->urlReferrer);
    }


    public function actionCheckNow($id) {
        $model = $this->loadModel($id);
        BackgroundProcess::launchBackgroundProcessStart('php ' . realpath(Yii::app()->basePath) . '/yiic.php MailBoxLoad ' . $id);
        $this->render('start', array('model' => $model));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = PricesFtpAutoloadMailboxes::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'prices-ftp-autoload-mailboxes-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
