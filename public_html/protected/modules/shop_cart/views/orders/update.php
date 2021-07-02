<?php
$this->breadcrumbs = array(
    Yii::t('shop_cart', 'My orders') => array('index'),
    Yii::t('shop_cart', 'Edit order'),
);
$this->pageTitle = Yii::t('shop_cart', 'Edit order');
?>

<h1><?= Yii::t('shop_cart', 'Edit order') ?></h1>


<?php echo $this->renderPartial('_order_info', array('model' => $model, 'orderStatus' => $orderStatus)); ?>
<?php echo $this->renderPartial('_edit_form', array('model' => $model)); ?>

<?php echo $this->renderPartial('_order_items', array('model' => $model, 'itemStatus' => $itemStatus)); ?>
