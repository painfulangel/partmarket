<?php
$delivery_price = 0;

if (is_object($dt)) {
    $delivery_price = $dt->price;
    
    if ($delivery_price > 0) {
?>
    <b><?php echo Yii::t('shop_cart', 'Shipping cost:') ?></b> 
    <?php echo Yii::app()->controller->module->getPriceFormatFunction($delivery_price) ?>
    <br/><br/>
<?php
    }
}
?>
<b><?php echo Yii::t('shop_cart', 'Total:') ?></b> 

<?php echo Yii::app()->controller->module->getPriceTotal($delivery_price) ?>