<?php

class ItemsStatus extends ShopingCartStatus {

    protected $type = 'Items';
    public $statusList = array(
        '1' => 'Принят к обработке',
        '2' => 'Заказ оформлен',
        '4' => 'Частичный резерв',
        '5' => 'Резерв',
        '6' => 'Готов к выдаче',
        '7' => 'Частично выдан',
        '8' => 'Выполнен',
        '9' => 'Отказ',
    );
    public $statusTranslitList = array(
        '1' => 'Prinyat k robote',
        '2' => 'Oformlen',
        '4' => 'Chast. reserv',
        '5' => 'Reserv',
        '6' => 'Gotov k vudache',
        '7' => 'Chast. vudan',
        '8' => 'Vupolnen',
        '9' => 'Otkaz',
    );
    public $payedList = array(
        '0' => 'Не оплачен',
        '1' => 'Частично оплачен',
        '2' => 'Оплачено',
    );

    function __construct() {
        $this->statusList = array(
            '1' => Yii::t('shop_cart', 'Accepted for processing'),
            '2' => Yii::t('shop_cart', 'Check features'),
            '4' => Yii::t('shop_cart', 'Partial reserve'),
            '5' => Yii::t('shop_cart', 'Reserve'),
            '6' => Yii::t('shop_cart', 'Ready to issue'),
            '7' => Yii::t('shop_cart', 'Partially issued'),
            '8' => Yii::t('shop_cart', 'Done'),
            '9' => Yii::t('shop_cart', 'Failure'),
        );
        $this->statusTranslitList = array(
            '1' => Yii::t('shop_cart', 'Prinyat k robote'),
            '2' => Yii::t('shop_cart', 'Oformlen'),
            '4' => Yii::t('shop_cart', 'Chast. reserv'),
            '5' => Yii::t('shop_cart', 'Reserv'),
            '6' => Yii::t('shop_cart', 'Gotov k vudache'),
            '7' => Yii::t('shop_cart', 'Chast. vudan'),
            '8' => Yii::t('shop_cart', 'Vupolnen'),
            '9' => Yii::t('shop_cart', 'Otkaz'),
        );
        $this->payedList = array(
            '0' => Yii::t('shop_cart', 'Not paid'),
            '1' => Yii::t('shop_cart', 'Partly paid'),
            '2' => Yii::t('shop_cart', 'Paid'),
        );
    }

    public function getSearchList() {
        return array(
            '0' => Yii::t('shop_cart', 'All'),
            '1' => Yii::t('shop_cart', 'Accepted for processing'),
            '2' => Yii::t('shop_cart', 'Check features'),
            '4' => Yii::t('shop_cart', 'Partial reserve'),
            '5' => Yii::t('shop_cart', 'Reserve'),
            '6' => Yii::t('shop_cart', 'Ready to issue'),
            '7' => Yii::t('shop_cart', 'Partially issued'),
            '8' => Yii::t('shop_cart', 'Done'),
            '9' => Yii::t('shop_cart', 'Failure'),
        );
//        return array_merge(array(), $this->statusList);
    }

    public function afterChange($model, $new_status) {
        if ($new_status == 9)
            $model->updateOrder();
    }

    public function changeStatus($model, $new_status) {
        if ($this->get1CStatus($model)) {
            return $this->IC_ERROR;
        }
        $model->status = $new_status;
        $model->save();
        $this->afterChange($model, $new_status);
        $model->sendEmailNotification();

        return $this->STATUS_SUCCESS;
    }

}
