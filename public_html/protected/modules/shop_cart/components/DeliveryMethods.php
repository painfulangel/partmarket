<?php
class DeliveryMethods {
    public $POST_METHOD = 1;
    public $OWN_GET_METHOD = 2;
    public $TRANSPORT_COMPANY = 3;
    public $PAYMENT_GET_METHOD = 4;
    
    protected $data = null;

    function __construct() {
        $this->POST_METHOD = 1;
        $this->OWN_GET_METHOD = 2;
        $this->TRANSPORT_COMPANY = 3;
        $this->PAYMENT_GET_METHOD = 4;
    }

    protected function init() {
    	$this->data = array();
    	
    	$data = Delivery::model()->findAll('active = 1');
    	foreach ($data as $item) {
    		$this->data[$item->primaryKey] = $item->name;
    	}/*
        $this->data = array(
            $this->PAYMENT_GET_METHOD => $this->PAYMENT_GET_METHOD,
            $this->POST_METHOD => $this->POST_METHOD,
            $this->OWN_GET_METHOD => $this->OWN_GET_METHOD,
        );*/
    }

    public function getName($id, $params = array('weight' => '')) {
        
    }

    public function getList($params = array('weight' => '')) {
        if ($this->data == null) {
            $this->init();
        }
        
        $array = array();
        foreach ($this->data as $key => $value) {
            $price = $this->getDeliveryPrice($key, $params);
            
            $array[$key] = $value . ' ' . ($price != '' ? '(' . Yii::app()->getModule('currencies')->getFormatPrice($price) . ')' : '');
        }
        
        return $array;
    }

    public function getDeliveryPrice($type, $params = array('weight' => '')) {
    	$delivery = Delivery::model()->findByPk($type);
    	if (is_object($delivery) && $delivery->price) return $delivery->price;
        /*if ($type == $this->PAYMENT_GET_METHOD) {
            return Yii::app()->config->get('ShopingCart.PaymentGetMethodPrice');
        }

        if ($type == $this->POST_METHOD) {
            //return $params['weight']*Yii::app()->config->get('ShopingCart.PaymentGetMethodPrice');
        }*/

        return '';
    }
}