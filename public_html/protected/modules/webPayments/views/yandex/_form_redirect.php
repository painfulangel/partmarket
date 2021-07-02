<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'web-payments-robokassa-form',
    'action' => 'https://money.yandex.ru/eshop.xml',
    'enableAjaxValidation' => false,
        ));
?>
<div class="control-group ">
    <b>Сумма к оплате:</b>
    <?= Yii::app()->getModule('currencies')->getDefaultPrice($model->total_value) ?>

</div>
<?php echo CHtml::hiddenField('shopId', $model->system_login); ?>
<?php echo CHtml::hiddenField('scid', $model->system_extra_parametr); ?>
<?php echo CHtml::hiddenField('sum', $model->total_value); ?>
<?php // echo CHtml::hiddenField('Desc', $model->description); ?>
<?php echo CHtml::hiddenField('customerNumber', $model->user_id); ?>
<?php echo CHtml::hiddenField('orderNumber', $model->id); ?>
<?php // echo CHtml::hiddenField('IncCurrLabel', ''); ?>
<?php // echo CHtml::hiddenField('Culture', 'ru'); ?>

<div class="form-actions" style="display: none;">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('webPayments', 'Proceed to payment'),
    ));
    ?>
</div>


<?php $this->endWidget(); ?>

<script>
    document.getElementById('web-payments-robokassa-form').submit();
</script>
