<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'web-payments-robokassa-form',
    'action' => 'https://merchant.roboxchange.com/Index.aspx',
    'enableAjaxValidation' => false,
        ));
?>
<div class="control-group " style="display: none;">
    <b>Сумма к оплате:</b>
    <?php echo Yii::app()->getModule('currencies')->getDefaultPrice($model->total_value) ?>
</div>
<?php
    // регистрационная информация - логин
    echo CHtml::hiddenField('MrchLogin', $model->system_login);
    // сумма заказа
    echo CHtml::hiddenField('OutSum', $model->total_value);
    // номер заказа
    echo CHtml::hiddenField('InvId', $model->id);
    // описание заказа
    echo CHtml::hiddenField('Desc', $model->description);
    // формирование подписи
    echo CHtml::hiddenField('SignatureValue', $model->getSign(1));
    // тип товар
    echo CHtml::hiddenField('Shp_item', $model->id);
    echo CHtml::hiddenField('IncCurrLabel', '');
    // язык
    echo CHtml::hiddenField('Culture', 'ru');
?>
<div class="form-actions">
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