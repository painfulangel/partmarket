<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table 'orders':
 * @property integer $id
 * @property string $user_id
 * @property integer $ic_status
 * @property integer $payed_status
 * @property integer $status
 * @property double $delivery_cost
 * @property string $zipcode
 * @property string $city
 * @property string $country
 * @property string $street
 * @property string $house
 * @property string $payment_method
 * @property string $delivery_method
 * @property string $description
 * @property double $total_cost
 * @property double $left_pay
 * @property double $total_weight
 * @property boolean $is_trash
 * @property boolean $create_date
 * @property boolean $update_status
 * @property boolean $ic_id
 */
class Orders extends CMyActiveRecord
{

    public $articul = null;

    public $user_model = null;

    public $date_from = '';

    public $date_to = '';

    public $duration = 0;

    public $user_search_fio = '';

    public $user_search_phone = '';

    public $user_search_email = '';

    public $user_search_organization_name = '';

    public $user_search_inn = '';

    // public $pay_redirect = false;
    public $messages;

    public static $CHANGE_STATUS = 0;

    public static $MERGE = 1;

    public static $NEW_ORDER = 2;

    public $pay_order;

    public $credit;

    /**
     *
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'orders';
    }

    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'ic_status, payed_status, status, update_status, is_trash, manager_id, confirmed, courier, prepay, id_delivery_transport, cancelled',
                'numerical',
                'integerOnly' => true
            ),
            array(
                'delivery_cost, total_cost, left_pay, total_weight',
                'numerical'
            ),
            array(
                'user_id',
                'length',
                'max' => 45
            ),
            array(
                'delivery_method',
                'ext.YiiConditionalValidator.YiiConditionalValidator',
                'if' => array(
                    array(
                        'delivery_method',
                        'compare',
                        'compareValue' => Yii::app()->getModule('shop_cart')->delivery_model->PAYMENT_GET_METHOD
                    )
                ),
                'then' => array(
                    array(
                        'street, house',
                        'required'
                    )
                )
            ),
            array(
                'delivery_method',
                'ext.YiiConditionalValidator.YiiConditionalValidator',
                'if' => array(
                    array(
                        'delivery_method',
                        'compare',
                        'compareValue' => Yii::app()->getModule('shop_cart')->delivery_model->POST_METHOD
                    )
                ),
                'then' => array(
                    array(
                        'street, house',
                        'required'
                    )
                )
            ),
            array(
                'delivery_method',
                'ext.YiiConditionalValidator.YiiConditionalValidator',
                'if' => array(
                    array(
                        'delivery_method',
                        'compare',
                        'compareValue' => Yii::app()->getModule('shop_cart')->delivery_model->TRANSPORT_COMPANY
                    )
                ),
                'then' => array(
                    array(
                        'id_delivery_transport, sender_name, sender_phone, passport_data, country_city',
                        'required'
                    )
                )
            ),
            array(
                'zipcode, city, country, street, house,date_to, './*'payment_method, '.*/'delivery_method, description, user_description, create_date, ic_id, sender_name, sender_phone, passport_data, country_city, terminal',
                'length',
                'max' => 255
            ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'create_date, date_to, date_from, duration, user_search_fio, user_search_phone, user_search_email, user_search_organization_name, user_search_inn, id, user_id, ic_status, payed_status, status, delivery_cost, zipcode, city, country, street, house, './*'payment_method, '.*/'delivery_method, description, total_cost, left_pay, total_weight, articul, manager_id, confirmed, courier, prepay, id_delivery_transport, sender_name, sender_phone, passport_data, country_city, terminal',
                'safe',
                'on' => 'search'
            )
        );
    }

    public function beforeSave()
    {
        if (parent::beforeSave()) {
            if (empty($this->create_date))
                $this->create_date = time();
            
            if ($this->scenario != '1c') {
                $this->update_status = 1;
            }
            
            if ($this->isNewRecord) {
                $this->status = 1;
                $this->payed_status = 0;
                $this->ic_status = 0;
                $this->update_status = 1;
                
                if (empty($this->create_date))
                    $this->create_date = time();
                $delivery_name_method1 = Yii::app()->getModule('shop_cart')->delivery_model->PAYMENT_GET_METHOD;
                $delivery_name_method2 = Yii::app()->getModule('shop_cart')->delivery_model->POST_METHOD;
                
                if ($delivery_name_method1 != $this->delivery_method && $delivery_name_method2 != $this->delivery_method) {
                    $this->zipcode = '';
                    $this->city = '';
                    $this->country = '';
                    $this->street = '';
                    $this->house = '';
                }
                
                if ($this->scenario != '1c') {
                    if (! $this->getUser()->checkStopList()) {
                        $this->addError('user_id', 'У вас есть задолженость, вы должны пополнить свой баланс.');
                        return false;
                    }
                }
                
                $this->confirmed = ! (intval(Yii::app()->config->get('Site.CheckOrderBeforePayment')) == 1);
            }
            
            if (empty($this->total_weight))
                $this->total_weight = 0;
            
            $this->delivery_cost = $this->getDeliveryCost();
            
            if ($this->isNewRecord) {
                $this->manager_id = $this->user_model->manager;
            }
            
            if (empty($this->delivery_cost))
                $this->delivery_cost = 0;
            
            $this->getTotalSum();
            
            return true;
        }
        
        return false;
    }
    
    public function getDeliveryCost() {
        $cost = Yii::app()->getModule('shop_cart')->delivery_model->getDeliveryPrice($this->delivery_method);
        
        if ((intval($cost) == 0) && is_object($dt = $this->delivery_transport) && $dt->price) {
            $cost = $dt->price;
        }
        
        return $cost;
    }

    public function get1cFieldList()
    {
        return array(
            'total_cost' => 'Сумма',
            'id' => 'Номер',
            'ic_id' => 'Ид',
            'delivery_adress' => array(
                'house' => 'Дом',
                'street' => 'Улица',
                'city' => 'Город',
                'country' => 'Страна',
                'zipcode' => 'Код'
                // '' => '',
            ),
            'contragents' => array(
                '1c_id' => 'ИД',
                'user_id' => 'СайтИд'
                // '' => '',
                // '' => '',
            ),
            'status' => 'СтатусЗаказа',
            'payed_status' => 'СтатусОплаты',
            'delivery_cost' => 'СуммаДоставки',
            'delivery_method' => 'МетодДоставки',
            // 'payment_method' => 'МетодОплаты',
            'create_date' => 'Дата',
            'items' => array(
                'article_order' => 'Артикул',
                'brand' => 'Бренд',
                // 'supplier' => array(
                // 'supplier' => 'Код',
                // 'supplier_inn' => 'Наименование',
                // // '' => '',
                // ),
                'name' => 'Наименование',
                'price' => 'ЦенаЗаЕдиницу',
                'quantum' => 'Количество'
                // 'summa' => 'Сумма',
                // '' => '',
                // 'ic_id' => 'Ид',
            )
            // '' => '',
            // '' => '',
            // '' => '',
            // '' => '',
        );
    }

    public function exportOrders1c($elements)
    {
        // print_r($elements);
        $result = '<?xml version="1.0" encoding="UTF-8"?>'."\n".' <КоммерческаяИнформация ВерсияСхемы="2.05" ДатаФормирования="'.date('Y-m-d\TH:i:s').'">'."\n";
        
        $data = Yii::app()->db->createCommand("SELECT * FROM `".$this->tableName()."` WHERE update_status='1'")
            ->queryAll();
        Yii::app()->db->createCommand("UPDATE `".$this->tableName()."` SET `update_status`='0' WHERE update_status='1'")
            ->query();
        $status = array(
            '0' => 'Ожидается проверка менеджером',
            '1' => 'Ожидается проверка менеджером',
            '2' => 'Заказано',
            // '5' => 'Частичный резерв',
            '4' => 'Частичный резерв',
            '6' => 'Готов к выдаче',
            '7' => 'Частично выдан',
            '8' => 'Отгружен',
            '9' => 'Отказ'
        );
        $status_items = array(
            // '1' => 'Ожидает оплаты',
            '0' => 'Ожидается проверка менеджером',
            '1' => 'Ожидается проверка менеджером',
            // '4' => 'Заказано',
            // '5' => 'Заказано',
            '2' => 'Заказано',
            // '7' => 'Готов к выдаче',
            '6' => 'Готов к выдаче',
            '8' => 'Выдан',
            '9' => 'Отказ'
        );
        $status_payed = array(
            '0' => 'Принят, ожидание оплаты',
            '1' => 'Частичная оплата',
            '2' => 'Оплачен, в работе'
            // ''=>'',
        );
        foreach ($data as $value) {
            $result .= ' <Документ>'."\n";
            foreach ($elements as $k => $v) {
                if ($k == 'create_date') {
                    $result .= "<$v>".date('YmdHis', $value[$k])."</$v>\n";
                } else if ($k == 'status') {
                    $result .= "<$v>".$status[$value['status']]."</$v>\n";
                } else if ($k == 'payed_status') {
                    $result .= "<$v>".$status_payed[$value['payed_status']]."</$v>\n";
                } else if ($k == 'contragents') {
                    $result .= " <Контрагенты><Контрагент><СайтИд>$value[user_id]</СайтИд></Контрагент></Контрагенты>";
                } else if ($k == 'items') {
                    $result .= '<Товары>';
                    $data_items = Yii::app()->db->createCommand("SELECT * FROM `".Items::model()->tableName()."` WHERE order_id='$value[id]]'")
                        ->queryAll();
                    foreach ($data_items as $value_item) {
                        $result .= '<Товар>';
                        $result .= " <Поставщик><Код>$value_item[supplier_inn]</Код><Наименование>$value_item[supplier]</Наименование></Поставщик>";
                        $result .= ' <Сумма>'.($value_item['price'] * $value_item['quantum']).'</Сумма>';
                        $result .= "<ЗначенияРеквизитов>
					<ЗначениеРеквизита>
						<Наименование>ИД_Сайта</Наименование>
						<Значение>$value_item[id]</Значение>
					</ЗначениеРеквизита>
					<ЗначениеРеквизита>
						<Наименование>ИД_Строки</Наименование>
						<Значение>$value_item[ic_id]</Значение>
					</ЗначениеРеквизита>
					<ЗначениеРеквизита>
						<Наименование>СтатусСтроки</Наименование>
						<Значение>".$status_items[$value_item['status']].'</Значение>
					</ЗначениеРеквизита>
				</ЗначенияРеквизитов>';
                        foreach ($v as $k2 => $v2) {
                            $result .= " <$v2>$value_item[$k2]</$v2>\n";
                        }
                        $result .= '</Товар>';
                    }
                    $result .= '</Товары>';
                    // $result.=" <Контрагенты><Контрагент><СайтИд>$value[user_id]</СайтИд></Контрагент></Контрагенты>";
                } else if ($k == 'delivery_adress') {
                    $result .= ' <АдресДоставки>';
                    foreach ($v as $k2 => $v2) {
                        $result .= "<$v2>$value[$k2]</$v2>";
                    }
                    $result .= '</АдресДоставки>'."\n";
                } else
                    $result .= " <$v>$value[$k]</$v>\n";
            }
            $result .= '</Документ>'."\n";
        }
        $result .= '</КоммерческаяИнформация>';
        return $result;
    }

    /**
     *
     * @param type $file            
     * @return type
     */
    public function import1COrders($file)
    {
        $file = file_get_contents($file);
        $dom = new DOMDocument();
        $dom->loadXML($file);
        $elements = $dom->getElementsByTagName('Документ');
        $elemements_types = $this->get1cFieldList();
        $search = array(
            "\\",
            "\x00",
            "\n",
            "\r",
            "'",
            '"',
            "\x1a"
        );
        $replace = array(
            "\\\\",
            "\\0",
            "\\n",
            "\\r",
            "\'",
            '\"',
            "\\Z"
        );
        $new_ids = array();
        
        foreach ($elements as $element) {
            $elem = XmlWork::getArray($element);
            // print_r($elem);
            
            $data = array();
            foreach ($elemements_types as $key => $value) {
                if ($key == 'create_date') {
                    $data[$key] = strtotime((isset($elem[$value][0]['#text']) ? $elem[$value][0]['#text'] : $elem[$value][0]['#cdata-section']));
                } else if ($key == 'items') {
                    $currentOrder = $this->insertUpdateOrder($data);
                    $temp_items = $elem['Товары'][0]['Товар'];
                    foreach ($temp_items as $elem2) {
                        $data_item = array();
                        if (isset($elem2['ЗначенияРеквизитов'][0]['ЗначениеРеквизита'])) {
                            foreach ($elem2['ЗначенияРеквизитов'][0]['ЗначениеРеквизита'] as $tv => $tk) {
                                if (isset($tk['Наименование'][0]['#text']) && ! empty($tk['Наименование'][0]['#text']) && $tk['Наименование'][0]['#text'] == 'ИД_Сайта')
                                    $data_item['id'] = $tk['Значение'][0]['#text'];
                                if (isset($tk['Наименование'][0]['#text']) && ! empty($tk['Наименование'][0]['#text']) && $tk['Наименование'][0]['#text'] == 'ИД_Строки')
                                    $data_item['ic_id'] = $tk['Значение'][0]['#text'];
                                if (isset($tk['Наименование'][0]['#text']) && ! empty($tk['Наименование'][0]['#text']) && $tk['Наименование'][0]['#text'] == 'СтатусСтроки')
                                    $data_item['status'] = $tk['Значение'][0]['#text']; // !!!!!!!!!!
                            }
                        }
                        if (isset($elem2['Поставщик'][0]['Код'][0]['#text'])) {
                            $data_item['supplier_inn'] = $elem2['Поставщик'][0]['Код'][0]['#text'];
                        }
                        if (isset($elem2['Поставщик'][0]['Наименование'][0]['#text'])) {
                            $data_item['supplier'] = $elem2['Поставщик'][0]['Наименование'][0]['#text'];
                        }
                        foreach ($value as $key2 => $value2) {
                            if (! isset($elem2[$value2]))
                                continue;
                            if (empty($elem2[$value2][0]))
                                continue;
                            $data_item[$key2] = (isset($elem2[$value2][0]['#text']) ? $elem2[$value2][0]['#text'] : $elem2[$value2][0]['#cdata-section']);
                        }
                        $update_flag = $currentOrder->insertUpdateItem($data_item);
                    }
                    $currentOrder->updateOrder($update_flag);
                } else if ($key == 'contragents') {
                    $temp = $elem['Контрагенты'][0]['Контрагент'][0];
                    if (! empty($temp['СайтИД'][0]))
                        $data['user_id'] = (isset($temp['СайтИД'][0]['#text']) ? $temp['СайтИД'][0]['#text'] : $temp['СайтИД'][0]['#cdata-section']);
                    if (! empty($temp['ИД'][0]))
                        $data['1c_id'] = (isset($temp['ИД'][0]['#text']) ? $temp['ИД'][0]['#text'] : $temp['ИД'][0]['#cdata-section']);
                } else if ($key == 'delivery_adress') {
                    $temp = $elem['АдресДоставки'][0];
                    foreach ($value as $key2 => $value2) {
                        if (empty($temp[$value2]))
                            continue;
                        $data[$key2] = (isset($temp[$value2][0]['#text']) ? $temp[$value2][0]['#text'] : $temp[$value2][0]['#cdata-section']);
                    }
                } else {
                    if (! isset($elem[$value]))
                        continue;
                    if (empty($elem[$value][0]))
                        continue;
                    $data[$key] = (isset($elem[$value][0]['#text']) ? $elem[$value][0]['#text'] : $elem[$value][0]['#cdata-section']);
                }
            }
            ;
        }
        
        // return UserProfile::model()->exportUsers1c($result, $elemements_types);
    }

    public function insertUpdateOrder($data)
    {
        $model = NULL;
        if (isset($data['id']) || isset($data['ic_id'])) {
            
            $model = Orders::model()->findByPk(isset($data['id']) ? $data['id'] : 0);
            if ($model == NULL) {
                $model = Orders::model()->findByAttributes(array(
                    'ic_id' => isset($data['ic_id']) ? $data['ic_id'] : 0
                ));
            }
        }
        if ($model == NULL) {
            $model = new Orders();
            $model->scenario = '1c';
            $model->user_id = $this->user_id;
        }
        
        $model->attributes = $data;
        if ($model->payed_status == 'Принят, ожидание оплаты')
            $model->payed_status = 0;
        else if ($model->payed_status == 'Оплачен, в работе')
            $model->payed_status = 2;
        else if ($model->payed_status == 'Частичная оплата')
            $model->payed_status = 1;
        $status = array(
            'Ожидает оплаты' => '1',
            'Заказано' => '2',
            'Готов к выдаче' => '6',
            'Частичный резерв' => '4',
            'Частично выдан' => '7',
            'Выполнен' => '8',
            'Отказ' => '9'
        );
        if (! is_numeric($model->status))
            if (isset($status[$model->status]))
                $model->status = $status[$model->status];
            else {
                $model->status = 2;
            }
        // print_r($model);
        
        $model->save();
        // print_r($model->errors);
        return $model;
    }

    public function insertUpdateItem($data)
    {
        $model = NULL;
        $return_flag = false;
        if (isset($data['id']) || isset($data['ic_id'])) {
            $model == NULL;
            if (isset($data['id'])) {
                $model = Items::model()->findByPk($data['id']);
            }
            if ($model == NULL) {
                $model = Items::model()->findByAttributes(array(
                    'ic_id' => $data['ic_id']
                ));
            }
        }
        if ($model == NULL) {
            $return_flag = true;
            $model = new Items();
            $model->scenario = '1c';
            $model->user_id = $this->user_id;
            $model->create_date = $this->create_date;
            $model->order_id = $this->id;
        }
        
        $model->attributes = $data;
        $model->quantum = intval($model->quantum);
        $model->price = floatval($model->price);
        $status = array(
            'Заказано' => '2',
            'Готов к выдаче' => '6',
            'Выполнен' => '8',
            'Отказ' => '9'
        );
        if (! is_numeric($model->status))
            if (isset($status[$model->status]))
                $model->status = $status[$model->status];
            else {
                $model->status = 2;
            }
        // print_r($model);
        $model->article = preg_replace("/[^a-zA-Z0-9]/", '', $model->article_order);
        $model->price_echo = Yii::app()->getModule('prices')->getPriceFormatFunction($model->price);
        $model->save();
        return $return_flag;
        // print_r($model->errors);
    }

    public function isPrePayOrder()
    {
        $prepaid_flag = false;
        
        /*
         * $cart = Yii::app()->getModule('shop_cart')->getCartContent();
         * if ($cart && count($cart) > 0) {
         * foreach($cart as $position => $product) {
         * $model = ShopProducts::model()->findByPk($product['product_id']);
         *
         * //У склада или парсера должен стоять флаг "Предоплата"
         * if (is_object($model->sklad)) {
         * $prepaid_flag = $model->sklad->prepay == 1;
         * } else if (is_object($model->parser)) {
         * $prepaid_flag = $model->parser->prepay == 1;
         * }
         * }
         * }
         */
        $data = $this->items;
        $count = count($data);
        for ($i = 0; $i < $count; $i ++) {
            $model = $data[$i];
            
            // У склада или парсера должен стоять флаг "Предоплата"
            if (is_object($model->sklad)) {
                $prepaid_flag = $model->sklad->prepay == 1;
            } else if (is_object($model->parser)) {
                $prepaid_flag = $model->parser->prepay == 1;
            }
        }
        
        return $prepaid_flag;
    }

    public function getPrePaySum()
    {
        $prepaid_value = round($this->total_cost * Yii::app()->config->get('PrePay.Percent') / 100, 2);
        
        return $prepaid_value;
    }

    public function afterSave()
    {
        parent::afterSave();
        
        if ($this->isNewRecord) {
            Reliability::addDoneOrder($this->supplier_inn);
        }
        
        if ($this->isNewRecord && $this->scenario != '1c') {
            $cart = Yii::app()->getModule('shop_cart')->getCartContent();
            $prepaid_flag = false;
            
            if ($cart && count($cart) > 0) {
                foreach ($cart as $position => $product) {
                    $model = ShopProducts::model()->findByPk($product['product_id']);
                    if ($model->store_count_state == 1) {
                        $temp = PricesData::model()->findByPk($model->price_data_id);
                        if ($temp != null) {
                            $temp->quantum -= $model->quantum;
                            if ($temp->quantum < 0)
                                $temp->quantum = 0;
                            $temp->save();
                        }
                    }
                    
                    if (intval($model->delivery) > 1) {
                        $prepaid_flag = true;
                    }
                    
                    $item = new Items();
                    $item->attributes = $model->getAttributes(array(
                        'price_purchase',
                        'price_purchase_echo',
                        'price',
                        'price_echo',
                        'brand',
                        'quantum',
                        'delivery',
                        'article',
                        'article_order',
                        'supplier_inn',
                        'supplier',
                        'store',
                        'store_id',
                        'parser_id',
                        'name',
                        'weight',
                        'supplier_price',
                        'price_group_1',
                        'price_group_2',
                        'price_group_3',
                        'price_group_4'
                    ));
                    $item->order_id = $this->id;
                    $item->save();
                }
            }
            
            $db = Yii::app()->db;
            $sql = 'SELECT SUM(t.weight) as `total_weight` FROM `'.Items::model()->tableName().'` `t` '."WHERE order_id='$this->id'";
            $value = $db->createCommand($sql)->queryScalar();
            $this->total_weight = $value;
            $this->delivery_cost = Yii::app()->getModule('shop_cart')->delivery_model->getDeliveryPrice($this->delivery_method, array(
                'weight' => $this->total_weight
            ));
            $this->getTotalSum();
            //$this->isNewRecord = false;
            if ($this->isNewRecord) $this->sendEmailNotification(array(), self::$NEW_ORDER);
            
            /*
             * $prepaid_koef = Yii::app()->request->getPost('prepaid_type', 0.3);
             * $prepaid_value = 0;
             * if ($prepaid_flag) {
             * $prepaid_value = $this->total_cost * $prepaid_koef;
             * }
             *
             * if (Yii::app()->config->get('PrePay.Active') && $prepaid_flag) {
             * $this->getUser()->addMoneyOperation(-$prepaid_value, 'Предоплата по заказу №'.$this->id, $this->id);
             * $this->payed_status = 1;
             * $this->tatal_paid += $prepaid_value;
             * if($this->getUser(true)->balance < 0) {
             * $this->pay_redirect = true;
             * }
             * }
             *
             * if (Yii::app()->config->get('PrePay.Active') && $prepaid_flag || ! $prepaid_flag) {
             * $this->save();
             * }
             */
            
            Yii::app()->getModule('shop_cart')->clearCart();
        }
    }

    /**
     *
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'User' => array(
                self::HAS_ONE,
                'UserProfile',
                array(
                    'uid' => 'user_id'
                )
            ),
            'items' => array(
                self::HAS_MANY,
                'Items',
                array(
                    'order_id' => 'id'
                )
            ),
            'manager' => array(
                self::HAS_ONE,
                'UserProfile',
                array(
                    'uid' => 'manager_id'
                )
            ),
            'delivery' => array(
                self::HAS_ONE,
                'Delivery',
                array(
                    'id' => 'delivery_method'
                ),
            ),
            'delivery_transport' => array(
                self::HAS_ONE,
                'DeliveryTransport',
                array(
                    'id' => 'id_delivery_transport'
                ),
            ),
        );
    }

    public function initUserData($model)
    {
        $this->zipcode = $model->delivery_zipcode;
        $this->city = $model->delivery_city;
        $this->country = $model->delivery_country;
        $this->street = $model->delivery_street;
        $this->house = $model->delivery_house;
        $this->user_id = $model->uid;
    }

    /**
     *
     * @return array customized attribute labels(name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('shop_cart', 'order ID'),
            'user_id' => Yii::t('shop_cart', 'UserSi'),
            'create_date' => Yii::t('shop_cart', 'Order date'),
            'ic_status' => Yii::t('shop_cart', 'Status 1С'),
            'payed_status' => Yii::t('shop_cart', 'Payment status'),
            'status' => Yii::t('shop_cart', 'Order status'),
            'delivery_cost' => Yii::t('shop_cart', 'Shipping cost'),
            'delivery_name' => Yii::t('shop_cart', 'Delivery address'),
            'zipcode' => Yii::t('shop_cart', 'Index'),
            'city' => Yii::t('shop_cart', 'City'),
            'country' => Yii::t('shop_cart', 'Country'),
            'street' => Yii::t('shop_cart', 'Street'),
            'house' => Yii::t('shop_cart', 'House / building / housing'),
            // 'payment_method' => Yii::t('shop_cart', 'Payment method'),
            'delivery_method' => Yii::t('shop_cart', 'Method of getting'),
            'description' => Yii::t('shop_cart', 'Comment(administrator)'),
            'user_description' => Yii::t('shop_cart', 'Comment'),
            'total_cost' => Yii::t('shop_cart', 'Amount'),
            'left_pay' => Yii::t('shop_cart', 'Remaining amount'),
            'prepay' => Yii::t('shop_cart', 'Order prepayment'),
            'weight' => Yii::t('shop_cart', 'Weight'),
            'date_to' => Yii::t('shop_cart', 'Date to'),
            'date_from' => Yii::t('shop_cart', 'Date with'),
            'duration' => Yii::t('shop_cart', 'Period'),
            'user_search_fio' => Yii::t('shop_cart', 'Full Name'),
            'user_search_phone' => Yii::t('shop_cart', 'Telephone number'),
            'user_search_email' => Yii::t('shop_cart', 'Email'),
            'user_search_organization_name' => Yii::t('shop_cart', 'Organization'),
            'user_search_inn' => Yii::t('shop_cart', 'TIN'),
            'articul' => Yii::t('shop_cart', 'Original number'),
            'messages' => Yii::t('menu', 'Messages'),
            'manager_id' => Yii::t('userControl', 'Manager'),
            'confirmed' => Yii::t('userControl', 'Order is confirmed'),
            'pay_order' => Yii::t('shop_cart', 'Pay order'),
            'courier' => Yii::t('shop_cart', 'Payment to the courier'),
            'id_delivery_transport' => Yii::t('delivery', 'Transport company'),
            'sender_name' => Yii::t('shop_cart', 'Sender name'),
            'sender_phone' => Yii::t('shop_cart', 'Sender phone'),
            'passport_data' => Yii::t('shop_cart', 'Series and passport number'),
            'country_city' => Yii::t('shop_cart', 'Country/city of receiving'),
            'terminal' => Yii::t('shop_cart', 'The terminal of receiving or specify the receiving address to a door'),
            'credit' => Yii::t('shop_cart', 'Credit'),
            'cancelled' => Yii::t('shop_cart', 'Cancelled'),
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
     *         based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria();
        
        // if(!empty($this->user_search_phone))
        // $this->user_search_phone = preg_replace("/[^0-9]/", "", $this->user_search_phone);
        
        $criteria->with = array(
            'User' => array(
                'together' => true,
                'alias' => 'u'
            )
        );
        $criteria->together = true;
        
        if (! empty($this->user_search_fio))
            $criteria->compare('concat(u.second_name,\' \',u.first_name)', $this->user_search_fio, true);
        if (! empty($this->user_search_email))
            $criteria->compare('u.email', $this->user_search_email, true);
        if (! empty($this->user_search_organization_name))
            $criteria->compare('u.organization_name', $this->user_search_organization_name, true);
        if (! empty($this->user_search_inn))
            $criteria->compare('u.organization_inn', $this->user_search_inn, true);
        if (! empty($this->user_search_phone))
            $criteria->compare('concat(u.phone,\' \',u.extra_phone)', $this->user_search_phone, true);
        
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.user_id', $this->user_id);
        $criteria->compare('ic_status', $this->ic_status);
        $criteria->compare('payed_status', $this->payed_status);
        
        $criteria->compare('delivery_cost', $this->delivery_cost);
        $criteria->compare('zipcode', $this->zipcode, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('country', $this->country, true);
        $criteria->compare('street', $this->street, true);
        $criteria->compare('house', $this->house, true);
        // $criteria->compare('payment_method', $this->payment_method, true);
        $criteria->compare('delivery_method', $this->delivery_method, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('total_cost', $this->total_cost);
        $criteria->compare('left_pay', $this->left_pay);
        $criteria->compare('total_weight', $this->total_weight);
        
        if ($this->status != 0)
            $criteria->compare('status', $this->status);
        if ($this->date_from != '') {
            $this->date_from = strtotime($this->date_from);
        }
        if (! empty($this->date_to)) {
            $this->date_to = strtotime($this->date_to);
        }
        if (! empty($this->duration)) {
            $this->date_to = strtotime(date('Y-m-d 24:59:59'));
            $this->date_from = strtotime(date('Y-m-d')) - 3600 * 24 * $this->duration;
        }
        if (! empty($this->create_date)) {
            $this->date_from = @strtotime($this->create_date);
            $this->date_to = @strtotime($this->create_date) + 3600 * 24;
        }
        
        if ($this->date_from != '')
            $criteria->compare('create_date', '>='.$this->date_from, true);
        if ($this->date_to != '')
            $criteria->compare('create_date', '<='.$this->date_to, true);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'create_date DESC, status ASC'
            )
        ));
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
     *         based on the search/filter conditions.
     */
    public function userSearch()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria();
        
        if ($this->articul != NULL) {
            $this->articul = preg_replace("/[^a-zA-Z0-9]/", "", $this->articul);
            $criteria->addCondition('(SELECT COUNT(*) FROM `items` WHERE order_id=t.id AND article=\''.$this->articul.'\' LIMIT 1)>0 ');
        }
        
        $criteria->compare('id', $this->id);
        $criteria->compare('payed_status', $this->payed_status);
        
        $criteria->compare('delivery_cost', $this->delivery_cost);
        $criteria->compare('zipcode', $this->zipcode, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('country', $this->country, true);
        $criteria->compare('street', $this->street, true);
        $criteria->compare('house', $this->house, true);
        // $criteria->compare('payment_method', $this->payment_method, true);
        $criteria->compare('delivery_method', $this->delivery_method, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('total_cost', $this->total_cost);
        $criteria->compare('left_pay', $this->left_pay);
        $criteria->compare('total_weight', $this->total_weight);
        if ($this->status > 0)
            $criteria->compare('status', $this->status);
        else if ($this->status < 0)
            $criteria->compare('status', '!='.(- $this->status));
        if ($this->date_from != '') {
            $this->date_from = strtotime($this->date_from);
        }
        if (! empty($this->date_to)) {
            $this->date_to = strtotime($this->date_to);
        }
        if (! empty($this->duration)) {
            $this->date_to = strtotime(date('Y-m-d 24:59:59'));
            $this->date_from = strtotime(date('Y-m-d')) - 3600 * 24 * $this->duration;
        }
        if (! empty($this->create_date)) {
            $this->date_from = @strtotime($this->create_date);
            $this->date_to = @strtotime($this->create_date) + 3600 * 24;
        }
        
        if ($this->date_from != '')
            $criteria->compare('create_date', '>='.$this->date_from, true);
        if ($this->date_to != '')
            $criteria->compare('create_date', '<='.$this->date_to, true);
        
        $criteria->compare('user_id', Yii::app()->user->id);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC'
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     *
     * @param string $className
     *            active record class name.
     * @return Orders the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function sendEmailNotification($clients = array(), $type = 0, $params = array())
    {
        $message = new YiiMailMessage();
        
        $status = new OrdersStatus();
        
        switch ($type) {
            case self::$CHANGE_STATUS:
                $message->setBody($this->getEmailText(), 'text/html');
                $message->setSubject(Yii::t('shop_cart', 'Changing the order status on the website').' '.Yii::app()->config->get('Site.SiteName'));
                $message->addTo($this->getUser()
                    ->getEmail());
                $message->from = Yii::app()->config->get('Site.NoreplyEmail');
                
                $sms = new VipSmsSmsSend();
                $sms->setData(array(
                    '_text' => Yii::t('shop_cart', 'Status zakaza №').$this->id.' '.Yii::t('shop_cart', 'izmemen na').' '.$status->getTranslitName($this->status),
                    '_phone_number' => $this->getUser()
                        ->getPhone()
                ));
                $sms->send();
                break;
            case self::$NEW_ORDER:
                // Само сообщение о создании нового заказа клиенту и администратору
                
                // !!! Клиент
                $message2 = new YiiMailMessage();
                if (intval(Yii::app()->config->get('Site.CheckOrderBeforePayment')) == 1) {
                    $message2->setBody($this->getEmailTextClientCheckOrderBeforePayment(), 'text/html');
                    
                    $body = $this->getEmailText();
                } else {
                    $message2->setBody($this->getEmailTextClientNotCheckOrderBeforePayment(), 'text/html');
                    
                    $body = $this->getEmailTextAdminNotCheckOrderBeforePayment();
                }
                
                $message2->setSubject(Yii::t('shop_cart', 'New order on the website').' '.Yii::app()->config->get('Site.SiteName'));
                $message2->addTo($this->getUser()
                    ->getEmail());
                $message2->from = Yii::app()->config->get('Site.NoreplyEmail');
                Yii::app()->mail->send($message2);
                // !!! Клиент
                
                // !!! Администратор
                $message->setBody($body, 'text/html');
                $message->setSubject(Yii::t('shop_cart', 'New order on the website').' '.Yii::app()->config->get('Site.SiteName'));
                // $message->addTo($this->getUser()->getEmail());
                $message->addTo(Yii::app()->config->get('Site.AdminEmail'));
                $message->from = Yii::app()->config->get('Site.NoreplyEmail');
                // !!! Администратор
                
                // СМС
                $sms = new VipSmsSmsSend();
                $sms->setData(array(
                    '_text' => Yii::t('shop_cart', 'New order№').$this->id,
                    '_phone_number' => $this->getUser()
                        ->getPhone()
                ));
                $sms->send();
                // СМС
                break;
            case self::$MERGE:
                $message->setBody(Yii::t('shop_cart', 'Order №').' '.implode(', ', $params['mergeIds'])." ".Yii::t('shop_cart', 'Order has been removed №').' '.$this->id.' '.Yii::t('shop_cart', 'Accessible from the remote order.'), 'text/html');
                $message->setSubject(Yii::t('shop_cart', 'Inform to the order').' '.$this->id.' '.Yii::t('shop_cart', 'added orders').' '.implode(', ', $params['mergeIds']));
                foreach ($clients as $email)
                    $message->addTo($email);
                $message->from = Yii::app()->config->get('Site.NoreplyEmail');
                break;
            default:
                break;
        }
        
        $recipient_count = Yii::app()->mail->send($message);
        
        return $recipient_count > 0;
    }

    public function getEmailTextClientCheckOrderBeforePayment()
    {
        return Yii::t('shop_cart', 'Hello!')."<br><br>\n\n".
               Yii::t('shop_cart', 'Thanks for the made order.')."<br>\n".
               str_replace('__number__', $this->primaryKey, Yii::t('shop_cart', 'We have accepted your order No.__number__ for processing.'))."<br>\n".
               Yii::t('shop_cart', 'After reviewing it, we will contact you to clarify the terms of delivery of the order.')."<br>\n".
               Yii::t('shop_cart', 'You ordered:')."<br><br>\n\n".
               $this->getItemsEmailText()."<br>\n".Yii::t('shop_cart', 'YOUR STORE')."<br>\n".Yii::app()->getRequest()->getHostInfo();
    }

    public function getEmailTextClientNotCheckOrderBeforePayment()
    {
        $text = Yii::t('shop_cart', 'Hello!')."<br><br>\n\n".Yii::t('shop_cart', 'Thank you for your order №{number}.', array(
            '{number}' => $this->primaryKey
        ))."<br>\n".Yii::t('shop_cart', 'You ordered:')."<br><br>\n\n".$this->getItemsEmailText()."<br>\n";
        
        if ($this->isPrePayOrder()) {
            $text .= Yii::t('shop_cart', 'In the order there are custom items, please make a prepayment {amount}.', array(
                '{amount}' => Yii::app()->getModule('prices')->getPriceFormatFunction($this->getPrePaySum())
            ))."<br>\n".Yii::t('shop_cart', 'You can make it here: <a href="{link}">PREPAYMENT</a> / <a href="{link2}">TOTAL COST</a>.', array(
                '{link}' => Yii::app()->createAbsoluteUrl('/webPayments/webPayments/prepay', array('order' => $this->primaryKey)),
                '{link2}' => Yii::app()->createAbsoluteUrl('/webPayments/webPayments/pay', array('order' => $this->primaryKey)),
            ))."<br>\n";
        } else {
            $text .= Yii::t('shop_cart', 'You can pay it <a href="{link}">here</a>.', array(
                '{link}' => Yii::app()->createAbsoluteUrl('/webPayments/webPayments/pay', array(
                    'order' => $this->primaryKey
                ))
            ))."<br>\n";
        }
        
        $text .= Yii::t('shop_cart', 'YOUR STORE')."<br>\n".Yii::app()->getRequest()->getHostInfo();
        
        return $text;
    }

    public function getEmailText()
    {
        /*
         * $orderStatus = new OrdersStatus();
         *
         * $txt = '<strong>'.Yii::t('shop_cart', 'Date of registration').'</strong> - '.date('d.m.Y H:i:s', $this->create_date)."<br>\n".
         * '<strong>'.Yii::t('shop_cart', 'Status of the order').'</strong> - '.$orderStatus->getName($this->status)."<br>\n".
         * //'<strong>'.Yii::t('shop_cart', 'Payment status').'</strong> - '.$orderStatus->getPayedName($this->payed_status)."<br>\n".
         * //"<strong>".Yii::t('shop_cart', 'Type of payment')."</strong> - $this->payment_method<br>\n".
         * "<strong>".Yii::t('shop_cart', 'The delivery type')."</strong> - $this->delivery_method<br>\n".
         * '<strong>'.Yii::t('shop_cart', 'The address for the delivery').'</strong> - '.$this->getDeliveryAddress()." <br>\n".
         * "<strong>".Yii::t('shop_cart', 'Shipping cost')."</strong> - $this->delivery_cost<br>\n".
         * "<strong>".Yii::t('shop_cart', 'Order amount')."</strong> - $this->total_cost<br>\n";
         *
         * return $txt.$this->getItemsEmailText().$this->getUser()->getEmailText();
         */
        $txt = Yii::t('shop_cart', 'Hello!')."<br><br>\n\n".Yii::t('shop_cart', 'Date of registration').' '.date('d.m.Y H:i:s', $this->create_date)."<br>\n".Yii::t('shop_cart', 'Received Order No.{number}.', array(
            '{number}' => $this->primaryKey
        ))."<br>\n".Yii::t('shop_cart', 'Confirmation is required.')."<br>\n".
        // Yii::t('shop_cart', 'From the client:')."<br>\n".
        $this->getUser()->getEmailText()."<br>\n".Yii::t('shop_cart', 'Ordered:')."<br>\n".$this->getItemsEmailText();
        
        return $txt;
    }

    public function getEmailTextAdminNotCheckOrderBeforePayment()
    {
        $txt = Yii::t('shop_cart', 'Hello!')."<br><br>\n\n".Yii::t('shop_cart', 'Date of registration').' '.date('d.m.Y H:i:s', $this->create_date)."<br>\n".Yii::t('shop_cart', 'Received Order No.{number}.', array(
            '{number}' => $this->primaryKey
        ))."<br>\n".
        // Yii::t('shop_cart', 'From the client:')."<br>\n".
        $this->getUser()->getEmailText()."<br>\n".Yii::t('shop_cart', 'Ordered:')."<br>\n".$this->getItemsEmailText();
        
        return $txt;
    }

    public function getItemsEmailText()
    {
        $txt = Yii::t('shop_cart', 'Goods').":<br>";
        
        foreach ($this->getItemsDataProvider()->getData() as $data) {
            $txt .= "<strong>".Yii::t('shop_cart', 'Manufacturer')."</strong> - $data->brand<br>\n".
                    "<strong>".Yii::t('shop_cart', 'Name')."</strong> - $data->name<br>\n".
                    "<strong>".Yii::t('shop_cart', 'Article')."</strong> - $data->article <br>\n".
                    // "<strong>Поставщик</strong> - $data->supplier<br>\n" .
                    "<strong>".Yii::t('shop_cart', 'Cost(for 1pc.)')."</strong> - ".Yii::app()->getModule('shop_cart')->getPriceFormatFunction($data->price)."<br>\n".
                    "<strong>".Yii::t('shop_cart', 'Number')."</strong> - $data->quantum<br>\n".
                    "<strong>".Yii::t('shop_cart', 'Delivery time')."</strong> - $data->delivery<br>\n-------------------------------------------------------------<br>\n";
        }
        
        //Стоимость доставки + итого
        $txt .= ($this->delivery_cost ? '<strong>'.Yii::t('shop_cart', 'Shipping cost').':</strong> '.Yii::app()->getModule('shop_cart')->getPriceFormatFunction($this->delivery_cost)."<br>\n" : '').
                '<strong>'.Yii::t('shop_cart', 'Total:').'</strong> '.Yii::app()->getModule('shop_cart')->getPriceFormatFunction($this->total_cost)."<br>\n";
        
        return $txt;
    }

    public function getDeliveryAddress()
    {
        $temp = array();
        if (! empty($this->zipcode))
            $temp[] = $this->zipcode;
        if (! empty($this->country))
            $temp[] = $this->country;
        if (! empty($this->city))
            $temp[] = $this->city;
        if (! empty($this->street))
            $temp[] = $this->street;
        if (! empty($this->house))
            $temp[] = $this->house;
        return implode(', ', $temp);
    }

    public function getTotalSum()
    {
        $db = Yii::app()->db;
        $sql = 'SELECT SUM(t.price*t.quantum) as `total_cost` FROM `'.Items::model()->tableName().'` `t`  '."WHERE order_id='$this->id' and status!='9'";
        
        $value = $db->createCommand($sql)->queryScalar();
        // throw new CHttpException(400,$sql);
        $temp = $this->tatal_paid;
        $this->total_cost = $value + $this->delivery_cost;
        $this->left_pay = $this->total_cost - $temp;
    }

    public function finishOrder($flag = false, $money = true)
    {
        $this->getTotalSum();
        $user = UserProfile::model()->findByAttributes(array(
            'uid' => $this->user_id
        ));
        
        if ($this->left_pay > 0) {
            if (($user != null) && $money) {
                if ($flag)
                    $user->addMoneyOperation($this->left_pay, Yii::t('shop_cart', 'Depositing'));
                $user->addMoneyOperation(- $this->left_pay, Yii::t('shop_cart', 'Payment order №').$this->id, $this->id);
            }
            
            $this->tatal_paid += $this->left_pay;
            $this->left_pay = 0;
        }
        
        $this->payed_status = 2;
        
        $sql = 'UPDATE  `'.Items::model()->tableName()."` SET `payed_status`='2'  WHERE `order_id`='$this->id' AND `status`!='9'";
        Yii::app()->db->createCommand($sql)->query();
    }

    public function refundMoney()
    {
        if ($this->left_pay < 0) {
            $user = UserProfile::model()->findByAttributes(array(
                'uid' => $this->user_id
            ));
            if ($user == NULL)
                return Yii::t('shop_cart', 'The user does not exist');
            $user->addMoneyOperation(- ($this->left_pay), Yii::t('shop_cart', 'Return of means by ordert №').$this->id);
            $this->left_pay = 0;
        }
        $this->save();
        return Yii::t('shop_cart', 'Refunds successfully completed');
    }

    public function cancelOrder()
    {
        $this->payed_status = 0;
        $this->left_pay -= $this->tatal_paid;
        $this->tatal_paid = 0;
        // Reliability::addRefuseOrder($this->supplier_inn);
    }

    public function toTrash()
    {
        $this->is_trash = 1;
        $this->save();
    }

    public function fromTrash()
    {
        $this->is_trash = 0;
        $this->save();
    }

    public function getUser($flag = false)
    {
        if ($this->user_model == null || $flag) {
            $this->user_model = UserProfile::model()->findByAttributes(array(
                'uid' => $this->user_id
            ));
        }
        
        if ($this->user_model == NULL) {
            throw new CHttpException(400, Yii::t('shop_cart', 'Do not repeat the request again'));
        }
        
        return $this->user_model;
    }

    public function getDataProvider()
    {
        $criteria = new CDbCriteria();
        
        $criteria->compare('id', $this->id);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    public function getItemsDataProvider()
    {
        $criteria = new CDbCriteria();
        
        $criteria->compare('order_id', $this->id);
        
        return new CActiveDataProvider('Items', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999
            )
        ));
    }

    public function updateOrder($update_save = false)
    {
        if ($update_save)
            $this->update_status = 1;
        $this->getTotalSum();
        $this->save();
    }

    public function checkDone()
    {
        return /*$this->status == 9 || $this->status == 8 || */$this->payed_status == 2 || $this->is_trash == 1 || ($this->status != 1);
    }

    public function isFormEnabled()
    {
        return ($this->checkDone() || $this->confirmed ? '' : 'off');
    }

    public function updateItemsIdInOrder($id)
    {
        $db = Yii::app()->db;
        $sql = 'UPDATE `'.Items::model()->tableName().'` SET  '." order_id='$id' WHERE order_id='$this->id' ";
        $db->createCommand($sql)->query();
    }

    public function deleteTrash()
    {
        $db = Yii::app()->db;
        $sql = 'DELETE FROM `'.Items::model()->tableName().'`  WHERE(SELECT count(*) FROM `'.$this->tableName().'` WHERE `'.$this->tableName().'`.id=order_id and is_trash=1 LIMIT 1)>0 and status!=8 and payed_status!=2';
        $db->createCommand($sql)->query();
        $sql = 'DELETE FROM `'.$this->tableName().'`  WHERE is_trash=1 and status!=8 and payed_status!=2';
        $db->createCommand($sql)->query();
    }

    public function mergeOrdersCheck($mergeIds = array())
    {
        $is_not_payed = true;
        $is_not_one_owner = false;
        foreach ($mergeIds as $value) {
            $model = Orders::model()->findByPk($value);
            if ($model != NULL) {
                if ($model->checkDone())
                    $is_not_payed = false;
                if ($this->user_id != $model->user_id)
                    $is_not_one_owner = true;
            }
        }
        return array(
            'is_not_payed' => $is_not_payed,
            'is_not_one_owner' => $is_not_one_owner
        );
    }

    public function mergeOrders($mergeIds = array())
    {
        $clients = array(
            $this->getUser()->getEmail()
        );
        foreach ($mergeIds as $value) {
            $model = Orders::model()->findByPk($value);
            if ($model != NULL) {
                $clients[] = $model->getUser()->getEmail();
                $model->updateItemsIdInOrder($this->id);
                $model->delete();
            }
        }
        $this->updateOrder();
        $this->sendEmailNotification($clients, self::$MERGE, array(
            'mergeIds' => $mergeIds
        ));
    }

    public function getNewItem()
    {
        $model = new Items();
        $model->order_id = $this->id;
        $model->user_id = $this->user_id;
        return $model;
    }

    public function getDurationList()
    {
        return array();
    }

    public function getBill()
    {
        $edinici = Yii::app()->config->get('PaymentDocuments.Bill.ProductMarker');
        $nds = Yii::app()->config->get('PaymentDocuments.Bill.Ndc');
        
        // Создаем объект класса PHPExcel
        $xls = PHPExcel_IOFactory::load(realpath(__DIR__.'/../templates').'/bill.xls');
        // Устанавливаем индекс активного листа
        $xls->setActiveSheetIndex(0);
        // Получаем активный лист
        $sheet = $xls->getActiveSheet();
        // Подписываем лист
        $sheet->setTitle('Счет');
        
        $sheet->setCellValueByColumnAndRow(1, 5, Yii::app()->config->get('PaymentDocuments.Bill.Bank'));
        $sheet->setCellValueByColumnAndRow(4, 8, Yii::app()->config->get('PaymentDocuments.Bill.Inn'));
        $sheet->setCellValueByColumnAndRow(13, 8, Yii::app()->config->get('PaymentDocuments.Bill.Kpp'));
        $sheet->setCellValueByColumnAndRow(1, 9, Yii::app()->config->get('PaymentDocuments.Bill.OrganizationName'));
        $sheet->setCellValueExplicitByColumnAndRow(25, 5, Yii::app()->config->get('PaymentDocuments.Bill.Bik'));
        $sheet->setCellValueExplicitByColumnAndRow(25, 6, Yii::app()->config->get('PaymentDocuments.Bill.Kc'));
        $sheet->setCellValueExplicitByColumnAndRow(25, 8, Yii::app()->config->get('PaymentDocuments.Bill.Rc'));
        $sheet->setCellValueByColumnAndRow(6, 17, Yii::app()->config->get('PaymentDocuments.Bill.Supplier'));
        $sheet->setCellValueByColumnAndRow(7, 31, Yii::app()->config->get('PaymentDocuments.Bill.Director'));
        $sheet->setCellValueByColumnAndRow(28, 31, Yii::app()->config->get('PaymentDocuments.Bill.Accounter'));
        $sheet->setCellValueByColumnAndRow(6, 19, ($this->User->legal_entity == '1' || $this->User->legal_entity == '2' ? $this->User->organization_name : $this->User->first_name.' '.$this->User->second_name));
        $sheet->setCellValueByColumnAndRow(1, 13, 'Счет на оплату №'.$this->id.' от '.date('d.m.Y', $this->create_date).' г.');
        
        $items = $this->getItemsDataProvider();
        
        $i = 22;
        $j = $items->getItemCount();
        $z = 1;
        $itogo = 0;
        foreach ($items->getData() as $item) {
            $itogo += $item->quantum * $item->price;
            $name_echo = (! empty($item->brand) ? $item->brand.', ' : '').(! empty($item->article) ? $item->article.', ' : '').$item->name;
            if ($j > 1) {
                $sheet->insertNewRowBefore($i);
                $sheet->mergeCellsByColumnAndRow(1, $i, 2, $i);
                $sheet->mergeCellsByColumnAndRow(3, $i, 20, $i);
                $sheet->getStyleByColumnAndRow(3, $i)
                    ->getAlignment()
                    ->setWrapText(true);
                $sheet->getStyleByColumnAndRow(3, $i)->applyFromArray(array(
                    "font" => array(
                        "size" => 8
                    )
                ));
                $sheet->mergeCellsByColumnAndRow(21, $i, 23, $i);
                $sheet->mergeCellsByColumnAndRow(24, $i, 26, $i);
                $sheet->mergeCellsByColumnAndRow(27, $i, 31, $i);
                $sheet->mergeCellsByColumnAndRow(32, $i, 37, $i);
                $i ++;
                $j --;
                $sheet->setCellValueByColumnAndRow(1, $i - 1, $z);
                $sheet->setCellValueByColumnAndRow(3, $i - 1, $name_echo);
                $sheet->setCellValueByColumnAndRow(21, $i - 1, $item->quantum);
                $sheet->setCellValueByColumnAndRow(24, $i - 1, $edinici);
                $sheet->setCellValueByColumnAndRow(27, $i - 1, $item->price);
                $sheet->setCellValueByColumnAndRow(32, $i - 1, $item->quantum * $item->price);
                $sheet->getRowDimension($i - 1)->setRowHeight(35);
                $z ++;
            } else {
                $sheet->setCellValueByColumnAndRow(1, $i, $z);
                $sheet->setCellValueByColumnAndRow(3, $i, $name_echo);
                $sheet->setCellValueByColumnAndRow(21, $i, $item->quantum);
                $sheet->setCellValueByColumnAndRow(24, $i, $edinici);
                $sheet->setCellValueByColumnAndRow(27, $i, $item->price);
                $sheet->setCellValueByColumnAndRow(32, $i, $item->quantum * $item->price);
                $sheet->getRowDimension($i)->setRowHeight(35);
            }
        }

        if ($this->delivery_cost) {
            $delivery_cost = intval($this->delivery_cost);
            $itogo += $delivery_cost;

            //$i ++;
            $sheet->mergeCellsByColumnAndRow(29, $i + 1, 31, $i + 1);
            $sheet->setCellValueByColumnAndRow(29, $i + 1, 'Доставка');
            $sheet->getStyleByColumnAndRow(29, $i + 1)->getFont()->setBold(true);
            $sheet->getStyleByColumnAndRow(29, $i + 1)->applyFromArray(array(
                "font" => array(
                    "size" => 9
                )
            ));

            $sheet->mergeCellsByColumnAndRow(32, $i + 1, 37, $i + 1);
            $sheet->setCellValueByColumnAndRow(32, $i + 1, $this->delivery_cost);
            $sheet->getStyleByColumnAndRow(32, $i + 1)->getFont()->setBold(true);
            $sheet->getStyleByColumnAndRow(32, $i + 1)->applyFromArray(array(
                "font" => array(
                    "size" => 9
                )
            ));
            $sheet->getStyleByColumnAndRow(32, $i + 1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        }

        $sheet->setCellValueByColumnAndRow(32, $i + 2, $itogo);
        $sheet->setCellValueByColumnAndRow(32, $i + 3, round($itogo * $nds, 2));
        $sheet->setCellValueByColumnAndRow(32, $i + 4, $itogo);
        $sheet->setCellValueByColumnAndRow(1, $i + 5, 'Всего наименований '.$z.', на сумму '.$itogo.Yii::app()->getModule('currencies')->defaultCurrencyMarker);

        return $xls;
    }

    public function getCustomBill()
    {
        $Month_r = array(
            "01" => "Января",
            "02" => "Февраля",
            "03" => "Марта",
            "04" => "Апреля",
            "05" => "Мая",
            "06" => "Июня",
            "07" => "Июля",
            "08" => "Августа",
            "09" => "Сентября",
            "10" => "Октября",
            "11" => "Ноября",
            "12" => "Декабря"
        );
        $edinici = Yii::app()->config->get('PaymentDocuments.CustomerBill.ProductMarker');
        $nds = Yii::app()->config->get('PaymentDocuments.CustomerBill.Ndc');
        $country_code = Yii::app()->config->get('PaymentDocuments.CustomerBill.CountryCode');
        $country_name = Yii::app()->config->get('PaymentDocuments.CustomerBill.CountryName');
        $custom_declaration = Yii::app()->config->get('PaymentDocuments.CustomerBill.Declaration');
        
        $user_info = '';
        if ($this->User->legal_entity == '0')
            $user_info = (empty($this->User->delivery_zipcode) ? $this->User->delivery_zipcode : '').', '.(empty($this->User->delivery_country) ? $this->User->delivery_country : '').', '.(empty($this->User->delivery_city) ? $this->User->delivery_city : '').', '.(empty($this->User->delivery_street) ? $this->User->delivery_street : '').', '.(empty($this->User->delivery_house) ? $this->User->delivery_house : '');
        if ($this->User->legal_entity == '1')
            $user_info = (empty($this->User->legal_zipcode) ? $this->User->legal_zipcode : '').', '.(empty($this->User->legal_country) ? $this->User->legal_country : '').', '.(empty($this->User->legal_city) ? $this->User->legal_city : '').', '.(empty($this->User->legal_street) ? $this->User->legal_street : '').', '.(empty($this->User->legal_house) ? $this->User->legal_house : '');
        if ($this->User->legal_entity == '2')
            $user_info = (empty($this->User->legal_zipcode) ? $this->User->legal_zipcode : '').', '.(empty($this->User->legal_country) ? $this->User->legal_country : '').', '.(empty($this->User->legal_city) ? $this->User->legal_city : '').', '.(empty($this->User->legal_street) ? $this->User->legal_street : '').', '.(empty($this->User->legal_house) ? $this->User->legal_house : '');
        
        // Создаем объект класса PHPExcel
        $xls = PHPExcel_IOFactory::load(realpath(__DIR__.'/../templates').'/custom_bill.xls');
        // Устанавливаем индекс активного листа
        $xls->setActiveSheetIndex(0);
        // Получаем активный лист
        $sheet = $xls->getActiveSheet();
        // Подписываем лист
        $sheet->setTitle('Счет Фактура');
        
        $sheet->setCellValueByColumnAndRow(1, 4, "Счет-фактура № $this->id от ".date('d ').$Month_r[date('m')].date(' Y г.'));
        $sheet->setCellValueByColumnAndRow(1, 6, 'Продавец: '.Yii::app()->config->get('PaymentDocuments.CustomerBill.Prodavec'));
        $sheet->setCellValueByColumnAndRow(1, 7, 'Адрес: '.Yii::app()->config->get('PaymentDocuments.CustomerBill.Adress'));
        $sheet->setCellValueByColumnAndRow(1, 8, 'ИНН/КПП продавца: '.Yii::app()->config->get('PaymentDocuments.CustomerBill.Iin'));
        $sheet->setCellValueByColumnAndRow(1, 9, 'Грузоотправитель и его адрес: Он же');
        $sheet->setCellValueByColumnAndRow(1, 10, 'Грузополучатель и его адрес: '.($this->User->legal_entity == '1' || $this->User->legal_entity == '2' ? $this->User->organization_name : $this->User->first_name.' '.$this->User->second_name).', ');
        $sheet->setCellValueByColumnAndRow(1, 11, "К платежно-расчетному документу № $this->id от ".date('d ').$Month_r[date('m')].date(' Y г.'));
        $sheet->setCellValueByColumnAndRow(1, 12, 'Покупатель: '.($this->User->legal_entity == '1' || $this->User->legal_entity == '2' ? $this->User->organization_name : $this->User->first_name.' '.$this->User->second_name).', '.$user_info);
        $sheet->setCellValueByColumnAndRow(1, 13, 'Адрес: '.$user_info);
        $sheet->setCellValueByColumnAndRow(1, 14, 'ИНН/КПП покупателя: '.$this->User->organization_inn.'/'.$this->User->bank_kpp);
        $sheet->setCellValueByColumnAndRow(1, 15, 'Валюта: '.Yii::app()->config->get('PaymentDocuments.CustomerBill.Valuta'));
        $sheet->setCellValueByColumnAndRow(12, 21, Yii::app()->config->get('PaymentDocuments.CustomerBill.GvavBuh'));
        $sheet->setCellValueByColumnAndRow(4, 21, Yii::app()->config->get('PaymentDocuments.CustomerBill.GvavBuh'));
        // $sheet->setCellValueByColumnAndRow(, 2, ': '.Yii::app()->config->get('PaymentDocuments.CustomerBill.'));
        
        // $sheet->setCellValueByColumnAndRow(6, 19,($this->User->legal_entity == '1' || $this->User->legal_entity == '2' ? $this->User->organization_name : $this->User->first_name.' '.$this->User->second_name));
        // $sheet->setCellValueByColumnAndRow(1, 13, 'Счет на оплату №'.$this->id.' от '.date('Y', $this->create_date).' г.');
        
        $items = $this->getItemsDataProvider();
        
        $i = 19;
        $j = $items->getItemCount();
        $z = 1;
        $itogo = 0;
        $ndc_itogo = 0;
        $ndc_plus_itogo = 0;
        foreach ($items->getData() as $item) {
            $itogo += $item->quantum * $item->price;
            $ndc_itogo += round($nds * ($item->quantum * $item->price) / 100, 2);
            $ndc_plus_itogo += round($nds * ($item->quantum * $item->price) / 100 + ($item->quantum * $item->price), 2);
            $name_echo = (! empty($item->brand) ? $item->brand.', ' : '').(! empty($item->article) ? $item->article.', ' : '').$item->name;
            $sheet->insertNewRowBefore($i);
            
            // $sheet->insertNewRowBefore($i);
            // $sheet->mergeCellsByColumnAndRow(1, $i, 2, $i);
            // $sheet->mergeCellsByColumnAndRow(3, $i, 20, $i);
            // $sheet->getStyleByColumnAndRow(3, $i)->getAlignment()->setWrapText(true);
            // $sheet->getStyleByColumnAndRow(3, $i)->applyFromArray(array("font" => array("size" => 8)));
            // $sheet->mergeCellsByColumnAndRow(21, $i, 23, $i);
            // $sheet->mergeCellsByColumnAndRow(24, $i, 26, $i);
            // $sheet->mergeCellsByColumnAndRow(27, $i, 31, $i);
            // $sheet->mergeCellsByColumnAndRow(32, $i, 37, $i);
            $i ++;
            $j --;
            // $sheet->setCellValueByColumnAndRow(1, $i - 1, $z);
            $sheet->getStyleByColumnAndRow(1, $i - 1)
                ->getAlignment()
                ->setWrapText(true);
            $sheet->setCellValueByColumnAndRow(1, $i - 1, $name_echo);
            $sheet->setCellValueByColumnAndRow(4, $i - 1, $item->quantum.',000');
            $sheet->setCellValueByColumnAndRow(3, $i - 1, $edinici);
            $sheet->setCellValueByColumnAndRow(5, $i - 1, $item->price);
            $sheet->setCellValueByColumnAndRow(6, $i - 1, $item->quantum * $item->price);
            $sheet->setCellValueByColumnAndRow(8, $i - 1, $nds.'%');
            $sheet->setCellValueByColumnAndRow(9, $i - 1, round($nds * ($item->quantum * $item->price) / 100, 2));
            $sheet->setCellValueByColumnAndRow(10, $i - 1, round($nds * ($item->quantum * $item->price) / 100 + ($item->quantum * $item->price), 2));
            $sheet->setCellValueByColumnAndRow(7, $i - 1, 'без акциза');
            $sheet->setCellValueByColumnAndRow(11, $i - 1, $country_code);
            $sheet->setCellValueByColumnAndRow(12, $i - 1, $country_name);
            $sheet->setCellValueByColumnAndRow(13, $i - 1, $custom_declaration);
            $sheet->getRowDimension($i - 1)->setRowHeight(35);
            $z ++;
        }
        $sheet->setCellValueByColumnAndRow(9, $i, $ndc_itogo);
        $sheet->setCellValueByColumnAndRow(10, $i, $ndc_plus_itogo);
        $sheet->setCellValueByColumnAndRow(6, $i, $itogo);
        // $sheet->setCellValueByColumnAndRow(32, $i + 3, round($itogo * $nds, 2));
        // $sheet->setCellValueByColumnAndRow(32, $i + 4, $itogo);
        // $sheet->setCellValueByColumnAndRow(1, $i + 5, 'Всего наименований '.$z.', на сумму '.$itogo.Yii::app()->getModule('currencies')->defaultCurrencyMarker);
        
        return $xls;
    }

    public function getWaybill()
    {
        $user_info = '';
        if ($this->User->legal_entity == '0')
            $user_info = $this->User->first_name.' '.$this->User->second_name.', '.(empty($this->User->delivery_zipcode) ? $this->User->delivery_zipcode : '').', '.(empty($this->User->delivery_country) ? $this->User->delivery_country : '').', '.(empty($this->User->delivery_city) ? $this->User->delivery_city : '').', '.(empty($this->User->delivery_street) ? $this->User->delivery_street : '').', '.(empty($this->User->delivery_house) ? $this->User->delivery_house : '').', '.$this->User->phone.', '.(empty($this->User->bank) ? $this->User->bank : '').', '.(empty($this->User->bank_bik) ? $this->User->bank_bik : '');
        if ($this->User->legal_entity == '1')
            $user_info = $this->User->organization_name.', '.$this->User->organization_inn.', '.(empty($this->User->legal_zipcode) ? $this->User->legal_zipcode : '').', '.(empty($this->User->legal_country) ? $this->User->legal_country : '').', '.(empty($this->User->legal_city) ? $this->User->legal_city : '').', '.(empty($this->User->legal_street) ? $this->User->legal_street : '').', '.(empty($this->User->legal_house) ? $this->User->legal_house : '').', '.$this->User->phone.', '.(empty($this->User->bank) ? $this->User->bank : '').', '.(empty($this->User->bank_bik) ? $this->User->bank_bik : '');
        if ($this->User->legal_entity == '2')
            $user_info = $this->User->organization_name.', '.$this->User->organization_inn.', '.(empty($this->User->legal_zipcode) ? $this->User->legal_zipcode : '').', '.(empty($this->User->legal_country) ? $this->User->legal_country : '').', '.(empty($this->User->legal_city) ? $this->User->legal_city : '').', '.(empty($this->User->legal_street) ? $this->User->legal_street : '').', ' .(empty($this->User->legal_house) ? $this->User->legal_house : '').', '.$this->User->phone.', ' .(empty($this->User->bank) ? $this->User->bank : '').', ' .(empty($this->User->bank_bik) ? $this->User->bank_bik : '');
		$edinici = Yii::app()->config->get('PaymentDocuments.Waybill.ProductMarker');
		$nds = Yii::app()->config->get('PaymentDocuments.Waybill.Ndc');
		$edinici_cod = Yii::app()->config->get('PaymentDocuments.Waybill.ProductMarkerCode');
		$vid_upakovki = Yii::app()->config->get('PaymentDocuments.Waybill.BoxType');
		$masa = Yii::app()->config->get('PaymentDocuments.Waybill.WeightOnePlace');
		$masa_sh = Yii::app()->config->get('PaymentDocuments.Waybill.WeightCount');
		
		$xls = PHPExcel_IOFactory::load(realpath(__DIR__.'/../templates').'/waybill.xls');
		// Устанавливаем индекс активного листа
		$xls->setActiveSheetIndex(0);
		// Получаем активный лист
		$sheet = $xls->getActiveSheet();
		// Подписываем лист
		$sheet->setTitle('Товарная накладная');
		$sheet->setCellValueByColumnAndRow(50, 7, Yii::app()->config->get('PaymentDocuments.Waybill.OKPO1'));
		$sheet->setCellValueByColumnAndRow(50, 16, Yii::app()->config->get('PaymentDocuments.Waybill.OKPO2'));
		$sheet->setCellValueByColumnAndRow(1, 7, Yii::app()->config->get('PaymentDocuments.Waybill.StoreOrganizationName'));
		$sheet->setCellValueByColumnAndRow(8, 12, $user_info);
		$sheet->setCellValueByColumnAndRow(8, 15, Yii::app()->config->get('PaymentDocuments.Waybill.Supplier'));
		$sheet->setCellValueByColumnAndRow(24, 50, Yii::app()->config->get('PaymentDocuments.Waybill.Director'));
		$sheet->setCellValueByColumnAndRow(23, 52, Yii::app()->config->get('PaymentDocuments.Waybill.Accounter'));
		$sheet->setCellValueByColumnAndRow(37, 28, date('d.m.Y', $this->create_date));
		$sheet->setCellValueByColumnAndRow(32, 28, $this->id);
		
		$items = $this->getItemsDataProvider();
		$i = 34;
		$j = $items->getItemCount();
		$z = 1;
		$itogo = 0;
		$itogo_nds = 0;
		$itogo_bez_nds = 0;
		$itogo_count = 0;
		
		foreach($items->getData() as $item) {
			$itogo += $item->quantum * $item->price;
			$itogo_count += $item->quantum;
			$itogo_nds += round($item->quantum * $item->price *($nds), 2);
			$itogo_bez_nds += round($item->quantum * $item->price *(1 - $nds), 2);
			$name_echo =(! empty($item->brand) ? $item->brand.', ' : '') .(! empty($item->article) ? $item->article.', ' : '').$item->name;
			if($j > 1) {
				$sheet->insertNewRowBefore($i);
				$sheet->mergeCellsByColumnAndRow(1, $i, 3, $i);
				$sheet->mergeCellsByColumnAndRow(4, $i, 17, $i);
				$sheet->getStyleByColumnAndRow(4, $i)->getAlignment()->setWrapText(true);
				$sheet->getStyleByColumnAndRow(4, $i)->applyFromArray(array(
						"font" => array(
								"size" => 8 
						) 
				));
				$sheet->mergeCellsByColumnAndRow(18, $i, 20, $i);
				$sheet->mergeCellsByColumnAndRow(21, $i, 23, $i);
				$sheet->mergeCellsByColumnAndRow(24, $i, 26, $i);
				$sheet->mergeCellsByColumnAndRow(27, $i, 29, $i);
				$sheet->mergeCellsByColumnAndRow(30, $i, 32, $i);
				$sheet->mergeCellsByColumnAndRow(33, $i, 34, $i);
				$sheet->mergeCellsByColumnAndRow(35, $i, 37, $i);
				$sheet->mergeCellsByColumnAndRow(38, $i, 40, $i);
				$sheet->mergeCellsByColumnAndRow(41, $i, 43, $i);
				$sheet->mergeCellsByColumnAndRow(44, $i, 46, $i);
				$sheet->mergeCellsByColumnAndRow(47, $i, 49, $i);
				$sheet->mergeCellsByColumnAndRow(50, $i, 53, $i);
				$sheet->mergeCellsByColumnAndRow(54, $i, 57, $i);
				
				$i ++;
				$j --;
				$sheet->setCellValueByColumnAndRow(1, $i - 1, $z);
				$sheet->setCellValueByColumnAndRow(4, $i - 1, $name_echo);
				$sheet->setCellValueByColumnAndRow(18, $i - 1, $item->article);
				$sheet->setCellValueByColumnAndRow(21, $i - 1, $edinici);
				$sheet->setCellValueByColumnAndRow(24, $i - 1, $edinici_cod);
				$sheet->setCellValueByColumnAndRow(27, $i - 1, $vid_upakovki);
				$sheet->setCellValueByColumnAndRow(30, $i - 1, $masa);
				$sheet->setCellValueByColumnAndRow(33, $i - 1, $masa_sh);
				$sheet->setCellValueByColumnAndRow(33, $i - 1, $item->quantum);
				$sheet->setCellValueByColumnAndRow(38, $i - 1, $item->quantum);
				$sheet->setCellValueByColumnAndRow(41, $i - 1, $item->price);
				$sheet->setCellValueByColumnAndRow(44, $i - 1, round($item->quantum * $item->price *(1 - $nds), 2));
				$sheet->setCellValueByColumnAndRow(47, $i - 1, round(100 *($nds), 0).'%');
				$sheet->setCellValueByColumnAndRow(50, $i - 1, round($item->quantum * $item->price *($nds), 2));
				$sheet->setCellValueByColumnAndRow(54, $i - 1, $item->quantum * $item->price);
				$sheet->getRowDimension($i - 1)->setRowHeight(35);
				$z ++;
			} else {
				$sheet->setCellValueByColumnAndRow(1, $i, $z);
				$sheet->setCellValueByColumnAndRow(4, $i, $name_echo);
				$sheet->setCellValueByColumnAndRow(18, $i, $item->article);
				$sheet->setCellValueByColumnAndRow(21, $i, $edinici);
				$sheet->setCellValueByColumnAndRow(24, $i, $edinici_cod);
				$sheet->setCellValueByColumnAndRow(27, $i, $vid_upakovki);
				$sheet->setCellValueByColumnAndRow(30, $i, $masa);
				$sheet->setCellValueByColumnAndRow(33, $i, $masa_sh);
				$sheet->setCellValueByColumnAndRow(33, $i, $item->quantum);
				$sheet->setCellValueByColumnAndRow(38, $i, $item->quantum);
				$sheet->setCellValueByColumnAndRow(41, $i, $item->price);
				$sheet->setCellValueByColumnAndRow(44, $i, round($item->quantum * $item->price *(1 - $nds), 2));
				$sheet->setCellValueByColumnAndRow(47, $i, round(100 *($nds), 0).'%');
				$sheet->setCellValueByColumnAndRow(50, $i, round($item->quantum * $item->price *($nds), 2));
				$sheet->setCellValueByColumnAndRow(54, $i, $item->quantum * $item->price);
				$sheet->getRowDimension($i)->setRowHeight(35);
			}
		}
		// $sheet->setCellValueByColumnAndRow(33, $i + 1, $items->getItemCount() * $masa_sh);
		$sheet->setCellValueByColumnAndRow(44, $i + 1, $itogo_bez_nds);
		$sheet->setCellValueByColumnAndRow(50, $i + 1, $itogo_nds);
		$sheet->setCellValueByColumnAndRow(54, $i + 1, $itogo);
		$sheet->setCellValueByColumnAndRow(33, $i + 1, $itogo_count);
		$sheet->setCellValueByColumnAndRow(38, $i + 1, $itogo_count);
		return $xls;
	}
	
	public function getUsersAutoComplete() {
		$array = array();
		$list = CHtml::listData(UserProfile::model()->findAllByAttributes(array()), 'uid', 'fullNameOrg');
		foreach($list as $key => $value) {
			$array [] = array(
					'id' => $key,
					'label' => $value,
					'value' => $value 
			);
		}
		return $array;
	}
	
	public function canPayed() {
		return in_array($this->status, array(1, 2)) && ($this->payed_status != 2);
	}
}