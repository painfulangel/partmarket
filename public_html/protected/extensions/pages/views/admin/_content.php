<?php
Yii::app()->clientScript->registerScriptFile('/libs/redactorjs/ru.js');
Yii::app()->clientScript->registerScriptFile('/libs/django-urlify/urlify.js');
Yii::app()->clientScript->registerScript('translit', "
$('#translit-btn').click(function() {
$('#Page_slug').val(URLify($('#Page_page_title').val()));
});
");
?>
<?php echo $form->dropDownListRow($model, 'parent_id', $model->selectList(), array('class' => 'span5', 'empty' => '')) ?>
<?= $form->textFieldRow($model, 'page_title', array('class' => 'span5', 'maxlength' => 255)) ?>
<?= $form->textFieldRow($model, 'meta_title', array('class' => 'span5', 'maxlength' => 255)) ?>
<div class="control-group">
    <?= $form->labelEx($model, 'slug', array('class' => 'control-label', 'label' => Yii::t('pages', 'Alias') )) ?>
    <div class="controls">
        <div class="input-append">
            <?= $form->textField($model, 'slug', array('class' => 'span5', 'maxlength' => 127)) ?><button class="btn" type="button" id="translit-btn"><?= Yii::t('pages', 'Translit') ?></button>
        </div>
    </div>
</div>
<?= $form->checkBoxRow($model, 'is_published') ?>
<?php
	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'content'));
	//!!! Виджет, выводящий редактор нужного типа
?>