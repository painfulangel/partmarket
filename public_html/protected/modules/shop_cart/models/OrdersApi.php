<?php

class OrdersApi extends Orders {

    public $items_order = array();

    public function beforeSave() {
        $this->create_date = time();

        if ($this->isNewRecord) {
            $this->status = 1;
            $this->payed_status = 0;
            $this->ic_status = 0;
            $this->update_status = 1;
            $delivery_name_method1 = Yii::app()->getModule('shop_cart')->delivery_model->PAYMENT_GET_METHOD;
            $delivery_name_method2 = Yii::app()->getModule('shop_cart')->delivery_model->POST_METHOD;
            if ($delivery_name_method1 != $this->delivery_method && $delivery_name_method2 != $this->delivery_method) {
                $this->zipcode = '';
                $this->city = '';
                $this->country = '';
                $this->street = '';
                $this->house = '';
            }
        }

        if (empty($this->total_weight))
            $this->total_weight = 0;
        if ($this->isNewRecord)
            $this->delivery_cost = Yii::app()->getModule('shop_cart')->delivery_model->getDeliveryPrice($this->delivery_method);

        if (empty($this->delivery_cost))
            $this->delivery_cost = 0;
        $this->getTotalSum();

        return true;
    }

    public function afterSave() {
        if ($this->isNewRecord) {
            Reliability::addDoneOrder($this->supplier_inn);
        }
        if ($this->isNewRecord) {
            $cart = $this->items_order;
            if ($cart && count($cart) > 0) {
                foreach ($cart as $position => $product) {
                    $model = ShopProducts::model()->findByPk($product['product_id']);
                    if ($model->store_count_state == 1) {
                        $temp = PricesData::model()->findByPk($model->price_data_id);
                        if ($temp != null) {
                            $temp->quantum-=$model->quantum;
                            if ($temp->quantum < 0)
                                $temp->quantum = 0;
                            $temp->save();
                        }
                    }
                    $item = new Items;
                    $item->attributes = $model->getAttributes(array('price', 'brand', 'price_echo', 'quantum', 'delivery', 'article', 'article_order', 'supplier_inn', 'supplier', 'store', 'name', 'weight', 'supplier_price', 'price_group_1', 'price_group_2', 'price_group_3', 'price_group_4'));
                    $item->order_id = $this->id;
                    $item->save();
                }
            }

            $db = Yii::app()->db;
            $sql = 'SELECT SUM(t.weight) as `total_weight` FROM `' . Items::model()->tableName() . '` `t` '
                    . "WHERE order_id='$this->id'";
            $value = $db->createCommand($sql)->queryScalar();
            $this->total_weight = $value;
            $this->delivery_cost = Yii::app()->getModule('shop_cart')->delivery_model->getDeliveryPrice($this->delivery_method, array('weight' => $value['total_weight']));
            $this->getTotalSum();
            $this->isNewRecord = false;
            $this->sendEmailNotification(array(), self::$NEW_ORDER);

            $this->save();
        }
    }

}
