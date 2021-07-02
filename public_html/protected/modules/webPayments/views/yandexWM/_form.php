<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'web-payments-robokassa-form',
    'enableAjaxValidation' => false,
        ));
?>


<?php echo $form->errorSummary($model); ?>


<?php echo $form->textFieldRow($model, 'value', array('class' => 'span5', 'id' => 'sum_to_pay', 'onkeyup' => 'WebPaymentsChangeSum(' . $model->commission . ')')); ?>


<div class="control-group ">
    <?php echo $form->labelEx($model, 'total_value', array('class' => 'control-label')); ?>
    <div class="controls" id="total_pay_sum">
        0
    </div>
    <?php echo $form->error($model, 'phone'); ?>
</div>
<?php
$script = <<<SCRIPT
               function WebPaymentsChangeSum(koef){

               $('#total_pay_sum').html(($('#sum_to_pay').val()*1+$('#sum_to_pay').val()*koef));
        }
        WebPaymentsChangeSum($model->commission);
                
SCRIPT;
Yii::app()->clientScript->registerScript(__CLASS__ . "#WebPaymentsChangeSum", $script, CClientScript::POS_END);
?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('crosses', 'Checkout') : Yii::t('crosses', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
