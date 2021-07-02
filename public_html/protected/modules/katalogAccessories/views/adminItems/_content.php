<?php
Yii::app()->clientScript->registerScriptFile('/libs/redactorjs/ru.js');
Yii::app()->clientScript->registerScriptFile('/libs/django-urlify/urlify.js');
Yii::app()->clientScript->registerScript('translit', "
$('#translit-btn').click(function() {
$('#KatalogAccessoriesItems_slug').val(URLify($('#KatalogAccessoriesItems_title').val()));
});
");
?>


<?php echo $form->dropDownListRow($model, 'cathegory_id', KatalogAccessoriesCathegorias::model()->selectList(), array('class' => 'span5', 'empty' => '')) ?>

<?= $form->textFieldRow($model, 'title', array('class' => 'span5', 'maxlength' => 255)) ?>

<?= $form->textFieldRow($model, 'short_title', array('class' => 'span5', 'maxlength' => 255)) ?>

<div class="control-group">
    <?= $form->labelEx($model, 'slug', array('class' => 'control-label')) ?>
    <div class="controls">
        <div class="input-append">
            <?= $form->textField($model, 'slug', array('class' => 'span5', 'maxlength' => 127)) ?><button class="btn" type="button" id="translit-btn"><?= Yii::t('katalogAccessories', 'transliteration') ?></button>
        </div>
    </div>
</div>

<?= $form->textFieldRow($model, 'price', array('class' => 'span5', 'maxlength' => 255)) ?>

<?= $form->textFieldRow($model, 'supplier', array('class' => 'span5', 'maxlength' => 255)) ?>

<?= $form->textFieldRow($model, 'supplier_inn', array('class' => 'span5', 'maxlength' => 255)) ?>

<?php
	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'text'));
	//!!! Виджет, выводящий редактор нужного типа
?>
<div class="control-group">
    <?php echo $form->textAreaRow($model, 'short_text', array('rows' => 11, 'cols' => 50, 'class' => 'redactor')); ?>
</div>
<?= $form->checkBoxRow($model, 'active_state', array('class' => 'span1', 'maxlength' => 255)) ?>