

<?php

ob_start();
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'stores-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
        ));

$html = ob_get_clean();
?>


<?php echo $form->textFieldRow($model, 'delivery', array('class' => 'span5')); ?>

<?php echo $form->dropDownListRow($model, 'price_group_1', $priceGroupsList, array('class' => 'span5')); ?>

<?php echo $form->dropDownListRow($model, 'price_group_2', $priceGroupsList, array('class' => 'span5')); ?>

<?php echo $form->dropDownListRow($model, 'price_group_3', $priceGroupsList, array('class' => 'span5')); ?>

<?php echo $form->dropDownListRow($model, 'price_group_4', $priceGroupsList, array('class' => 'span5')); ?>

<?php echo $form->dropDownListRow($model, 'currency', $currencies, array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'supplier_inn', array('class' => 'span5', 'maxlength' => 20)); ?>

<?php echo $form->textFieldRow($model, 'supplier', array('class' => 'span5', 'maxlength' => 45)); ?>

<?php echo $form->checkBoxRow($model, 'search_state', array('class' => '')); ?>

<?php echo $form->dropDownListRow($model, 'language', array('' => Yii::t('languages', 'All'), '0' => Yii::t('languages', 'Basic')) + CHtml::listData(Languages::model()->findAll(), 'link_name', 'name'), array('class' => '')); ?>

<?php

ob_start();
$this->endWidget();
$html = ob_get_clean();
?>
