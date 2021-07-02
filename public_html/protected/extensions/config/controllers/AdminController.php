<?php
class AdminController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/admin_column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update', 'admin', 'adminTotal'),
                'roles' => array('admin'),
                //'roles' => array('admin','main_manager','manager','simple_manager','text_manager'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionAdminTotal($type = 'Site') {
    	ini_set("display_errors","1");
    	ini_set("display_startup_errors","1");
    	ini_set('error_reporting', E_ALL);
    	
        $db = Yii::app()->db;
        $data = $db->createCommand('SELECT * FROM `'.Config::model()->tableName().'` WHERE `enable_state`=\'1\' GROUP BY `group`')->queryAll();
        $this->admin_subheader = array();
        $groups = Config::getGroupList();
        foreach ($data as $row) {
            $this->admin_subheader[] = array(
                'name' => $groups[$row['group']],
                'url' => array('/config/admin/adminTotal', 'type' => $row['group']),
                'active' => ($type == $row['group'] ? true : false),
            );
        }
        
        $data = $db->createCommand('SELECT * FROM `'.Config::model()->tableName().'` WHERE `enable_state`=\'1\' AND `group`=\''.$type.'\' ORDER BY `group`, `sequence`, `param`')->queryAll();
        $config_data = array();
        
        $flag = false;

        foreach ($data as $temp_key => $row) {
            if (isset($_POST['param_'.$row['id']]) || ($row['type'] == 'checkBox')) {
                $flag = true;
            } else {
				//unset($data[$temp_key]);
            }
        }
        $sql = 'UPDATE `'.Config::model()->tableName()."` SET `value`=:value WHERE `id`=:id LIMIT 1";
        $command = Yii::app()->db->createCommand($sql);
        
        //echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
        
        foreach ($data as $row) {
            if (!isset($config_data[$row['group']]))
                $config_data[$row['group']] = array();
            
            $value = $row['value'];
            
            if ($flag) {
                if (isset($_POST['param_'.$row['id']])) {
                    $value = $_POST['param_'.$row['id']];
                    $command->bindParam(":id", $row['id'], PDO::PARAM_STR);
                    $command->bindParam(":value", $value, PDO::PARAM_STR);
                    $command->execute();
                } else if (($row['type'] == 'checkBox') && array_key_exists('YII_CSRF_TOKEN', $_POST)) {
                	$value = '';
                    $command->bindParam(":id", $row['id'], PDO::PARAM_STR);
                    $command->bindParam(":value", $value, PDO::PARAM_STR);
                    $command->execute();
                }
            }
            
            $config_data[$row['group']][$row['id']] = array(
                'value' => $value,
                'help_title' => $row['help_title'],
                'type' => $row['type'],
                'label' => $row['label'],
                'description' => $row['description'],
            );
        }

        $this->render('admin_total', array(
            'type' => $type,
            'data' => $config_data,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Config'])) {
            $model->attributes = $_POST['Config'];
            if ($model->save())
                $this->redirect(array('admin', 'Config_page' => (isset($_GET['Config_page']) ? $_GET['Config_page'] : '')));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Config('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Config']))
            $model->attributes = $_GET['Config'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Config::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'Запрашиваемая страница не существует.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'config-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}