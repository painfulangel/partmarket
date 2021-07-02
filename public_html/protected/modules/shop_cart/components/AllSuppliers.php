<?php

/**
 * Description of Suppliers
 *
 * @author Sergij
 */
class AllSuppliers {

    public $list = null;
    public $list_inn = null;
    private static $_models = array();   // class name => model

    public function insert($data) {
        foreach ($data as $value) {
            $this->list[$value['supplier']] = $value['supplier'];
            $this->list_inn[$value['supplier']] = $value['supplier_inn'];
        }
    }

    public function getList() {
        if ($this->list == null) {
            $data = ParsersApi::model()->getSuppliers();
            $this->insert($data);
            $data = Parsers::model()->getSuppliers();
            $this->insert($data);
            $data = Prices::model()->getSuppliers();
            $this->insert($data);
        }
        return $this->list;
    }

    public function getStateList() {
        return array(
            '-1' => Yii::t('shop_cart', 'Any'),
            '0' => Yii::t('shop_cart', 'Not ordered'),
            '1' => Yii::t('shop_cart', 'Ordered'),
        );
    }

    public static function model($className = __CLASS__) {
        if (isset(self::$_models[$className]))
            return self::$_models[$className];
        else {
            $model = self::$_models[$className] = new $className(null);
            return $model;
        }
    }

}
