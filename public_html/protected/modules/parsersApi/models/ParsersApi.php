<?php
/**
 * This is the model class for table "parsers_api".
 *
 * The followings are the available columns in table 'parsers_api':
 * @property integer $id
 * @property string $name
 * @property string $supplier_code
 * @property integer $price_group_1
 * @property integer $price_group_2
 * @property integer $price_group_3
 * @property integer $price_group_4
 * @property integer $active_state
 * @property integer $delivery
 * @property string $supplier_inn
 * @property string $supplier
 * @property string $create_date
 * @property integer $currency
 * @property string $language
 * 
 */
class ParsersApi extends CMyActiveRecord {
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'parsers_api';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, supplier_code, price_group_1, price_group_2, price_group_3, price_group_4', 'required'),
            array('price_group_1, price_group_2, price_group_3, price_group_4, active_state, admin_active_state, top, currency, show_prefix, prepay', 'numerical', 'integerOnly' => true),
            array('name, supplier_code', 'length', 'max' => 127),
            array('delivery', 'length', 'max' => 255),
            array('supplier_inn, create_date', 'length', 'max' => 20),
            array('supplier', 'length', 'max' => 45),
            array('language', 'length', 'max' => 10),
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, language, supplier_code, price_group_1, price_group_2, price_group_3, price_group_4, active_state, delivery, supplier_inn, supplier, create_date, currency, show_prefix, prepay', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord)
                $this->create_date = strtotime(date('d.m.Y'));

            return true;
        } else
            return false;
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
            'name' => Yii::t('parsersApi', 'Name'),
            'parser_class' => Yii::t('parsersApi', 'Class name'),
            'price_group_1' => Yii::t('parsersApi', 'Price group') . ' 1',
            'price_group_2' => Yii::t('parsersApi', 'Price group') . ' 2',
            'price_group_3' => Yii::t('parsersApi', 'Price group') . ' 3',
            'price_group_4' => Yii::t('parsersApi', 'Price group') . ' 4',
            'active_state' => Yii::t('parsersApi', 'Active'),
            'admin_active_state' => Yii::t('parsersApi', 'To enable the issuance'),
        	'top' => Yii::t('parsersApi', 'Top row'),
            'delivery' => Yii::t('parsersApi', 'Delivery date'),
            'supplier_inn' => Yii::t('parsersApi', 'Supplier INN'),
            'supplier' => Yii::t('parsersApi', 'Supplier'),
            'create_date' => Yii::t('parsersApi', 'Creation date'),
            'currency' => Yii::t('parsersApi', 'currency'),
            'supplier_code' => Yii::t('parsersApi', 'System name'),
            'language' => Yii::t('languages', 'Language'),
        	'show_prefix' => Yii::t('parsersApi', 'Show store prefix'),
        	'prepay' => Yii::t('delivery', 'Prepay'),
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
        $criteria->compare('supplier_code', $this->supplier_code, true);
        $criteria->compare('language', $this->language);
        $criteria->compare('price_group_1', $this->price_group_1);
        $criteria->compare('price_group_2', $this->price_group_2);
        $criteria->compare('price_group_3', $this->price_group_3);
        $criteria->compare('price_group_4', $this->price_group_4);
        $criteria->compare('active_state', $this->active_state);
        $criteria->compare('delivery', $this->delivery);
        $criteria->compare('supplier_inn', $this->supplier_inn, true);
        $criteria->compare('supplier', $this->supplier, true);
        $criteria->compare('create_date', @strtotime($this->create_date), true);
        $criteria->compare('currency', $this->currency);
        $criteria->compare('language', $this->language);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return ParsersApi the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getSuppliers() {
        $db = Yii::app()->db;
        $sql = 'SELECT supplier as `supplier`, supplier_inn   as `supplier_inn`  FROM `' . $this->tableName() . '`  ';
        $data = $db->createCommand($sql)->queryAll();
        return $data;
    }
}