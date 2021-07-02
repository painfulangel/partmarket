<?php
Yii::app()->clientScript->registerScriptFile('/libs/redactorjs/ru.js');
Yii::app()->clientScript->registerScriptFile('/libs/django-urlify/urlify.js');
Yii::app()->clientScript->registerScript('translit', "
$('#translit-btn').click(function() {
$('#KatalogVavtoCars_slug').val(URLify($('#KatalogVavtoCars_title').val()));
});
");
?>
<?php if (!empty($model->image)) { ?>
    <div class="control-group">
        <div class="controls">
            <?= CHtml::image('/' . $model->getImage('big')) ?>
        </div>
    </div>
<?php } ?>

<?php echo $form->fileFieldRow($model, '_image', array()); ?>

<?php if (!empty($model->index_image)) { ?>
    <div class="control-group">
        <div class="controls">
            <?= CHtml::image('/' . $model->getImageIndex('big')) ?>
        </div>
    </div>
<?php } ?>
<?php echo $form->fileFieldRow($model, '_index_image', array()); ?>

<?php echo $form->dropDownListRow($model, 'parent_id', $model->selectList(), array('class' => 'span5', 'empty' => '')) ?>

<?= $form->textFieldRow($model, 'title', array('class' => 'span5', 'maxlength' => 255)) ?>
<?= $form->textFieldRow($model, 'short_title', array('class' => 'span5', 'maxlength' => 255)) ?>
<?= $form->textFieldRow($model, 'years', array('class' => 'span5', 'maxlength' => 255)) ?>
<script type="text/javascript">

</script>

<div id="level2" style="display:none;"></div>
<?php
$data_list = $model->getCarTypes();
?>
<?=
$form->dropDownListRow($model, 'sub_image_class', $data_list, array('class' => 'span5', 'maxlength' => 255))
?>
<div class="control-group">
    <?= $form->labelEx($model, 'slug', array('class' => 'control-label')) ?>
    <div class="controls">
        <div class="input-append">
            <?= $form->textField($model, 'slug', array('class' => 'span5', 'maxlength' => 127)) ?><button class="btn" type="button" id="translit-btn"><?= Yii::t('katalogVavto', 'Translit') ?></button>
        </div>
    </div>
</div>
<?php
	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'text'));
	//!!! Виджет, выводящий редактор нужного типа
?>
<div class="control-group">
    <?php echo $form->textAreaRow($model, 'short_text', array('rows' => 11, 'cols' => 50, 'class' => 'span5')); ?>
</div>
<?= $form->checkBoxRow($model, 'active_state', array('class' => '', 'maxlength' => 255)) ?>