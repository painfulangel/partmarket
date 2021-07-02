<?php

/**
 * This is the model class for table "prices_ftp_autoload_mailboxes".
 *
 * The followings are the available columns in table 'prices_ftp_autoload_mailboxes':
 * @property integer $id
 * @property string $mailbox
 * @property string $password
 * @property string $pop_adress
 * @property string $pop_port
 * @property string $protocol
 * @property string $imap_address
 * @property string $imap_port
 * @property string $frequency
 * @property string $expire
 * @property integer $delete_old
 * @property integer $just_new
 * @property string $last_update
 * @property integer $cron_general
 * @property string $load_period_hours
 * @property string $load_period_minutes
 * @property string $load_period_days
 * @property integer $state
 * @property integer $download_time
 * @property integer $download_count
 *
 * @property PricesFtpSourcesRules[] $sources
 */
class PricesFtpAutoloadMailboxes extends CMyActiveRecord {

    const POP_PROTOCOL = 'pop';
    const IMAP_PROTOCOL = 'imap';

    const POP_PORT = '110';
    const IMAP_PORT = '993';

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'prices_ftp_autoload_mailboxes';
    }

    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('delete_old, just_new, cron_general', 'numerical', 'integerOnly' => true),
            array('mailbox, password, pop_adress, pop_port', 'length', 'max' => 45),
            array('last_update', 'length', 'max' => 20),
            array('protocol, imap_address, imap_port, frequency, expire', 'length', 'max' => 255),
            array('load_period_days, load_period_hours, load_period_minutes', 'length', 'max' => 32),
            array('id, mailbox, password, pop_adress, pop_port, delete_old, just_new, last_update', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'sources' => array(self::HAS_MANY, 'PricesFtpSourcesRules', 'mail_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'mailbox' => Yii::t('prices', 'Email'),
            'password' => Yii::t('prices', 'Password'),
            'pop_adress' => Yii::t('prices', 'POP Address'),
            'pop_port' => Yii::t('prices', 'POP Port'),
            'mail_subject' => Yii::t('prices', 'In the field the subject of the letter contains'),
            'mail_body' => Yii::t('prices', 'In the text of the letter is contained'),
            'mail_from' => Yii::t('prices', 'Came from what address'),
            'protocol' => Yii::t('prices', 'Mail Protocol'),
            'imap_address' => Yii::t('prices', 'IMAP Address'),
            'imap_port' => Yii::t('prices', 'IMAP Port'),
            'frequency' => Yii::t('prices', 'How often to check mail'),
            'expire' => Yii::t('prices', 'Delete letters - older than X - days'),
            'delete_old' => Yii::t('prices', 'Remove old'),
            'just_new' => Yii::t('prices', 'Only new'),
            'last_update' => Yii::t('prices', 'Time of the last loading'),
            'cron_general' => Yii::t('prices', 'Download time '),
            'state' => Yii::t('prices', 'Included'),
            'download_time' => Yii::t('prices', 'Time of the last processing'),
            'download_count' => Yii::t('prices', 'Count of loaded files'),
        );
    }

    public function beforeSave()
    {
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

        if($this->isNewRecord){
            $commands = array();
            $cron = new Crontab('my_crontab', Yii::app()->basePath . '/runtime/');
            $jobs_obj = $cron->getJobs();
            foreach ($jobs_obj as $item) {
                array_push($commands, $item->getCommandName());
            }

            //Если задача Обработки очереди еще не добавлена в планировщик, добавить
            if(!in_array('HandlerQueuePrice', $commands)){
                //Добавить задачу в кронтаб
                $job = new CronApplicationJob('protected/yiic', 'HandlerQueuePrice', array(), '*/3', '*', '*'); // run every 5 min.
                //$job->setParams(array('999'));
                $cron->add($job);
                // save to my_crontab cronfile
                $cron->saveCronFile();
                // adds all my_crontab jobs to system (replacing previous my_crontab jobs)
                $cron->saveToCrontab();
            }

        }

        return parent::beforeSave();
    }

    public function afterSave()
    {
        if ($this->scenario != 'runCronTab') {
            $cron = new Crontab('my_crontab', Yii::app()->basePath . '/runtime/'); // my_crontab file will store all added jobs
            $jobs_obj = $cron->getJobs();

            foreach ($jobs_obj as $key => $value) {
                $p = $value->getParams();
                if (isset($p[0])) {
                    $temp_delete_model = PricesFtpAutoloadMailboxes::model()->findByPk($p[0]);
                    if (($value->getCommandName() == 'MailBoxLoad' && $temp_delete_model == NULL) || ($value->getCommandName() == 'MailBoxLoad' && $p[0] == $this->id))
                        $cron->removeJob($key);
                }
            }

            if ($this->state == '1') {
                //Добавить задачу в кронтаб
                $job = new CronApplicationJob('protected/yiic', 'MailBoxLoad', array("'datetime"), $this->load_period_minutes, $this->load_period_hours, $this->load_period_days); // run every day
                $job->setParams(array("{$this->id}"));
                $cron->add($job);
            } else {
                //Удалить данные прайса из базы, если бокс не активен
                $db = Yii::app()->db;
                $rows = $db->createCommand('SELECT `id` FROM ' . Prices::model()->tableName() . '  WHERE  `store_id`=' . $this->store_id)->queryAll();
                $ids = array(0);
                foreach ($rows as $value) {
                    $ids[] = '`price_id`=' . $value['id'];
                }
                $ids2 = array(0);
                foreach ($rows as $value) {
                    $ids2[] = '`id`=' . $value['id'];
                }

                $dataModel = new PricesData;
                $db->createCommand('DELETE FROM ' . $dataModel->tableName() . '  WHERE  ' . implode(' OR ', $ids))->query();
                $db->createCommand('DELETE FROM ' . Prices::model()->tableName() . '  WHERE  ' . implode(' OR ', $ids2))->query();
            }

            $cron->saveCronFile(); // save to my_crontab cronfile
            $cron->saveToCrontab(); // adds all my_crontab jobs to system (replacing previous my_crontab jobs)
        }

        parent::afterSave();
    }

    public function beforeDelete()
    {
        //Удалить задачу из планировщика
        $cron = new Crontab('my_crontab', Yii::app()->basePath . '/runtime/'); // my_crontab file will store all added jobs
        $jobs_obj = $cron->getJobs();

        foreach ($jobs_obj as $key => $value) {
            $p = $value->getParams();
            if (isset($p[0])) {

                if ($value->getCommandName() == 'MailBoxLoad' && $this->id == $p[0]){
                    $cron->removeJob($key);
                    $cron->saveCronFile();
                    $cron->saveToCrontab();
                }
            }
        }

        return parent::beforeDelete();
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
        $criteria->compare('mailbox', $this->mailbox, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('pop_adress', $this->pop_adress, true);
        $criteria->compare('pop_port', $this->pop_port, true);
        $criteria->compare('delete_old', $this->delete_old);
        $criteria->compare('just_new', $this->just_new);
        $criteria->compare('last_update', $this->last_update, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @return string
     */
    public function getConsoleProtocol()
    {
        if($this->protocol == self::POP_PROTOCOL){
            return 'pop3';
        }

        if($this->protocol == self::IMAP_PROTOCOL){
            return 'imap';
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return PricesFtpAutoloadMailboxes the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getMailProtocols()
    {
        return array(
            self::POP_PROTOCOL => 'POP',
            self::IMAP_PROTOCOL => 'IMAP',
        );
    }

    public static function getMailExpire()
    {
        $out = array(0=>Yii::t('prices', 'Not delete'));

        for ($i=1;$i<=10;$i++){
            $out[$i] = $i.' '.Yii::t('prices', 'expire_day', array($i));
        }
        return $out;
    }

    public static function getCronFrequency()
    {
        return array(
            '3' => Yii::t('prices', 'Everyone hour'),
            '4' => Yii::t('prices', 'Each 3 hours'),
            '5' => Yii::t('prices', 'Each 6 hours'),
            '6' => Yii::t('prices', 'Each 12 hours'),
            '7' => Yii::t('prices', 'Every day'),
            '8' => Yii::t('prices', 'each 2 days'),
            '9' => Yii::t('prices', 'each 3 days'),
        );
    }

    public function getUploadedFiles()
    {
        if(!$this->download_time){
            return '<span class="badge" style="background:red; color: #fff;">'.$this->download_count.'</span>';
        }else{

            $date1 = date_create(date('Y-m-d', $this->download_time));
            $date2 = date_create(date('Y-m-d', time()));
            $diff = date_diff($date1,$date2);

            $link = '/prices/adminMailboxes/loadedFiles/?id='.$this->id;

            if($diff->d <= 1){
                return CHtml::link('<span class="badge" style="background:#126303; color: #fff;">'.$this->download_count.'</span>', $link, array('title'=>'Посмотреть список файлов'));
            } elseif ($diff->d >= 1 && $diff->d <= 2){
                return CHtml::link('<span class="badge" style="background:#126303; color: #fff;">'.$this->download_count.'</span>', $link, array('title'=>'Посмотреть список файлов'));
            } elseif ($diff->d >= 2 && $diff->d <= 3){
                return CHtml::link('<span class="badge" style="background:#54e008">'.$this->download_count.'</span>', $link, array('title'=>'Посмотреть список файлов'));
            } elseif ($diff->d >= 3 && $diff->d <= 5){
                return CHtml::link('<span class="badge" style="background:#cfdd04">'.$this->download_count.'</span>', $link, array('title'=>'Посмотреть список файлов'));
            } elseif ($diff->d >= 5 && $diff->d <= 7){
                return CHtml::link('<span class="badge" style="background:#e5b104">'.$this->download_count.'</span>', $link, array('title'=>'Посмотреть список файлов'));
            } elseif ($diff->d >= 7){
                return CHtml::link('<span class="badge" style="background:#ef3f04; color: #fff;">'.$this->download_count.'</span>', $link, array('title'=>'Посмотреть список файлов'));
            }
        }

    }
}
