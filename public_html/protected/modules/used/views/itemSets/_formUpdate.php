<?php
/* @var $this ItemSetsController */
/* @var $model UsedItemSets */
/* @var $form CActiveForm */
?>
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
<div class="form" id="set-form">

	<?php echo TbHtml::activeDropDownListControlGroup($model,'brand_item_id', $model->getBrandsItem(), array('prompt'=>'Выберите производителя')); ?>

		<?php echo TbHtml::activeTextFieldControlGroup($model,'name', array('required'=>'required')); ?>

		<?php echo TbHtml::activeTextFieldControlGroup($model,'vendor_code',array('size'=>60,'maxlength'=>255)); ?>
		
		<?php //echo TbHtml::activeTextFieldControlGroup($model,'original_num',array('size'=>60,'maxlength'=>255)); ?>
		
		<?php echo TbHtml::activeTextFieldControlGroup($model,'replacement',array('size'=>60,'maxlength'=>255)); ?>
		
		<?php //echo TbHtml::activeRadioButtonListControlGroup($model,'type', $items->getTypes()); ?>
		
		<?php //echo TbHtml::activeDropDownListControlGroup($model,'state', $items->getStates()); ?>
		
		<?php echo TbHtml::activeTextAreaControlGroup($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
		
		<?php echo TbHtml::activeTextFieldControlGroup($model,'price',array('size'=>8,'maxlength'=>8)); ?>

		<?php echo TbHtml::activeTextFieldControlGroup($model,'delivery_time'); ?>
		
		<?php echo TbHtml::activeTextFieldControlGroup($model,'availability'); ?>

</div><!-- form -->


<div class="control-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>