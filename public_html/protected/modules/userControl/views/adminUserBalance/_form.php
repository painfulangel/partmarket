<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'user-balance-operations-form',
    'action' => array('/userControl/adminUserBalance/create', 'id' => $model->user_id),
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
        ));
?>
<?php echo $form->errorSummary($model); ?>
<div class="control-group ">
    <?= Yii::t('userControl', 'Balance user') ?>: <b><?= Yii::app()->getModule('currencies')->getFormatPrice($model->getBalance()) ?></b>
</div>
<?php echo $form->textFieldRow($model, 'value', array('class' => 'span5')); ?>
<div class="control-group ">
    <?= Yii::t('userControl', 'Amount> 0 Recharge, sum <0 debit.') ?> 
    <div class="controls">

    </div>

</div>
<?php echo $form->textFieldRow($model, 'comment', array('class' => 'span5', 'maxlength' => 255)); ?>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('userControl', 'Deposit') : Yii::t('userControl', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
