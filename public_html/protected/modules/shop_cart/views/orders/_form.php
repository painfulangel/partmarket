<?php
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	    'id' => 'orders-form',
	    'enableAjaxValidation' => false,
	    'type' => 'horizontal',
	));
	
	echo CHtml::hiddenField('prepaid_type', '0.3', array('id' => 'prepaid_type_id'));
?>
<?php echo $form->errorSummary($model); ?>
<?php //echo $form->dropDownListRow($model, 'payment_method', Yii::app()->controller->module->payment_model->getList(), array('class' => 'span5')); ?>
<?php echo $form->dropDownListRow($model, 'delivery_method', Yii::app()->controller->module->delivery_model->getList(), array('class' => 'span5', 'onchange' => 'ShopChangeDeliveryMethod()', 'id' => 'delivery_method_id')); ?>
<?php echo $form->textFieldRow($model, 'user_description', array('class' => 'span5', 'maxlength' => 255)); ?>

<div id="adress_block">
    <div class="control-group ">
        <div class="controls">
            <?php echo $form->labelEx($model, 'delivery_name'); ?>
        </div>
    </div>
    <?php echo $form->textFieldRow($model, 'city', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php echo $form->textFieldRow($model, 'street', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php echo $form->textFieldRow($model, 'house', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php echo $form->textFieldRow($model, 'country', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php echo $form->textFieldRow($model, 'zipcode', array('class' => 'span5', 'maxlength' => 255)); ?>
</div>

<div id="transport_block">
    <div class="control-group ">
        <div class="controls">
            <?php echo $form->labelEx($model, 'id_delivery_transport'); ?>
        </div>
    </div>
    <?php echo $form->dropDownListRow($model, 'id_delivery_transport', DeliveryTransport::model()->selectList(), array('class' => 'span5', 'empty' => '')); ?>
    <?php echo $form->textFieldRow($model, 'sender_name', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php //echo $form->textFieldRow($model, 'sender_phone', array('class' => 'span5', 'maxlength' => 255)); ?>
    <div class="control-group ">
        <?php echo $form->labelEx($model, 'sender_phone', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('CMaskedTextField', array(
                'model' => $model,
                'attribute' => 'sender_phone',
                'mask' => '+7 (999) 999-9999',
                'htmlOptions' => array('class' => 'span5')
            ));
            ?>
        </div>
        <?php echo $form->error($model, 'phone'); ?>
    </div>
    <?php echo $form->textFieldRow($model, 'passport_data', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php echo $form->textFieldRow($model, 'country_city', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php echo $form->textAreaRow($model, 'terminal', array('class' => 'span5', 'maxlength' => 255)); ?>
    <div class="control-group attention">
    	<p><?php echo Yii::t('delivery', 'Attention! The cost of delivery by transport company is paid by the client when receiving.'); ?></p>
    	<p><?php echo Yii::t('delivery', 'Continuing the registration of the order you confirm your consent to the processing of personal data.'); ?></p>
    </div>
</div>
<div class="form-actions">
<?php
    /*if ($model->isPrePayOrder()) {
        echo CHtml::link('Оформить', '#', array('onclick' => 'ShowSubmitPrePayWindow();return false;', 'class' => 'btn btn-primary'));
    } else*/
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('shop_cart', 'Checkout') : Yii::t('shop_cart', 'Save'),
        ));
?>
</div>
<?php
/*$delivery_name_method1 = Yii::app()->controller->module->delivery_model->PAYMENT_GET_METHOD;
$delivery_name_method3 = Yii::app()->controller->module->delivery_model->PAYMENT_GET_METHOD;
$delivery_name_method4 = Yii::app()->controller->module->delivery_model->PAYMENT_GET_METHOD;
$delivery_name_method2 = Yii::app()->controller->module->delivery_model->POST_METHOD;*/
$orderTotalBlockUrl = Yii::app()->createAbsoluteUrl('/shop_cart/orders/getOrderTotalBlock');
$orderTotalBlockUrl2 = Yii::app()->createAbsoluteUrl('/shop_cart/orders/getOrderTransportTotalBlock');

$script = "
function ShopChangeDeliveryMethod() {
	var value = parseInt($('#delivery_method_id').val());
		
    if (value == 2) {
        $('#adress_block').hide();
        $('#transport_block').hide();
    } else if (value == 3) {
        $('#adress_block').hide();
        $('#transport_block').show();
    } else {
        $('#adress_block').show();
        $('#transport_block').hide();
	}

	$('#order_total_price').load('$orderTotalBlockUrl?type=' + value);
}

ShopChangeDeliveryMethod();

$('#Orders_id_delivery_transport').change(function() {
    var value = $(this).val();
    if (value == '') value = 0;

    $('#order_total_price').load('$orderTotalBlockUrl2?transport=' + value);
});
";

Yii::app()->clientScript->registerScript("detailSearch", $script, CClientScript::POS_END);

$this->endWidget();
?>