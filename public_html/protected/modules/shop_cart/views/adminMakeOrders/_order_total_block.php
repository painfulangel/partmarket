<?php
$delivery_price = Yii::app()->controller->module->delivery_model->getDeliveryPrice($type, array('weight' => Yii::app()->controller->module->getWeightTotal()));
if ($delivery_price > 0) {
    ?>
    <b><?= Yii::t('shop_cart', 'Shipping cost:') ?></b> 
    <?= Yii::app()->controller->module->getPriceFormatFunction($delivery_price) ?>
    <br/>
    <?php
}
?>
<b><?= Yii::t('shop_cart', 'Total:') ?></b> 

<?= Yii::app()->controller->module->getPriceTotal($delivery_price) ?>