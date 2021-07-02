<?php
/* @var $this BrandsItemsController */
/* @var $model UsedBrandsItems */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'used-brands-items-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'type' => 'horizontal',
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data'
	)
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('size'=>60,'maxlength'=>255)); ?>


	<?php echo $form->fileFieldRow($model,'image'); ?>

	<?php //echo $form->textFieldRow($model,'sort'); ?>


	<div class="form-actions">
		<?php
		$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type' => 'primary',
			'label' => $model->isNewRecord ? Yii::t(UsedModule::TRANSLATE_PATH, 'Add') : Yii::t(UsedModule::TRANSLATE_PATH, 'Save'),
		));
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->