<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'type_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'descr',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'box',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'comment',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'article',array('class'=>'span5','maxlength'=>127)); ?>

	<?php echo $form->textFieldRow($model,'search',array('class'=>'span5','maxlength'=>127)); ?>

	<?php echo $form->textFieldRow($model,'brand_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'seo_title',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'seo_kwords',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'seo_descr',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'slug',array('class'=>'span5','maxlength'=>127)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
