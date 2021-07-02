<?php
$this->breadcrumbs = array(
	Yii::t('menu', 'Messages') => array('/userControl/userMessages/index'),
	Yii::t('messages', 'Write new message')
);

$this->pageTitle = Yii::t('messages', 'Write new message');
?>
<h1><?php echo Yii::t('messages', 'Write new message'); ?></h1>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'new-message-form',
	'action' => Yii::app()->createUrl($this->route),
	'enableAjaxValidation' => false,
	'enableClientValidation' => true,
	'type' => 'horizontal',
	'htmlOptions' => array('enctype' => 'multipart/form-data'),	
));
?>
<?php /* ?><label><?php echo Yii::t('messages', 'Fields containing'); ?> <span class="required">*</span> <?php echo Yii::t('messages', 'Indispensable to filling.'); ?></label><?php */ ?>
<?php echo $form->errorSummary($model); ?>
<?php echo $form->textFieldRow($model, 'theme', array('class' => 'span5', 'maxlength' => 255)); ?>
<?php echo $form->textAreaRow($model, 'message', array('class' => 'span5')); ?>
<?php echo $form->fileFieldRow($model, '_attachment'); ?>
<div class="form-actions">
<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('messages', 'Save'),
    ));
?>
</div>
<?php $this->endWidget(); ?>