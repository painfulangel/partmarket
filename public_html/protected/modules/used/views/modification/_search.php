<?php
/* @var $this ModificationController */
/* @var $model UsedMod */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'type' => 'horizontal',
)); ?>

		<?php echo $form->textFieldRow($model,'id'); ?>

		<?php echo $form->dropDownListRow($model,'brand_id', CHtml::listData(UsedBrands::model()->findAll(), 'id','name'), array('prompt'=>'Выберите бренд')); ?>

		<?php echo $form->dropDownListRow($model,'model_id', CHtml::listData(UsedModels::model()->findAll(), 'id','name'), array('prompt'=>'Выберите модель')); ?>

		<?php echo $form->textFieldRow($model,'name',array('size'=>60,'maxlength'=>255)); ?>



	<div class="control-group  buttons">
		<?php echo CHtml::submitButton(Yii::t(UsedModule::TRANSLATE_PATH,'Search'), array('class'=>'btn btn-success', 'style'=>'margin-left:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->