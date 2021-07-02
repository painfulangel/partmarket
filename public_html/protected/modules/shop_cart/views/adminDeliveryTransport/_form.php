<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'delivery-data-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
));
echo $form->errorSummary($model);

echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255));
echo $form->textFieldRow($model, 'price', array('class' => 'span5', 'maxlength' => 255));
echo $form->checkBoxRow($model, 'active', array('class' => '', 'maxlength' => 255));
?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('delivery', 'Add') : Yii::t('delivery', 'Save'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>