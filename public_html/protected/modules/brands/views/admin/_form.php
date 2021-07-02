<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'brands-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
));
?>
<p class="help-block"><?= Yii::t('prices', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('prices', 'Indispensable to filling.') ?></p>
<?php echo $form->errorSummary($model);
$tabs = array(
    array(
        'label' => Yii::t('languages', 'Basic'),
        'content' => $this->renderPartial('_content', array('form' => $form, 'model' => $model), true),
        'active' => true
    ),
);
foreach ($model->langsList() as $row) {
    $tabs[] = array(
        'label' => $row['name'],
        'content' => $this->renderPartial('application.views.adminLanguages._form_edit_languange', array('form' => $form, 'model' => $model->getTranslatedModel($row['link_name'], true), 'lang' => $row), true),
    );
}

$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs',
    'tabs' => $tabs,
));
?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('prices', 'Add') : Yii::t('prices', 'Save'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>