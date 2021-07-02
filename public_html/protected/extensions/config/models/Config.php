<?php

/**
 * This is the model class for table "config".
 *
 * The followings are the available columns in table 'config':
 * @property string $id
 * @property string $param
 * @property string $value
 * @property string $default
 * @property string $label
 * @property string $type
 * @property string $description
 */
class Config extends CMyActiveRecord
{

    public static $base_data = null;

    public function getBaseData()
    {
        if (self::$base_data == NULL) {
            self::$base_data = Yii::app()->db->createCommand('SELECT * FROM `' . Config::model()->tableName() . '` WHERE `enable_state`=\'1\' ORDER BY `group`, `param`')->queryAll();
        }
        return self::$base_data;
    }

    public function getTranslatedFields()
    {
        return array(
            'value' => 'text',
            //            '' => 'string',
        );
    }

    public function getTranslatedData()
    {
        $db = Yii::app()->db;
        $data_base = $this->getBaseData();
        if (empty($this->load_lang))
            $data = $this->getBaseData();
        else
            $data = $db->createCommand('SELECT * FROM `' . $this->tableName() . '` ')->queryAll();
        $config_data = array();
//        print_r($_POST);
        $flag = false;
        $is_insert = array();

//        if ($data)
        foreach ($data_base as $row) {
            if (isset($_POST['param_' . $this->load_lang . $row['id']])) {
                $flag = true;
            }
        }
        $data_groups = array();

        $sql = 'UPDATE `' . $this->tableName() . "` SET `value`=:value WHERE `id`=:id LIMIT 1";
        $command = Yii::app()->db->createCommand($sql);
        $command_insert = Yii::app()->db->createCommand('INSERT INTO `' . $this->tableName() . "`  (`value`,`id`) VALUES (:value, :id) ");
        foreach ($data_base as $row) {
            $data_groups[$row['id']] = $row['group'];
            if (!isset($config_data[$row['group']]))
                $config_data[$row['group']] = array();
            $config_data[$row['group']][$row['id']] = array(
                'value' => $row['value'],
                'help_title' => $row['help_title'],
                'type' => $row['type'],
                'label' => $row['label'],
                'description' => $row['description'],
            );
        }
        foreach ($data as $row) {
            if (!empty($row['value']))
                $config_data[$data_groups[$row['id']]][$row['id']]['value'] = $row['value'];
            $is_insert[$row['id']] = true;
        }
        foreach ($data_base as $row) {
            if (!isset($config_data[$row['group']]))
                $config_data[$row['group']] = array();
            $value = $config_data[$row['group']][$row['id']]['value'];
            if ($flag) {
                if (isset($_POST['param_' . $this->load_lang . $row['id']])) {
                    $value = $_POST['param_' . $this->load_lang . $row['id']];
                } elseif (!empty($value)) {
                    $value = '';
                }
                if (isset($is_insert[$row['id']])) {
                    $command->bindParam(":id", $row['id'], PDO::PARAM_STR);
                    $command->bindParam(":value", $value, PDO::PARAM_STR);
                    $command->execute();
                } else {
                    $command_insert->bindParam(":id", $row['id'], PDO::PARAM_STR);
                    $command_insert->bindParam(":value", $value, PDO::PARAM_STR);
                    $command_insert->execute();
                }
            }
            $config_data[$row['group']][$row['id']]['value'] = $value;
        }
        return $config_data;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'config' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('value', 'safe'),
            array('value, help_title, group, param, default, label, type, description, enable_state', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    public static function getGroupList()
    {
        return array(
            'Site' => Yii::t('config', 'Basic Settings'),
            '1C' => Yii::t('config', 'Settings 1С accounting software'),
            'KatalogAccessories' => Yii::t('config', 'Catalog accessory'),
            'KatalogVavto' => Yii::t('config', 'My catalog'),
            'ParserApi' => Yii::t('config', 'Setting parsers/API'),
            'PaymentDocuments.Bill' => Yii::t('config', 'Account Settings (Admin)'),
            'PaymentDocuments.Waybill' => Yii::t('config', 'Settings invoice (Admin)'),
            'PrePay' => Yii::t('config', 'Settings prepayment'),
            'SendSMS' => Yii::t('config', 'Sending SMS (general settings)'),
            'ShopingCart' => Yii::t('config', 'Basket'),
            'StopList' => Yii::t('config', 'Stopping list'),
            'VipSMS' => Yii::t('config', 'Sending SMS (provider VipSMS)'),
            'PaymentDocuments.CustomerBill' => Yii::t('config', 'Invoices'),
			'Laximo' => 'Laximo',
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'param' => Yii::t('config', 'Option'),
            'value' => Yii::t('config', 'Value'),
            'default' => 'Default',
            'label' => Yii::t('config', 'Name'),
            'description' => Yii::t('config', 'Description'),
            'type' => 'Type',
        );
    }

    public function afterSave()
    {
        if (empty($this->load_lang)) {
            $temp = new $this->afterFunction;
            if (!empty($this->afterFunction))
                $temp->run();
        }
        parent::afterSave();
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('param', $this->param, true);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('default', $this->default, true);
        $criteria->compare('label', $this->label, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Config the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
