<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'parsers-form',
    'enableAjaxValidation' => false,
        ));
?>

<p class="help-block"><?= Yii::t('parsers', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('parsers', 'Indispensable to filling.') ?></p>


<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 127)); ?>

<?php echo $form->dropDownListRow($model, 'price_group_1', $priceGroupsList, array('class' => 'span5')); ?>

<?php echo $form->dropDownListRow($model, 'price_group_2', $priceGroupsList, array('class' => 'span5')); ?>

<?php echo $form->dropDownListRow($model, 'price_group_3', $priceGroupsList, array('class' => 'span5')); ?>

<?php echo $form->dropDownListRow($model, 'price_group_4', $priceGroupsList, array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'delivery', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'supplier_inn', array('class' => 'span5', 'maxlength' => 20)); ?>

<?php echo $form->textFieldRow($model, 'supplier', array('class' => 'span5', 'maxlength' => 45)); ?>

<?php echo $form->dropDownListRow($model, 'currency', $currencies, array('class' => 'span5')); ?>

<?php echo $form->dropDownListRow($model, 'language', array('' => Yii::t('languages', 'All'), '0' => Yii::t('languages', 'Basic')) + CHtml::listData(Languages::model()->findAll(), 'link_name', 'name'), array('class' => '')); ?>


<?php
if (Yii::app()->user->checkAccess('admin'))
    echo $form->textAreaRow($model, 'codeblock', array('rows' => 6, 'cols' => 50, 'class' => 'span5'));
?>

<?php echo $form->CheckBoxRow($model, 'active_state', array('class' => '')); ?>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('parsers', 'Add') : Yii::t('parsers', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
