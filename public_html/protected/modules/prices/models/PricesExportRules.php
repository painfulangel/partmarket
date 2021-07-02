<?php
/**
 * This is the model class for table "prices_export_rules".
 *
 * The followings are the available columns in table 'prices_export_rules':
 * @property integer $id
 * @property string $ftp_server
 * @property string $ftp_login
 * @property string $ftp_password
 * @property integer $ftp_auth_type
 * @property string $ftp_destination_folder
 * @property string $email
 * @property integer $active_state
 * @property string $load_period_hours
 * @property string $load_period_days
 * @property string $load_period_minutes
 * @property integer $download_count
 * @property string $download_time
 * @property string $rule_name
 * @property integer $cron_general
 * @property integer $price_group
 * @property integer $email_send
 * @property integer $ftp_send
 * @property integer $human_machine 
 */
class PricesExportRules extends CMyActiveRecord {
    public $_stores = array();
    public $_ftp_password;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'prices_export_rules';
    }

    public function afterFind() {
        parent::afterFind();
        $this->_ftp_password = $this->ftp_password;
        $this->ftp_password = '';
        $this->initStores();
    }

    public function initStores() {
        $this->_stores = array();
        $rows = Yii::app()->db->createCommand("SELECT `id`, `name`, `supplier`, `supplier_inn`, `delivery`, (SELECT '1' FROM `prices_export_rules_stores` WHERE `rule_id`='$this->id' AND store_id=t.id  LIMIT 1) as `row_data`, (SELECT id FROM `prices_export_rules_stores` WHERE `rule_id`='$this->id' AND store_id=t.id  LIMIT 1) as `row_id` FROM `stores` `t` ")->queryAll();
        foreach ($rows as $row) {
            $this->_stores[$row['id']] = array(
                'name' => $row['name'],
                'supplier' => $row['supplier'],
                'supplier_inn' => $row['supplier_inn'],
                'id' => $row['id'],
                'row_data' => $row['row_data'],
                'delivery' => $row['delivery'],
                'row_id' => $row['row_id'],
//                'row_value' => (''),
//                '' => $row[''],
            );
        }
    }

    public function getAllCDataProvider() {
        if (count($this->_stores) == 0)
            $this->initStores();
        return new CArrayDataProvider($this->_stores, array(
            'pagination' => array(
                'pageSize' => 999,
            ),
        ));
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('rule_name, cron_general', 'required'),
            array('ftp_auth_type, active_state, download_count, cron_general, price_group, email_send, ftp_send, human_machine, create_common, type_price_delivery, srok_more, srok_days, sklad_otd', 'numerical', 'integerOnly' => true),
            array('ftp_server, ftp_login, ftp_password, rule_name', 'length', 'max' => 127),
            array('ftp_destination_folder, email', 'length', 'max' => 255),
            array('load_period_hours, load_period_days, load_period_minutes', 'length', 'max' => 32),
            array('download_time', 'length', 'max' => 20),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, ftp_server, ftp_login, ftp_password, ftp_auth_type, ftp_destination_folder, email, active_state, load_period_hours, load_period_days, load_period_minutes, download_count, download_time, rule_name, cron_general, price_group, email_send, ftp_send, create_common, type_price_delivery, srok_more, srok_days, sklad_otd', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if (empty($this->ftp_destination_folder)) {
            $this->ftp_destination_folder = '/';
        }
        if (!$this->isNewRecord) {
            if (empty($this->ftp_password)) {
                $this->ftp_password = $this->_ftp_password;
            }
        }

        if (parent::beforeSave()) {
            $minutes = array(
                '1' => '*/05',
                '2' => '*/30',
                '3' => str_pad(rand(1, 48), 2, '0', STR_PAD_LEFT),
                '4' => str_pad(rand(10, 55), 2, '0', STR_PAD_LEFT),
                '5' => str_pad(rand(1, 35), 2, '0', STR_PAD_LEFT),
                '6' => str_pad(rand(5, 55), 2, '0', STR_PAD_LEFT),
                '7' => str_pad(rand(3, 55), 2, '0', STR_PAD_LEFT),
                '8' => str_pad(rand(1, 50), 2, '0', STR_PAD_LEFT),
                '9' => str_pad(rand(1, 45), 2, '0', STR_PAD_LEFT),
            );
            $hours = array(
                '1' => '*',
                '2' => '*',
                '3' => '*/01',
                '4' => '*/03',
                '5' => '*/06',
                '6' => '*/12',
                '7' => '*',
                '8' => '*',
                '9' => '*',
            );
            $days = array(
                '1' => '*',
                '2' => '*',
                '3' => '*',
                '4' => '*',
                '5' => '*',
                '6' => '*',
                '7' => '*/01',
                '8' => '*/02',
                '9' => '*/03',
            );
            if (!isset($days[$this->cron_general]))
                $this->cron_general = 2;

            $this->load_period_days = $days[$this->cron_general];
            $this->load_period_hours = $hours[$this->cron_general];
            $this->load_period_minutes = $minutes[$this->cron_general];

            return true;
        }
        return false;
    }

    public function getId() {
        return $this->id;
    }

    public function afterSave() {
        parent::afterSave();
        if ($this->scenario != 'saveDone') {
            $delete_ids = array();
            $insert_command = Yii::app()->db->createCommand('INSERT INTO `prices_export_rules_stores`( `rule_id`, `store_id`) VALUES ( :rule_id, :store_id)');
            foreach ($this->_stores as $value) {
                if (empty($value['row_data']) && !empty($value['row_id'])) {
                    $delete_ids[] = " id='$value[row_id]' ";
                } else
                if (!empty($value['row_data']) && empty($value['row_id'])) {
                	$rule_id = $this->getId();
                    $insert_command->bindParam(":rule_id", $rule_id, PDO::PARAM_INT);
                    $insert_command->bindParam(":store_id", $value['id'], PDO::PARAM_STR);
                    $insert_command->execute();
                }
            }
            if (count($delete_ids) > 0)
                Yii::app()->db->createCommand('DELETE FROM `prices_export_rules_stores`  WHERE ' . implode(' OR ', $delete_ids))->query();
            
            if ($this->scenario != 'runCronTab') {
                $cron = new Crontab('my_crontab', Yii::app()->basePath . '/runtime/'); // my_crontab file will store all added jobs
                $jobs_obj = $cron->getJobs();
				//print_r($jobs_obj);
                foreach ($jobs_obj as $key => $value) {
                    $p = $value->getParams();

                    if (isset($p[0])) {
                        $temp_delete_model = PricesExportRules::model()->findByPk($p[0]);
                        if (($temp_delete_model == NULL && $value->getCommandName() == 'PriceExport') || ($value->getCommandName() == 'PriceExport' && $p[0] == $this->id)) {
							//print_r($value);
                            $cron->removeJob($key);
                        }
                    }
                }
                if ($this->active_state == '1') {
                    $job = new CronApplicationJob('protected/yiic', 'PriceExport', array("'datetime"), $this->load_period_minutes, $this->load_period_hours, $this->load_period_days); // run every day
                    $job->setParams(array($this->id));
                    $cron->add($job);
                }
                $cron->saveCronFile(); // save to my_crontab cronfile
                $cron->saveToCrontab(); // adds all my_crontab jobs to system (replacing previous my_crontab jobs)
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'pricesExportRulesStores' => array(self::HAS_MANY, 'PricesExportRulesStores', 'rule_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'ftp_server' => Yii::t('prices', 'Server address'),
            'ftp_login' => Yii::t('prices', 'login'),
            'ftp_password' => Yii::t('prices', 'Password'),
            'ftp_auth_type' => Yii::t('prices', 'Entrance type on the server'),
            'ftp_destination_folder' => Yii::t('prices', 'Folder on the server'),
            'email' => Yii::t('prices', 'Email'),
            'active_state' => Yii::t('prices', 'Included'),
            'load_period_days' => Yii::t('prices', 'Download time  (days)'),
            'load_period_minutes' => Yii::t('prices', 'Download time  (minute)'),
            'load_period_hours' => Yii::t('prices', 'Download time (hour)'),
            'cron_general' => Yii::t('prices', 'Download time '),
            'rule_name' => Yii::t('prices', 'Name a price'),
            'download_time' => Yii::t('prices', 'Time of the last processing'),
            'download_count' => Yii::t('prices', 'Number of unloaded positions'),
            'price_group' => Yii::t('prices', 'Price group'),
            'email_send' => Yii::t('prices', 'Send by Email'),
            'ftp_send' => Yii::t('prices', 'Sending by ftp'),
            'human_machine' => Yii::t('prices', 'The creation of a price for opening in Excel'),
        	
        	'create_common' => Yii::t('prices', 'Create common price list'),
        	'type_price_delivery' => Yii::t('prices', 'Type'),
        	'srok_more' => Yii::t('prices', 'don\'t consider a position if delivery period is more'),
        	'srok_days' => Yii::t('prices', 'don\'t consider a position if delivery period is more'),
        	'sklad_otd' => Yii::t('prices', 'Write warehouse name in separate column'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('ftp_server', $this->ftp_server, true);
        $criteria->compare('ftp_login', $this->ftp_login, true);
        $criteria->compare('ftp_password', $this->ftp_password, true);
        $criteria->compare('ftp_auth_type', $this->ftp_auth_type);
        $criteria->compare('ftp_destination_folder', $this->ftp_destination_folder, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('active_state', $this->active_state);
        $criteria->compare('load_period_hours', $this->load_period_hours, true);
        $criteria->compare('load_period_days', $this->load_period_days, true);
        $criteria->compare('load_period_minutes', $this->load_period_minutes, true);
        $criteria->compare('download_count', $this->download_count);
        $criteria->compare('download_time', $this->download_time, true);
        $criteria->compare('rule_name', $this->rule_name, true);
        $criteria->compare('cron_general', $this->cron_general);
        $criteria->compare('price_group', $this->price_group);
        $criteria->compare('email_send', $this->email_send);
        $criteria->compare('ftp_send', $this->ftp_send);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return PricesExportRules the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}