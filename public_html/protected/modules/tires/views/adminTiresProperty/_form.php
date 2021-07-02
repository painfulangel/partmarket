<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'enableAjaxValidation' => false,
    'type'                 => 'horizontal',
));

	echo $form->errorSummary($model);
	
	echo $form->hiddenField($model, 'id_property');
	
	echo $form->textFieldRow($model, 'value', array('class' => 'span5', 'maxlength' => 255));
	
	echo $form->checkBoxRow($model, 'popular', array('class' => 'span1', 'maxlength' => 255));
?>
<div class="form-actions">
<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('tires', 'Add') : Yii::t('tires', 'Save'),
    ));
    
    echo CHtml::link(Yii::t('tires', 'Cancel'), array('property', 'id' => $model->id_property), array('class' => 'btn'));
?>
</div>
<style>
	a.btn {
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		margin-left: 10px;
	}
</style>
<?php $this->endWidget(); ?>