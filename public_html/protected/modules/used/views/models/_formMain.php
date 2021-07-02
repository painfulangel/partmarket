<?php
/* @var $this ModelsController */
/* @var $model UsedModels */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'used-models-form',
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

	<div class="control-group">
		<?php echo $form->hiddenField($model, 'brand_id', array('value'=>$brand_id));?>
		<?php //echo $form->dropDownListRow($model,'brand_id', CHtml::listData(UsedBrands::model()->findAll(),'id', 'name'), array('prompt'=>'Выберите модель')); ?>
		<?php //echo $form->error($model,'brand_id'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->textFieldRow($model,'keywords',array('class'=>'span5')); ?>
		<?php echo $form->error($model,'keywords'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->textFieldRow($model,'description',array('class'=>'span5')); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="control-editor">
		<?php //echo $form->labelEx($model,'text'); ?>
		<?php
				//!!! Виджет, выводящий редактор нужного типа
				$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'text'));
				//!!! Виджет, выводящий редактор нужного типа
		?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="control-group">
		<?php //echo $form->labelEx($model,'image'); ?>
		<?php echo $form->fileField($model,'image', array('value'=>$model->image)); ?>
		<?php echo $form->error($model,'image'); ?>
		<?php echo ($model->image) ? '<div>Photo file name: '.$model->image.'</div>' : ''; ?>
	</div>

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