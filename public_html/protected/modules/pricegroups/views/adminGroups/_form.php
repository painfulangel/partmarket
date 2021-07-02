<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'prices-rules-groups-form',
    'enableAjaxValidation' => false,
        ));
?>

<p class="help-block"><?= Yii::t('pricegroups', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('pricegroups', 'Indispensable to filling.') ?></p>

<?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5')); ?>

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
