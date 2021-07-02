<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'users-api-access-form',
    'enableAjaxValidation' => false,
        ));
?>


<?php echo $form->errorSummary($model); ?>

<?php // echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?>

<?php echo $form->textFieldRow($model, 'access_token', array('class' => 'span5', 'maxlength' => 127)); ?>

    <?php echo $form->checkBoxRow($model, 'active_state', array('class' => '')); ?>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('userControl', 'Create') : Yii::t('userControl', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
