<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>127)); ?>

	<?php echo $form->textFieldRow($model,'parser_class',array('class'=>'span5','maxlength'=>127)); ?>

	<?php echo $form->textFieldRow($model,'price_group_1',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'price_group_2',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'price_group_3',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'price_group_4',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'active_state',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'delivery',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'supplier_inn',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'supplier',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'create_date',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'currency',array('class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'codeblock',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
