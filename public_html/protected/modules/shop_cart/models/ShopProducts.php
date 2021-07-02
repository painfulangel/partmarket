<?php
/**
 * This is the model class for table "shop_products".
 *
 * The followings are the available columns in table 'shop_products':
 * @property integer $product_id
 * @property string $description
 * @property string $price
 * @property string $language
 * @property string $specifications
 * @property string $brand
 * @property integer $currency
 * @property string $price_echo
 * @property integer $quantum
 * @property string $delivery
 * @property string $article
 * @property string $article_order
 * @property string $supplier_inn
 * @property string $supplier
 * @property string $store
 * @property string $name
 * @property string $quantum_all
 * @property string $store_count_state
 * @property string $price_data_id
 * @property string $weight 
 * @property float $supplier_price
 * @property integer $price_group_1
 * @property integer $price_group_2
 * @property integer $price_group_3
 * @property integer $price_group_4
 *
 * The followings are the available model relations:
 * @property ShopImage[] $shopImages
 */
class ShopProducts extends CMyActiveRecord {
    public $price_total;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'shop_products';
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord)
                $this->supplier_price/=Yii::app()->params['MultiKoefSuplierPrice'];

            return true;
        }
        return false;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('currency, quantum, price_group, uid, store_id, parser_id', 'numerical', 'integerOnly' => true),
            array('price, price_purchase', 'length', 'max' => 45),
            array('supplier_price,price_group_1,price_group_2,price_group_3,price_group_4', 'safe'),
            array('brand, price_echo, price_purchase_echo, delivery, article, article_order, supplier_inn, supplier, store, name, go_link', 'length', 'max' => 255),
            array('description, quantum_all, store_count_state, price_data_id, weight', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('product_id, description, price, price_purchase, language, specifications, brand, currency, price_echo, price_purchase_echo, quantum, delivery, article, article_order, supplier_inn, supplier, store, name, price_group, uid, store_id, parser_id', 'safe', 'on' => 'search'),
        );
    }

    public function afterSave() {
        parent::afterSave();
        if ($this->store_count_state == 1) {
            $model = PricesData::model()->findByPk($this->price_data_id);
            if ($model != null) {
                $model->quantum-=$this->quantum;
                $model->save();
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
        	'shopImages' => array(self::HAS_MANY, 'ShopImage', 'product_id'),
        	'sklad' => array(self::HAS_ONE, 'Stores', array('id' => 'store_id')),
            'parser' => array(self::HAS_ONE, 'ParsersApi', array('id' => 'parser_id')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'product_id' => 'Product',
            'language' => 'Language',
            'specifications' => 'Specifications',
            'currency' => Yii::t('shop_cart', 'Currency'),
            'quantum_all' => Yii::t('shop_cart', 'In storage'),
            'description' => Yii::t('shop_cart', 'Comment'),
            'brand' => Yii::t('shop_cart', 'Brand'),
            'price' => Yii::t('shop_cart', 'Price'),
            'price_total' => Yii::t('shop_cart', 'Amount'),
            'quantum' => Yii::t('shop_cart', 'Number'),
            'article' => Yii::t('shop_cart', 'Original number'),
            'article_order' => Yii::t('shop_cart', 'Original number'),
            'price_echo' => Yii::t('shop_cart', 'Price'),
            'delivery' => Yii::t('shop_cart', 'Delivery time'),
            'supplier_inn' => Yii::t('shop_cart', 'Supplier TIN'),
            'supplier' => Yii::t('shop_cart', 'Supplier'),
        	'store' => Yii::t('shop_cart', 'Storage'),
        	'store_id' => Yii::t('shop_cart', 'Storage'),
            'name' => Yii::t('shop_cart', 'Name'),
            'weight' => Yii::t('shop_cart', 'Weight'),
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
    
        $module = Yii::app()->controller->module;
        if (!is_object($module) || (get_class($module) != 'Shop_cartModule')) {
        	$module = Yii::app()->getModule('shop_cart');
        }
        
        $cart = $module->getCartContent();
        $products = array(0);
        if ($cart && count($cart) > 0)
            foreach ($cart as $position => $product) {
                $products[] = 't.product_id=\'' . $product['product_id'] . '\'';
            }
        $criteria->condition = implode(' or ', $products);
        $criteria->select.=', (t.price*t.quantum) AS price_total ';

//        $criteria->compare('product_id', $this->product_id);
//        $criteria->compare('description', $this->description, true);
//        $criteria->compare('price', $this->price, true);
//        $criteria->compare('language', $this->language, true);
//        $criteria->compare('specifications', $this->specifications, true);
//        $criteria->compare('brand', $this->brand, true);
//        $criteria->compare('currency', $this->currency);
//        $criteria->compare('price_echo', $this->price_echo, true);
//        $criteria->compare('quantum', $this->quantum);
//        $criteria->compare('delivery', $this->delivery, true);
//        $criteria->compare('article', $this->article, true);
//        $criteria->compare('article_order', $this->article_order, true);
//        $criteria->compare('supplier_inn', $this->supplier_inn, true);
//        $criteria->compare('supplier', $this->supplier, true);
//        $criteria->compare('store', $this->store, true);
//        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('attributes' => array(
                    'price_total' => array(
                        'asc' => 'price_total ASC',
                        'desc' => 'price_total DESC',
                    ),
                    '*',
                ),
            ),
            'pagination' => array(
                'pageSize' => $module->perPage,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return ShopProducts the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}