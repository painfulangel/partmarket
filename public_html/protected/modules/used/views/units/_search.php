<?php
/* @var $this UnitsController */
/* @var $model UsedUnits */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'type' => 'horizontal',
)); ?>


	<?php echo $form->textFieldRow($model,'id'); ?>


	<?php echo $form->dropDownListRow($model,'node_id', CHtml::listData(UsedNodes::model()->findAll(), 'id','name'), array('prompt'=>'Выберите узел')); ?>


	<?php echo $form->textFieldRow($model,'name',array('size'=>60,'maxlength'=>255)); ?>


	<div class="control-group  buttons">
		<?php echo CHtml::submitButton(Yii::t(UsedModule::TRANSLATE_PATH,'Search'), array('class'=>'btn btn-success', 'style'=>'margin-left:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->