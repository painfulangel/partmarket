<?php
/* @var $this ModificationController */
/* @var $model UsedMod */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'used-mod-form',
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

	<?php echo $form->dropDownListRow(
				$model,
				'brand_id',
				CHtml::listData(UsedBrands::model()->findAll(),'id', 'name'),
				array(
					'prompt'=>'Выберите бренд',
					'ajax' => array(
						'type'=>'POST', //request type
						'url'=>CController::createUrl('/used/modification/listModels'),
						'update'=>'#UsedMod_model_id', //selector to update
						//'data'=>'js:javascript statement' 
					)
				)); ?>
	<?php echo $form->error($model,'brand_id'); ?>

	<?php echo $form->dropDownListRow($model,'model_id', CHtml::listData(UsedModels::model()->findAll(),'id', 'name'), array('prompt'=>'Выберите модель')); ?>
	<?php echo $form->error($model,'model_id'); ?>

	<?php echo $form->textFieldRow($model,'name',array('size'=>60,'maxlength'=>255)); ?>
	<?php echo $form->error($model,'name'); ?>

	<?php echo $form->textFieldRow($model,'title',array('size'=>60,'maxlength'=>255)); ?>
	<?php echo $form->error($model,'title'); ?>


	<?php echo $form->textFieldRow($model,'keywords',array('rows'=>6, 'cols'=>50)); ?>
	<?php echo $form->error($model,'keywords'); ?>


		<?php echo $form->textFieldRow($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>


		<div class="control-group">
		<?php
				//!!! Виджет, выводящий редактор нужного типа
				$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'text'));
				//!!! Виджет, выводящий редактор нужного типа
		?>
		<?php echo $form->error($model,'text'); ?>
		</div>

		<?php echo $form->fileFieldRow($model,'image',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'image'); ?>


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