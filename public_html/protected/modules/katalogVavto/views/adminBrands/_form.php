<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'katalog-accessories-cathegorias-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<?= $form->errorSummary($model) ?>
<?php
$tabs = array(
    array(
        'label' => Yii::t('katalogVavto', 'Page'),
        'content' => $this->renderPartial('_content', array('form' => $form, 'model' => $model), true),
        'active' => true
    ),
    array(
        'label' => Yii::t('katalogVavto', 'Search Engine Optimization'),
        'content' => $this->renderPartial('_seo', array('form' => $form, 'model' => $model), true),
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
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('katalogVavto', 'Add') : Yii::t('katalogVavto', 'Save'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>