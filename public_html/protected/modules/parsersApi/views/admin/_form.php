<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'parsers-form',
    'enableAjaxValidation' => false,
        ));
?>
<p class="help-block"><?= Yii::t('parsersApi', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('parsersApi', 'Indispensable to filling.') ?></p>
<?php
	echo $form->errorSummary($model);
	echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 127));
	echo $form->textFieldRow($model, 'supplier_code', array('class' => 'span5', 'maxlength' => 127, 'disabled' => 'disabled'));
	echo $form->dropDownListRow($model, 'price_group_1', $priceGroupsList, array('class' => 'span5'));
	echo $form->dropDownListRow($model, 'price_group_2', $priceGroupsList, array('class' => 'span5'));
	echo $form->dropDownListRow($model, 'price_group_3', $priceGroupsList, array('class' => 'span5'));
	echo $form->dropDownListRow($model, 'price_group_4', $priceGroupsList, array('class' => 'span5'));
	echo $form->textFieldRow($model, 'delivery', array('class' => 'span5'));
	echo $form->textFieldRow($model, 'supplier_inn', array('class' => 'span5', 'maxlength' => 20));
	echo $form->textFieldRow($model, 'supplier', array('class' => 'span5', 'maxlength' => 45));
	echo $form->dropDownListRow($model, 'currency', $currencies, array('class' => 'span5'));
	echo $form->checkBoxRow($model, 'admin_active_state');
	echo $form->checkBoxRow($model, 'top');
	echo $form->checkBoxRow($model, 'show_prefix');
	echo $form->checkBoxRow($model, 'prepay');
	echo $form->dropDownListRow($model, 'language', array('' => Yii::t('languages', 'All'), '0' => Yii::t('languages', 'Basic')) + CHtml::listData(Languages::model()->findAll(), 'link_name', 'name'), array('class' => ''));
?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('parsersApi', 'Add') : Yii::t('parsersApi', 'Save'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>