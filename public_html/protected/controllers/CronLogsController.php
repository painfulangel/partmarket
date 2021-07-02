<?php
class CronLogsController extends Controller {
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
                'actions' => array('admin', 'clearSystem', 'help', 'catalogs'),
                'roles' => array('siteSettings'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('clearFinish'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionHelp() {
        $this->render('help');
    }

    public function actionCatalogs() {
        $this->render('catalogs');
    }

    public function actionClearFinish() {
        $this->render('clearFinish');
    }

    public function actionClearSystem() {
        if (isset($_POST['password']) && $_POST['password'] == 'plJU78yvUJrt67TBdrewq') {
            set_time_limit(300);
            if (isset($_POST['users_ids'])) {
                $_POST['users_ids'] = explode(',', $_POST['users_ids']);
            } else {
                $_POST['users_ids'] = array(1);
            }
            Yii::app()->db->createCommand("DELETE FROM `gallery_photo`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `gallery_photo` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `gallery`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `gallery` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `katalog_vavto_items2`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `katalog_vavto_items2` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `katalog_vavto_items`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `katalog_vavto_items` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `katalog_vavto_cathegorias`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `katalog_vavto_cathegorias` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `katalog_vavto_cars`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `katalog_vavto_cars` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `katalog_vavto_brands`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `katalog_vavto_brands` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `katalog_accessories_cathegorias`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `katalog_accessories_cathegorias` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `katalog_accessories_items`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `katalog_accessories_items` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `stores`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `stores` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `prices_export_rules`");
            Yii::app()->db->createCommand("ALTER TABLE  `prices_export_rules` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `prices_export_rules_stores`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `prices_export_rules_stores` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `prices_ftp_autoload_rules`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `prices_ftp_autoload_rules` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `prices_rules`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `prices_rules` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `prices_rules_groups`")->query();
            Yii::app()->db->createCommand("ALTER TABLE  `prices_rules_groups` AUTO_INCREMENT =1")->query();

            Yii::app()->db->createCommand("DELETE FROM `lily_user` WHERE `uid` NOT IN (" . implode(' , ', $_POST['users_ids']) . ")")->query();
            $value = Yii::app()->db->createCommand("(SELECT MAX(`uid`) FROM `lily_user` LIMIT 1)")->queryScalar();
            Yii::app()->db->createCommand("ALTER TABLE  `lily_user` AUTO_INCREMENT =$value")->query();


            Yii::app()->db->createCommand("DELETE FROM `lily_account` WHERE `uid` NOT IN (" . implode(' , ', $_POST['users_ids']) . ")")->query();
            $value = Yii::app()->db->createCommand("(SELECT MAX(`aid`) FROM `lily_account` LIMIT 1)")->queryScalar();
            Yii::app()->db->createCommand("ALTER TABLE  `lily_account` AUTO_INCREMENT =$value")->query();


            Yii::app()->db->createCommand("DELETE FROM `user_profile` WHERE `uid` NOT IN (" . implode(' , ', $_POST['users_ids']) . ")")->query();
            $value = Yii::app()->db->createCommand("(SELECT MAX(`id`) FROM `user_profile` LIMIT 1)")->queryScalar();
            Yii::app()->db->createCommand("ALTER TABLE  `user_profile` AUTO_INCREMENT =$value")->query();

            Yii::app()->db->createCommand("DELETE FROM `authassignment` WHERE `userid` NOT IN (" . implode(' , ', $_POST['users_ids']) . ")")->query();

            Yii::app()->db->createCommand("TRUNCATE `prices`")->query();
            Yii::app()->db->createCommand("TRUNCATE `prices_data`")->query();
            Yii::app()->db->createCommand("TRUNCATE `shop_products`")->query();
            Yii::app()->db->createCommand("TRUNCATE `user_balance_operations`")->query();
            Yii::app()->db->createCommand("TRUNCATE `web_payments`")->query();
            Yii::app()->db->createCommand("TRUNCATE `web_payments_robokassa`")->query();
            Yii::app()->db->createCommand("TRUNCATE `web_payments_yandex`")->query();
            Yii::app()->db->createCommand("TRUNCATE `users_cars`")->query();
            Yii::app()->db->createCommand("TRUNCATE `users_api_access`")->query();
            Yii::app()->db->createCommand("TRUNCATE `orders`")->query();
            Yii::app()->db->createCommand("TRUNCATE `items`")->query();
            Yii::app()->db->createCommand("TRUNCATE `cron_logs`")->query();
            Yii::app()->db->createCommand("TRUNCATE `parsers_api`")->query();
            Yii::app()->db->createCommand("TRUNCATE `parsers_api_all`")->query();
            Yii::app()->db->createCommand("TRUNCATE `lily_session`")->query();
            Yii::app()->db->createCommand("TRUNCATE `lily_email_account_activation`")->query();
            Yii::app()->db->createCommand("TRUNCATE `lily_onetime`")->query();
            Yii::app()->db->createCommand("TRUNCATE `reliability`")->query();
            Yii::app()->db->createCommand("TRUNCATE `request_get_price`")->query();
            Yii::app()->db->createCommand("TRUNCATE `request_vin`")->query();
            Yii::app()->db->createCommand("TRUNCATE `request_wu`")->query();
            Yii::app()->db->createCommand("TRUNCATE `pages_left`")->query();
            Yii::app()->db->createCommand("TRUNCATE `pages_top`")->query();
            Yii::app()->db->createCommand("TRUNCATE `menus`")->query();
            Yii::app()->db->createCommand("TRUNCATE `news`")->query();
            Yii::app()->db->createCommand("TRUNCATE `feedbacks`")->query();

            $cron = new Crontab('my_crontab', Yii::app()->basePath . '/runtime/'); // my_crontab file will store all added jobs
            $jobs_obj = $cron->getJobs();
            foreach ($jobs_obj as $key => $value) {
                $p = $value->getParams();
                if (isset($p[0])) {
                    $temp_delete_model = PricesExportRules::model()->findByPk($p[0]);
                    if ($temp_delete_model != NULL) {
                        $cron->removeJob($key);
                    }
                    $temp_delete_model = PricesFtpAutoloadRules::model()->findByPk($p[0]);
                    if ($temp_delete_model != NULL) {
                        $cron->removeJob($key);
                    }
                }
            }
            $cron->saveCronFile(); // save to my_crontab cronfile
            $cron->saveToCrontab(); // adds all my_crontab jobs to system (replacing previous my_crontab jobs)
//        Yii::app()->db->createCommand("TRUNCATE `lily_session`")->query();
            @unlink(realpath(Yii::app()->basePath) . '/runtime/cache');
            @unlink(realpath(Yii::app()->basePath) . '/runtime/HTML');
            @unlink(realpath(Yii::app()->basePath) . '/runtime/state.bin');
            $this->redirect(array('clearFinish'));
        } else {
            $this->render('clearSystem');
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new CronLogs('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CronLogs']))
            $model->attributes = $_GET['CronLogs'];

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
        $model = CronLogs::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cron-logs-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}