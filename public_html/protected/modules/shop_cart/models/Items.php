<?php
/**
 * This is the model class for table "items".
 *
 * The followings are the available columns in table 'items':
 * @property integer $id
 * @property integer $user_id
 * @property integer $order_id
 * @property string $description
 * @property string $price
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
 * @property integer $payed_status
 * @property integer $ic_status
 * @property integer $status
 * @property string $create_date
 * @property float $supplier_price
 * @property integer $price_group_1
 * @property integer $price_group_2
 * @property integer $price_group_3
 * @property integer $price_group_4
 * 
 * 
 */
class Items extends CMyActiveRecord {
    public $price_total = '';
    public $date_from = '';
    public $date_to = '';
    public $duration = 0;
    public $user_search_fio = '';
    public $user_search_phone = '';
    public $user_search_email = '';
    public $user_search_organization_name = '';
    public $user_search_inn = '';

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'items';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, order_id,  quantum, payed_status, ic_status, status, store_id, parser_id', 'numerical', 'integerOnly' => true),
            array('price, price_purchase', 'length', 'max' => 45),
            array('supplier_price,price_group_1,price_group_2,price_group_3,price_group_4', 'safe'),
            array('brand, price_echo, price_purchase_echo, delivery, article, article_order, supplier_inn, supplier, store, name, weight', 'length', 'max' => 255),
            array('create_date', 'length', 'max' => 20),
            array('description, go_link, get_status', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('create_date, date_to, date_from, duration, user_search_fio, user_search_phone, user_search_email, user_search_organization_name, user_search_inn, id, user_id, order_id, get_status, price_total, description, price, price_purchase, brand, price_echo, price_purchase_echo, quantum, delivery, article, article_order, supplier_inn, supplier, store, name, payed_status, ic_status, status, create_date, store_id, parser_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'User'   => array(self::HAS_ONE, 'UserProfile', array('uid' => 'user_id')),
            'sklad'  => array(self::HAS_ONE, 'Stores', array('id' => 'store_id')),
            'parser' => array(self::HAS_ONE, 'ParsersApi', array('id' => 'parser_id')),
            'order'  => array(self::HAS_ONE, 'Orders', array('id' => 'order_id')),
        );
    }

