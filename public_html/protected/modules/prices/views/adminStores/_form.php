<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'stores-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
        ));
?>
<p class="help-block"><?= Yii::t('prices', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('prices', 'Indispensable to filling.') ?></p>
<?php
	echo $form->errorSummary($model);
	echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 127));

	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'description'));
	//!!! Виджет, выводящий редактор нужного типа

	echo $form->checkBoxRow($model, 'count_state', array('class' => ''));
	echo $form->checkBoxRow($model, 'auto_delete_state', array('class' => ''));
	echo $form->checkBoxRow($model, 'my_available', array('class' => '', 'hint' => Yii::t('prices', 'By search of details only details from this price types if in these prices there are no details will be given, then we look for in other sources')));
	echo $form->checkBoxRow($model, 'top');
	echo $form->checkBoxRow($model, 'highlight');
	echo $form->checkBoxRow($model, 'prepay');
	
	$this->renderPartial('_getFormBlock', array(
	    'model' => $model,
	    'priceGroupsList' => $priceGroupsList,
	    'currencies' => $currencies,
	));
?>
<div class="form-actions">
<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('prices', 'Add') : Yii::t('prices', 'Save'),
    ));
    
    echo CHtml::link(Yii::t('prices', 'Remove all the price lists on storage'), array('deleteAllPrices', 'id' => $model->id), array('class' => 'btn_delete btn btn-primary'));
?>
</div>
<script type="text/javascript">
    $('.btn_delete').click(function () {
        if (confirm("<?php echo Yii::t('prices', 'Are you sure you want to remove all the price lists on this storage?'); ?>"))
            return true;
        return false;
    });
</script>
<?php $this->endWidget(); ?>