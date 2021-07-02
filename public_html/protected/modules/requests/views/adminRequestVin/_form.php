<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'request-vin-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
        ));
?>


<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'vin', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'car_model', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'car_year', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'engine_model', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'body', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'phone', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textAreaRow($model, 'text', array('rows' => 6, 'cols' => 50, 'class' => 'span5')); ?>

<?php echo $form->textAreaRow($model, 'comment', array('rows' => 6, 'cols' => 50, 'class' => 'span5')); ?>

<?php echo $form->CheckBoxRow($model, 'work_state', array('class' => '')); ?>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('requests', 'Add') : Yii::t('requests', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
