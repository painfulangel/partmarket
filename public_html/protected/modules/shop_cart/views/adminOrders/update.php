<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('shop_cart', 'Orders') => array('admin'), Yii::t('shop_cart', 'Edit order')));

$this->pageTitle = Yii::t('shop_cart', 'Edit order');
?>
<h1><?php echo Yii::t('shop_cart', 'Edit order') ?></h1>
<?php
	echo $this->renderPartial('_order_info', array('model' => $model, 'orderStatus' => $orderStatus));
	echo $this->renderPartial('_form', array('model' => $model));
?>
<div>
<?php
    if (!$model->checkDone()) {
        echo CHtml::link(Yii::t('shop_cart', 'To add a position'), '', array('class' => 'btn ', 'onclick' => 'ShopCartAddNewItem()')).'&nbsp;';
        echo CHtml::link(Yii::t('shop_cart', 'Unite orders'), '', array('class' => 'btn ', 'onclick' => 'ShopCartMergeOrders()')).'&nbsp;';
    }
    
    if (!$model->confirmed) {
    	echo CHtml::link(Yii::t('shop_cart', 'Confirm order'), '', array('class' => 'btn btn-confirm', 'onclick' => 'ConfirmOrder('.$model->primaryKey.')'));
    }
?>
</div>
<?php
	echo $this->renderPartial('_quick_item_form', array('model' => $model->getNewItem()));
	echo $this->renderPartial('_merge_form', array('model' => $model));
	echo $this->renderPartial('_order_items', array('model' => $model, 'itemStatus' => $itemStatus));
?>