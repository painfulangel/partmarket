<?php
	echo $form->errorSummary($model);
	echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255));

	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'description'));
	//!!! Виджет, выводящий редактор нужного типа

	echo $form->textFieldRow($model, 'commission', array('class' => 'span5'));
	echo $form->textFieldRow($model, 'system_login', array('class' => 'span5'));
	echo $form->textFieldRow($model, 'system_password', array('class' => 'span5'));
	echo $form->textFieldRow($model, 'system_extra_parametr', array('class' => 'span5'));
	echo $form->checkBoxRow($model, 'active_state', array('class' => 'span1'));
	echo $form->checkBoxRow($model, 'show_balance', array('class' => 'span1'));
	echo $form->checkBoxRow($model, 'show_order', array('class' => 'span1'));
	echo $form->checkBoxRow($model, 'show_prepay', array('class' => 'span1'));
	
	//echo $form->textFieldRow($model, 'system_name', array('class' => 'span5', 'maxlength' => 255)); 
?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('webPayments', 'Add') : Yii::t('webPayments', 'Save'),
    ));
    ?>
</div>