<?php
/**
 * This is the model class for table "prices_data".
 *
 * The followings are the available columns in table 'prices_data':
 * @property integer $id
 * @property integer $price_id
 * @property string $name
 * @property string $brand
 * @property double $price
 * @property double $multiply
 * @property integer $quantum
 * @property string $article
 * @property string $original_article
 * @property integer $delivery
 */
class PricesData extends CMyActiveRecord {
    public $rule_id = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'prices_data';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('price_id, name, article, original_article', 'required'),
            array('price_id, quantum, delivery', 'numerical', 'integerOnly' => true),
            array('multiply, price, price2, price3, price4, price_selling, price_selling2, price_selling3, price_selling4', 'numerical'),
            array('name, brand, article, original_article, internal, storage, supplier, category, weight, dimensions', 'length', 'max' => 127),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('multiply, id, price_id, name, brand, price, quantum, article, original_article, delivery, internal, price_selling, price2, price3, price4, price_selling2, price_selling3, price_selling4, storage, supplier, category, weight, dimensions, image, image2d, extra_field1, extra_field2, extra_field3, extra_field4, extra_field5', 'safe', 'on' => 'search'),
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
            'price_id' => Yii::t('prices', 'Price'),
            'multiply' => 'multiply',
            'name' => Yii::t('prices', 'Name'),
            'brand' => Yii::t('prices', 'Brand '),
            'price' =>Yii::t('prices', 'Price') ,
            'quantum' => Yii::t('prices', 'Number'),
            'article' => Yii::t('prices','Original number'),
            'original_article' =>Yii::t('prices', 'Original number') ,
            'delivery' =>Yii::t('prices', 'Delivery date') ,
        	'internal' => Yii::t('prices', 'Internal number'),
        	'price_selling' => Yii::t('prices', 'Selling price'),
        	'price2' => Yii::t('prices', 'Price 2'),
        	'price3' => Yii::t('prices', 'Price 3'), 
        	'price4' => Yii::t('prices', 'Price 4'),
        	'price_selling2' => Yii::t('prices', 'Selling price 2'),
        	'price_selling3' => Yii::t('prices', 'Selling price 3'),
        	'price_selling4' => Yii::t('prices', 'Selling price 4'),
        	'storage' => Yii::t('prices', 'Storage'),
        	'supplier' => Yii::t('prices', 'Supplier'),
        	'category' => Yii::t('prices', 'Category'),
            'weight' => Yii::t('prices', 'Weight'),
        	'dimensions' => Yii::t('prices', 'Dimensions'),
        	'image' => Yii::t('prices', 'Image'),
        	'image2d' => Yii::t('prices', 'Image 2D'),
        	'extra_field1' => Yii::t('prices', 'Extra field1'),
        	'extra_field2' => Yii::t('prices', 'Extra field2'),
        	'extra_field3' => Yii::t('prices', 'Extra field3'),
        	'extra_field4' => Yii::t('prices', 'Extra field4'),
        	'extra_field5' => Yii::t('prices', 'Extra field5'),
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
        $ids = array(0);
        if ($this->rule_id != 0) {
            $temp = Prices::model()->findAllByAttributes(array('rule_id' => $this->rule_id));
            foreach ($temp as $key => $value) {

                $ids[] = ' price_id=\'' . $value->id . '\'';
            }
//            print_r($ids);
            $criteria->addCondition('(' . implode(' OR ', $ids) . ')');
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('price_id', $this->price_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('brand', $this->brand, true);
        $criteria->compare('price', $this->price);
        $criteria->compare('multiply', $this->multiply);
        $criteria->compare('quantum', $this->quantum);
        $criteria->compare('article', $this->article, true);
        $criteria->compare('original_article', $this->original_article, true);
        $criteria->compare('delivery', $this->delivery);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return PricesData the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}