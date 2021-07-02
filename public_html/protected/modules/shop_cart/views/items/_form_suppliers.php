<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'items-supplier-form',
    'action' => array('/shop_cart/adminItems/getSupplierOrderFile'),
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
    'htmlOptions' => array('target' => '_blank'),
        ));
?>

<div id="items-supplier-form-elements" style="display: none;">
</div>

<div class="form-actions">
    <?php
    echo CHtml::link(Yii::t('shop_cart', 'Download CSV'), '', array('class' => 'btn btn-primary', 'onclick' => 'ShopCartGetSupplierOrder()'));
    ?>
</div>

<?php $this->endWidget(); ?>
