<?php
/* @var $this BrandsController */
/* @var $model UsedBrands */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'used-brands-form',
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

	<div class="control-group ">
		<?php echo $form->labelEx($model,'name', array('class' => 'control-label')); ?>
		<div class="controls">
		<?php echo $form->textField($model,'name',array('class'=>'span5','maxlength'=>255)); ?>
		</div>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'title',array('class' => 'control-label')); ?>
		<div class="controls">
		<?php echo $form->textField($model,'title',array('class'=>'span5','maxlength'=>255)); ?>
		</div>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'keywords',array('class' => 'control-label')); ?>
		<div class="controls">
		<?php echo $form->textField($model,'keywords',array('class'=>'span5')); ?>
		</div>
		<?php echo $form->error($model,'keywords'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'description',array('class' => 'control-label')); ?>
		<div class="controls">
		<?php echo $form->textField($model,'description',array('class'=>'span5')); ?>
		</div>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="control-editor">
		<?php //echo $form->labelEx($model,'text'); ?>
		<?php
				//!!! Виджет, выводящий редактор нужного типа
				$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'text'));
				//!!! Виджет, выводящий редактор нужного типа
		?>
		<?php //echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'image', array('class' => 'control-label')); ?>
		<?php if($model->image):?>
			<?php echo CHtml::image("/uploads/brands/".$model->image, 'img', array("style"=>"width:100px;"));?>
			<br>
			<a href="#" onclick="$('#hidden-file').toggle();return false;">Изменить лого</a><br>
			<div class="controls" id="hidden-file" style="display: none;">
				<?php echo $form->fileField($model,'image'); ?>
			</div>
		<?php else:?>
			<div class="controls">
				<?php echo $form->fileField($model,'image'); ?>
			</div>
		<?php endif;?>

		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class="control-group">
		<?php //echo $form->labelEx($model,'sort'); ?>
		<?php //echo $form->textField($model,'sort'); ?>
		<?php //echo $form->error($model,'sort'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'status', array('class' => 'control-label')); ?>
		<?php echo $form->checkbox($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
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