<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'type' => 'horizontal',
    'id' => 'users-cars-form',
    'enableAjaxValidation' => false,
        ));
?>
<p class="help-block"><?= Yii::t('userControl', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('userControl', 'Indispensable to filling.') ?></p>


<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'model', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'brand', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'vin', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'year', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'body', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'engine_v', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'engine_t', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'transsmition', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textAreaRow($model, 'comment', array('rows' => 6, 'cols' => 50, 'class' => 'span5')); ?>

<?php
if (!$model->isNewRecord)
    echo $form->textAreaRow($model, 'suggestion', array('rows' => 6, 'cols' => 50, 'class' => 'span5', 'disabled' => 'on'));
?>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('userControl', 'Add') : Yii::t('userControl', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
