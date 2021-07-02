<?php
class PaymentMethods {
    protected $data = null;

    function __construct() {
        $this->CACH_METHOD = Yii::t('shop_cart', 'Cash');
        $this->BANK_METHOD = Yii::t('shop_cart', 'Bank transfer');
        $this->ELECTRONIC_MONEY_METHOD = Yii::t('shop_cart', 'Electronic payments');
    }

    protected function init() {
        $this->data = array(
            $this->CACH_METHOD => $this->CACH_METHOD,
            $this->BANK_METHOD => $this->BANK_METHOD,
            $this->ELECTRONIC_MONEY_METHOD => $this->ELECTRONIC_MONEY_METHOD,
        );
    }

    public function getList($params = array()) {
        if ($this->data == null) {
            $this->init();
        }
        return $this->data;
    }

    public function getName($id) {
        return $this->data[$id];
    }
}