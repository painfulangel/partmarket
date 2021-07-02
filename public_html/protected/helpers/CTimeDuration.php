<?php

class CTimeDuration {

    public static function getList() {
        return array(
            '0' => Yii::t('shop_cart', 'Not specified'),
            '7' => Yii::t('shop_cart', '1 week'),
            '14' => Yii::t('shop_cart', '2 weeks'),
            '30' => Yii::t('shop_cart', '1 month'),
            '90' => Yii::t('shop_cart', '3 months'),
            '180' => Yii::t('shop_cart', '6 months'),
            '165' => Yii::t('shop_cart', 'year'),
        );
    }

}
