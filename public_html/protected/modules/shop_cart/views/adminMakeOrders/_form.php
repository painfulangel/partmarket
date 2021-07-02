<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'orders-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
        ));

echo CHtml::hiddenField('prepaid_type', '0.3', array('id' => 'prepaid_type_id'))
?>


<?php echo $form->errorSummary($model); ?>
<div class="control-group "><label class="control-label" for="Orders_payment_method"><?= Yii::t('shop_cart', 'UserSi') ?></label><div class="controls">
        <?php
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'name' => 'city',
            'source' => $model->getUsersAutoComplete(),
            'options' => array(
                'minLength' => '1',
                'select' => 'js: function(event, ui) {
            this.value = ui.item.label;
            $(".reload_users").val(ui.item.id);
            return false;
        }',
                'showAnim' => 'fold',
            ),
            'htmlOptions' => array(
                'class' => 'span5',
                'style' => 'height:20px;',
            ),
        ));
        ?>
    </div></div>

<?php echo $form->hiddenField($model, 'user_id', array('class' => 'span5 reload_users')); ?>
<?php // echo $form->dropDownListRow($model, 'user_id', CHtml::listData(UserProfile::model()->findAllByAttributes(array()), 'uid', 'fullNameOrg'), array('class' => 'span5')); ?>
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

<div class="form-actions">


    <?php
    /*if ($model->isPrePayOrder()) {
        echo CHtml::link(Yii::t('shop_cart', 'Checkout'), '#', array('onclick' => 'ShowSubmitPrePayWindow();return false;', 'class' => 'btn btn-primary'));
    } else*/
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('shop_cart', 'Checkout') : Yii::t('shop_cart', 'Save'),
        ));
    ?>
</div>

<?php
$delivery_name_method1 = Yii::app()->controller->module->delivery_model->PAYMENT_GET_METHOD;
$delivery_name_method3 = Yii::app()->controller->module->delivery_model->PAYMENT_GET_METHOD;
$delivery_name_method4 = Yii::app()->controller->module->delivery_model->PAYMENT_GET_METHOD;
$delivery_name_method2 = Yii::app()->controller->module->delivery_model->POST_METHOD;
$orderTotalBlockUrl = Yii::app()->createAbsoluteUrl('/shop_cart/orders/getOrderTotalBlock');

$script = <<<SCRIPT
           function ShopChangeDeliveryMethod() {
    value = $('#delivery_method_id').val();
    if (value == '$delivery_name_method1' || value == '$delivery_name_method2' || value == '$delivery_name_method3' || value == '$delivery_name_method4')
       $('#adress_block').show();
    else
         $('#adress_block').hide();
    $('#order_total_price').load('$orderTotalBlockUrl?type=' + value.replace(/ /g,'+'));
                
}

ShopChangeDeliveryMethod(); 
 
SCRIPT;
Yii::app()->clientScript->registerScript(__CLASS__ . "#detailSearch", $script, CClientScript::POS_END);
?>

<?php $this->endWidget(); ?>
