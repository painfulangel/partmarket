<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'prices-rules-form',
    'enableAjaxValidation' => false,
        ));
?>
<p class="help-block"><?= Yii::t('pricegroups', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('pricegroups', 'Indispensable to filling.') ?></p>


<?php echo $form->errorSummary($model); ?>

<?php echo $form->dropDownListRow($model, 'group_id', $priceGroupsList, array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'top_value', array('class' => 'span5')); ?>
<div class="control-group">
    <?= Yii::t('pricegroups', '0 - default price') ?>
    <div class="controls">
        <div class="input-append">
        </div>
    </div>
</div>
<?php // echo $form->labelEx($model, 'top_value_default', '0 - цена по умолчанию', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'koeficient', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'brand', array('class' => 'span5', 'maxlength' => 127)); ?>
<div class="control-group">
    <?= Yii::t('pricegroups', '0 - for all brands') ?>
    <div class="controls">
        <div class="input-append">
        </div>
    </div>
</div>
<?php //echo $form->labelEx($model, 'top_value_default', '0 - для всех брендов', array('class' => 'span5')); ?>


<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('pricegroups', 'Add') : Yii::t('pricegroups', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
