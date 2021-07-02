<?php
$this->pageTitle = Yii::t('shop_cart', 'Checkout');
$this->breadcrumbs = array(
   Yii::t('shop_cart', 'Basket')  => array('view'),
    Yii::t('shop_cart', 'Checkout'),
);
?>
<h1><?php echo Yii::t('shop_cart', 'Checkout'); ?></h1>
<?php echo $this->renderPartial('_view_order', array('model' => $model_cart)); ?>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>