    public function afterSave() {
        parent::afterSave();
        $this->updateOrder(true);
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                if ($this->scenario != '1c') {
                    $this->create_date = time();
                    $this->user_id = UserProfile::getUserActiveId();
                }
                if (empty($this->status))
                    $this->status = 0;
                $this->payed_status = 0;
                $this->ic_status = 0;
                $this->get_status = 0;
            }
            if (empty($this->weight))
                $this->weight = 0;
            return true;
        }
        return false;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'order_id' => Yii::t('shop_cart', 'order ID'),
            'description' => Yii::t('shop_cart', 'Comment'),
            'brand' => Yii::t('shop_cart', 'Brand'),
        	
        	'price_purchase' => Yii::t('shop_cart', 'Price_purchase'),
        	'price_purchase_echo' => Yii::t('shop_cart', 'Price_purchase'),
            'price' =>Yii::t('shop_cart', 'Price') ,
            'price_echo' =>Yii::t('shop_cart', 'Price') ,
            
        	'price_total' => Yii::t('shop_cart', 'Amount'),
            'quantum' =>Yii::t('shop_cart', 'Number') ,
            'article' => Yii::t('shop_cart', 'Original number'),
            'article_order' => Yii::t('shop_cart', 'Original number'),
            'delivery' => Yii::t('shop_cart', 'Delivery time'),
            'supplier_inn' => Yii::t('shop_cart', 'Supplier TIN'),
            'supplier' => Yii::t('shop_cart', 'Supplier'),
            'store' => Yii::t('shop_cart', 'Storage'),
            'name' =>Yii::t('shop_cart', 'Name') ,
            'user_id' => Yii::t('shop_cart', 'UserSi'),
            'ic_status' =>Yii::t('shop_cart', 'Status 1С') ,
            'payed_status' => Yii::t('shop_cart', 'Payment status'),
            'status' =>Yii::t('shop_cart', 'Order status') ,
            'create_date' =>Yii::t('shop_cart', 'Order date') ,
            'weight' => Yii::t('shop_cart', 'Weight'),
            'date_to' =>Yii::t('shop_cart', 'Date to') ,
            'date_from' => Yii::t('shop_cart', 'Date with'),
            'duration' => Yii::t('shop_cart', 'Period'),
            'user_search_fio' =>Yii::t('shop_cart', 'Full Name') ,
            'user_search_phone' => Yii::t('shop_cart', 'Telephone number'),
            'user_search_email' =>Yii::t('shop_cart', 'Email') ,
            'user_search_organization_name' =>Yii::t('shop_cart', 'Organization') ,
            'user_search_inn' => Yii::t('shop_cart', 'TIN'),
            'get_status' =>Yii::t('shop_cart', 'Ordered') ,
            'supplier_checkbox' => Yii::t('shop_cart', 'To order'),
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

        $criteria->with = array('User' => array('together' => true, 'alias' => 'u'));
        $criteria->together = true;

        //    $criteria->select = "*, u.first_name as user_search_fio ";

        if (!empty($this->user_search_fio))
            $criteria->compare('concat(u.second_name,\' \',u.first_name)', $this->user_search_fio, true);
        if (!empty($this->user_search_email))
            $criteria->compare('u.email', $this->user_search_email, true);
        if (!empty($this->user_search_organization_name))
            $criteria->compare('u.organization_name', $this->user_search_organization_name, true);
        if (!empty($this->user_search_inn))
            $criteria->compare('u.organization_inn', $this->user_search_inn, true);
        if (!empty($this->user_search_phone))
            $criteria->compare('concat(u.phone,\' \',u.extra_phone)', $this->user_search_phone, true);

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('order_id', $this->order_id);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('price', $this->price);
        $criteria->compare('brand', $this->brand, true);
        $criteria->compare('price_echo', $this->price_echo);
        $criteria->compare('quantum', $this->quantum);
        $criteria->compare('delivery', $this->delivery);
        $criteria->compare('article', $this->article, true);
        $criteria->compare('article_order', $this->article_order);
        $criteria->compare('supplier_inn', $this->supplier_inn, true);
        $criteria->compare('supplier', $this->supplier, true);
        $criteria->compare('store', $this->store, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('payed_status', $this->payed_status);
        $criteria->compare('ic_status', $this->ic_status);
        $criteria->compare('status', $this->status);
        if (!empty($this->price_total)) {
            $criteria->compare('price*quantum', $this->price_total);
        }
        $criteria->compare('weight', $this->weight, true);
        $criteria->select.=', (t.price*t.quantum) AS price_total ';

        if ($this->get_status != -1 && $this->get_status != '') {
            $criteria->compare('get_status', $this->get_status);
        }

        if ($this->status != 0)
            $criteria->compare('status', $this->status);
        if ($this->date_from != '') {
            $this->date_from = strtotime($this->date_from);
        }
        if (!empty($this->date_to)) {
            $this->date_to = strtotime($this->date_to);
        }
        if (!empty($this->duration)) {
            $this->date_to = strtotime(date('Y-m-d 24:59:59'));
            $this->date_from = strtotime(date('Y-m-d')) - 3600 * 24 * $this->duration;
        }
        if (!empty($this->create_date)) {
            $this->date_from = @strtotime($this->create_date);
            $this->date_to = @strtotime($this->create_date) + 3600 * 24;
        }

        if ($this->date_from != '')
            $criteria->compare('create_date', '>=' . $this->date_from, true);
        if ($this->date_to != '')
            $criteria->compare('create_date', '<=' . $this->date_to, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'status ASC',
                'attributes' => array(
                    'price_total' => array(
                        'asc' => 'price_total ASC',
                        'desc' => 'price_total DESC',
                    ),
                    '*',
                ),
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return Items the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function sendEmailNotification() {
        $model = Orders::model()->findByPk($this->order_id);
        if ($model != NULL)
            $model->sendEmailNotification();
    }

    public function updateOrder() {
        if ($this->scenario != '1c') {
            $model = Orders::model()->findByPk($this->order_id);
            if ($model != NULL) {
                $model->updateOrder();
            }
        }
    }

    public function checkDone() {
        return $this->status == 9 || $this->status == 8 || $this->payed_status == 2 || $this->status >= 2;
    }

    public function isFormEnabled() {
        return ($this->checkDone() || $this->order->confirmed ? '' : 'off');
    }
}