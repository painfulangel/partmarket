<?php
/**
 * This is the model class for table "web_payments_system".
 *
 * The followings are the available columns in table 'web_payments_system':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property double $commission
 * @property integer $active_state
 * @property string $system_name
 */
class WebPaymentsSystem extends CMyActiveRecord {
    public function getTranslatedFields() {
        return array(
            'name' => 'string',
            'description' => 'text',
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'web_payments_system' . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('active_state, show_balance, show_order, show_prepay', 'numerical', 'integerOnly' => true),
            array('commission', 'numerical'),
            array('description', 'safe'),
            array('name, system_name, system_login, system_password,system_extra_parametr', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, description, commission, active_state, show_balance, show_order, show_prepay, system_name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('webPayments', 'Name'),
            'description' => Yii::t('webPayments', 'Description'),
            'commission' => Yii::t('webPayments', 'Commission'),
            'active_state' => Yii::t('webPayments', 'Status(whether the system is on)'),
        	'show_balance' => Yii::t('webPayments', 'Show on account replenishment'),
            'show_order' => Yii::t('webPayments', 'Show on order\'s payment'),
            'show_prepay' => Yii::t('webPayments', 'Show on order\'s prepayment'),
            'system_name' => Yii::t('webPayments', 'System name'),
            'system_login' => Yii::t('webPayments', 'Login'),
            'system_password' => Yii::t('webPayments', 'Password'),
            'system_extra_parametr' => Yii::t('webPayments', 'Additional parameter'),
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('commission', $this->commission);
        $criteria->compare('active_state', $this->active_state);
        $criteria->compare('system_name', $this->system_name, true);

        $criteria->order = 'sequence ASC';
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return WebPaymentsSystem the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}