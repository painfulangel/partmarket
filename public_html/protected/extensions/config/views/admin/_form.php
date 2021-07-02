<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'config-form',
    'enableAjaxValidation' => false,
        ));
?>
<?php echo $form->errorSummary($model); ?>
<?php
if ($model->type == 'string')
    echo $form->textFieldRow($model, 'value', array('class' => 'span5', 'maxlength' => 128));
if ($model->type == 'text')
    echo $form->textAreaRow($model, 'value', array('rows' => 6, 'cols' => 50, 'class' => 'span5'));
if ($model->type == 'checkbox')
    echo $form->checkBoxRow($model, 'value', array());
if ($model->type == 'list[PriceGroups]') {
    echo $form->dropDownListRow($model, 'value', CHtml::listData(PricesRulesGroups::model()->findAll(), 'id', 'name'), array('class' => 'span1'));
}

echo '<label for="Config_description">' . $model->description . '</label>';
?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('config', 'Save'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>
