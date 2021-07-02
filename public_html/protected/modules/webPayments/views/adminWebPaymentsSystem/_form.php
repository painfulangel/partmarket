<?php

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'web-payments-system-form',
    'enableAjaxValidation' => false,
        ));
?>

<?php

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
))
?>

<?php $this->endWidget(); ?>
