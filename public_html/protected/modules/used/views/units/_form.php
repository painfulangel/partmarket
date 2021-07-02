<?php
/* @var $this UnitsController */
/* @var $model UsedUnits */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'used-units-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'type' => 'horizontal',
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->dropDownListRow($model,'node_id', CHtml::listData(UsedNodes::model()->findAll(),'id', 'name'), array('prompt'=>'Выберите узел')); ?>
		
	<?php echo $form->textFieldRow($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		

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