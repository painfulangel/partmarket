<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('shop_cart', 'Orders') => array('admin'), Yii::t('shop_cart', 'Print order')));

$this->pageTitle = Yii::t('shop_cart', 'Print order');
?>
<div class="span8">
    <?php echo $this->renderPartial('_print_order_info', array('model' => $model, 'orderStatus' => $orderStatus)); ?>
    <?php echo $this->renderPartial('_print_form', array('model' => $model)); ?>
    <?php echo $this->renderPartial('_print_order_items', array('model' => $model, 'itemStatus' => $itemStatus)); ?>
    <div>
        <?php
        echo CHtml::link(Yii::t('shop_cart', 'Print'), '', array('class' => 'btn print_clear', 'onclick' => 'ShopChangePrintOrder()'));
        ?>
    </div>
</div>

