
<?php // echo $form->errorSummary($model);                ?>

<?php
foreach ($model->getTranslatedFields() as $field_name => $field_type) {

    echo CHtml::label($model->getAttributeLabel($field_name), get_class($model) . '_' . $lang['link_name'] . '[' . $field_name . ']');
    if ($field_type == 'string') {
        echo CHtml::textField(get_class($model) . '_' . $lang['link_name'] . '[' . $field_name . ']', isset($_POST[get_class($model) . '_' . $lang['link_name']][$field_name]) ? $_POST[get_class($model) . '_' . $lang['link_name']][$field_name] : $model->getTranslatedAttributes($field_name, $lang['link_name']), array('class' => 'span5'));
    }
    if ($field_type == 'text') {
        echo CHtml::textarea(get_class($model) . '_' . $lang['link_name'] . '[' . $field_name . ']', isset($_POST[get_class($model) . '_' . $lang['link_name']][$field_name]) ? $_POST[get_class($model) . '_' . $lang['link_name']][$field_name] : $model->getTranslatedAttributes($field_name, $lang['link_name']), array('rows' => 11, 'cols' => 50, 'class' => 'span5 redactor'));
    }
}
?>

<!--<div class="form-actions">
<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => $model->isNewRecord ? Yii::t('languages', 'Add') : Yii::t('languages', 'Save'),
));
?>
</div>-->