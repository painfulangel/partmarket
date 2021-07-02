
<p class="help-block"><?= Yii::t('news', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('news', 'Indispensable to filling.') ?></p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'title', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php
	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'text'));
	//!!! Виджет, выводящий редактор нужного типа
?>

<?php echo $form->textFieldRow($model, 'short_title', array('class' => 'span5', 'maxlength' => 127)); ?>

<?php echo $form->textAreaRow($model, 'short_text', array('rows' => 11, 'cols' => 50, 'class' => 'redactor')); ?>

<?php echo $form->textFieldRow($model, 'link', array('class' => 'span5', 'maxlength' => 127)); ?>

<?php echo $form->textFieldRow($model, 'keywords', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'description', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->checkBoxRow($model, 'active_state', array('class' => '')); ?>

<?php echo $form->checkBoxRow($model, 'visibility_state', array('class' => '')); ?>