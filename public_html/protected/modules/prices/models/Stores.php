<?php
/**
 * This is the model class for table "stores".
 *
 * The followings are the available columns in table 'stores':
 * @property string $name
 * @property integer $count_state
 * @property string $price_group_1
 * @property string $price_group_2
 * @property string $price_group_3
 * @property string $price_group_4
 * @property string $delivery
 * @property string $supplier_inn
 * @property string $supplier
 * @property string $currency
 * @property integer $auto_delete_state
 * @property integer $search_state
 * @property integer $done_orders
 * @property integer $refuse_orders
 * @property integer $my_available
 * @property string $language
 */
class Stores extends CMyActiveRecord {
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'stores';
    }

    public function init() {
        parent::init();
        $this->auto_delete_state = 1;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('count_state', 'safe'),
            array('name', 'length', 'max' => 127),
            array('done_orders,refuse_orders,price_group_1, price_group_2, price_group_3, price_group_4, auto_delete_state, search_state,  currency, my_available, top, highlight, prepay', 'numerical', 'integerOnly' => true),
            array('supplier_inn', 'length', 'max' => 20),
            array('delivery', 'length', 'max' => 255),
            array('supplier', 'length', 'max' => 45),
            array('language', 'length', 'max' => 10),
        	array('description', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, language, price_group_1, price_group_2, price_group_3, price_group_4, auto_delete_state, delivery, supplier_inn, supplier, name, description, count_state, prepay', 'safe', 'on' => 'search'),
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
            'name' => Yii::t('prices', 'Name'),
        	'description' => Yii::t('prices', 'Description'),
            'count_state' => Yii::t('prices', 'Consider the number of in storage'),
            'search_state' => Yii::t('prices', 'Show price list on the website'),
            'price_group_1' => Yii::t('prices', 'Price group') . ' 1',
            'price_group_2' => Yii::t('prices', 'Price group') . ' 2',
            'price_group_3' => Yii::t('prices', 'Price group') . ' 3',
            'price_group_4' => Yii::t('prices', 'Price group') . ' 4',
            'delivery' => Yii::t('prices', 'Delivery date'),
            'supplier_inn' => Yii::t('prices', 'Supplier INN'),
            'supplier' => Yii::t('prices', 'Supplier'),
            'currency' => Yii::t('prices', 'Currency'),
            'auto_delete_state' => Yii::t('prices', 'When loading new prices - to delete the old'),
            'my_available' => Yii::t('prices', 'My parts'),
        	'top' => Yii::t('prices', 'Top row'),
        	'highlight' => Yii::t('prices', 'Color highlight'),
        	'language' => Yii::t('languages', 'Language'),
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
        $criteria->compare('count_state', $this->count_state);
        
        $criteria->compare('language', $this->language);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return Stores the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}