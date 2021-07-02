<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id' => 'crosses-table-form',
		'enableAjaxValidation' => false,
));
?>

<p class="help-block"><?= Yii::t('crosses', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('crosses', 'Indispensable to filling.') ?></p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 45)); ?>

<?php echo $form->CheckBoxRow($model, 'active_state', array('class' => '')); ?>
<?php echo $form->CheckBoxRow($model, 'garanty', array('class' => '')); ?>
<?php echo $form->CheckBoxRow($model, 'look_for_coincidence', array('class' => '')); ?>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('crosses', 'Add') : Yii::t('crosses', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>