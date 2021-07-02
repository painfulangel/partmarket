<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'car_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>127)); ?>

	<?php echo $form->textFieldRow($model,'sort',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'is_active',array('class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'content',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>127)); ?>

	<?php echo $form->textFieldRow($model,'kwords',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'descr',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'img',array('class'=>'span5','maxlength'=>127)); ?>

	<?php echo $form->textFieldRow($model,'seo_text',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'slug',array('class'=>'span5','maxlength'=>127)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
