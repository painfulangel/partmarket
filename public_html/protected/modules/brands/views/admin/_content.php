<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 45)); ?>
<?php
	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'description'));
	//!!! Виджет, выводящий редактор нужного типа
?>
<?php if (!empty($model->image)) { ?>
    <div class="control-group">
        <div class="controls">
            <?php echo CHtml::image('/'.$model->getImage('big'), $model->name, array('style' => 'max-height: 150px;')) ?>
        </div>
    </div>
<?php } ?>
<?php echo $form->fileFieldRow($model, '_image'); ?>
<?php echo $form->textAreaRow($model, 'synonym', array('style' => 'width: 300px; height: 100px;')); ?>
<?php echo $form->CheckBoxRow($model, 'hide'); ?>
<?php echo $form->CheckBoxRow($model, 'active_state'); ?>