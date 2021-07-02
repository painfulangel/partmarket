<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'request-get-price-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
        ));
?>


<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'vin', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'brand', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'car_model', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'car_brand', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'car_year', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'email_phone', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'detail', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textAreaRow($model, 'comment', array('rows' => 6, 'cols' => 5, 'class' => 'span5')); ?>

<?php echo $form->checkBoxRow($model, 'work_state', array('class' => '')); ?>

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
