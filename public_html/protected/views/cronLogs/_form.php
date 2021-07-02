<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'cron-logs-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Поля содержащие <span class="required">*</span> обязательны к заполнению.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textAreaRow($model,'text',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'task',array('class'=>'span5','maxlength'=>127)); ?>

	<?php echo $form->textFieldRow($model,'create_time',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
