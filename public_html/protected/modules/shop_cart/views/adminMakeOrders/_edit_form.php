<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'orders-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
        ));
?>

<?php echo $form->errorSummary($model); ?>

<?php //echo $form->dropDownListRow($model, 'payment_method', Yii::app()->controller->module->payment_model->getList(), array('class' => 'span5', 'disabled' . $model->isFormEnabled() => 'on')); ?>

<?php echo $form->dropDownListRow($model, 'delivery_method', Yii::app()->controller->module->delivery_model->getList(), array('class' => 'span5', 'disabled' . ($model->status != 1 ? '' : '11')=>'on', 'onchange' => 'ShopChangeDeliveryMethod()', 'id' => 'delivery_method_id', 'disabled' . $model->isFormEnabled() => 'on')); ?>

<div id="adress_block">
    <div class="control-group ">
        <div class="controls">
            <?php echo $form->labelEx($model, 'delivery_name'); ?>
        </div>
    </div>
    <?php echo $form->textFieldRow($model, 'city', array('class' => 'span5', 'disabled' . ($model->status != 1 ? '' : '11')=>'on', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'street', array('class' => 'span5', 'disabled' . ($model->status != 1 ? '' : '11')=>'on', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'house', array('class' => 'span5', 'disabled' . ($model->status != 1 ? '' : '11')=>'on', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'country', array('class' => 'span5', 'disabled' . ($model->status != 1 ? '' : '11')=>'on', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'zipcode', array('class' => 'span5', 'disabled' . ($model->status != 1 ? '' : '11')=>'on', 'maxlength' => 255)); ?>
</div>


<?php echo $form->textFieldRow($model, 'user_description', array('class' => 'span5', 'maxlength' => 255)); ?>



<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('shop_cart', 'Add') : Yii::t('shop_cart', 'Save'),
    ));
    ?>
</div>

<?php
$delivery_name_method1 = Yii::app()->controller->module->delivery_model->PAYMENT_GET_METHOD;
$delivery_name_method2 = Yii::app()->controller->module->delivery_model->POST_METHOD;
$orderTotalBlockUrl = Yii::app()->createAbsoluteUrl('/shop_cart/orders/getOrderTotalBlock');

$script = <<<SCRIPT
           function ShopChangeDeliveryMethod() {
    value = $('#delivery_method_id').val();
    if (value == '$delivery_name_method1' || value == '$delivery_name_method2')
       $('#adress_block').show();
    else
         $('#adress_block').hide();
                
}

ShopChangeDeliveryMethod();

SCRIPT;
Yii::app()->clientScript->registerScript(__CLASS__ . "#ShopCartOrdersForm", $script, CClientScript::POS_END);
?>

<?php $this->endWidget(); ?>


