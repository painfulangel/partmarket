<?php
$this->breadcrumbs = array(Yii::t('menu', 'Messages') => '/userControl/userMessages/index',
						   $dialog->theme => array('/userControl/userMessages/messages', 'id_dialog' => $dialog->primaryKey),
						   Yii::t('messages', 'Answer message'));

$this->pageTitle = Yii::t('messages', 'Answer message');
?>
<h1><?php echo Yii::t('messages', 'Answer message') ?></h1>
<?php
//TbActiveForm
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'new-message-form',
	'enableAjaxValidation' => false,
	'enableClientValidation' => true,
	'type' => 'horizontal',
	'htmlOptions' => array('enctype' => 'multipart/form-data'),	
));
?>
<?php echo $form->errorSummary($model); ?>
<?php echo $form->hiddenField($model, 'parent_id'); ?>
<?php echo $form->hiddenField($model, 'user_dialog_id'); ?>
<?php echo $form->textAreaRow($model, 'message', array('class' => 'span5', 'rows' => 10)); ?>
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