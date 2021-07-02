<?php
Yii::app()->clientScript->registerScriptFile('/libs/redactorjs/ru.js');
Yii::app()->clientScript->registerScriptFile('/libs/django-urlify/urlify.js');
Yii::app()->clientScript->registerScript('translit', "
$('#translit-btn').click(function() {
$('#KatalogVavtoItems_slug').val(URLify($('#KatalogVavtoItems_title').val()));
});
");
?>
<?php if (!empty($model->image)) { ?>
    <div class="control-group">
        <div class="controls">
            <?php echo CHtml::image('/' . $model->getAttachment()) ?>
        </div>
    </div>
<?php } ?>
<?php echo $form->fileFieldRow($model, 'image', array()); ?>
<?php
	$data_list = $model->getCarPartTypes();
?>
<?php echo $form->dropDownListRow($model, 'cathegory_id', CHtml::listData(KatalogVavtoCars::model()->findAll(), 'id', 'title'), array('class' => 'span5', 'empty' => '')) ?>
<?php echo $form->dropDownListRow($model, 'detail_type', $data_list, array('class' => 'span5', 'empty' => '')) ?>
<?php echo $form->textFieldRow($model, 'title', array('class' => 'span5', 'maxlength' => 255)) ?>
<?php echo $form->textFieldRow($model, 'article', array('class' => 'span5', 'maxlength' => 255)) ?>
<div class="control-group">
    <?php echo $form->labelEx($model, 'slug', array('class' => 'control-label')) ?>
    <div class="controls">
        <div class="input-append">
            <?php echo $form->textField($model, 'slug', array('class' => 'span5', 'maxlength' => 127)) ?><button class="btn" type="button" id="translit-btn"><?php echo Yii::t('katalogVavto', 'Translit') ?></button>
        </div>
    </div>
</div>

<?php // $form->textFieldRow($model, 'price', array('class' => 'span5', 'maxlength' => 255))  ?>

<?php echo $form->textFieldRow($model, 'sub_title', array('class' => 'span5', 'maxlength' => 255)) ?>

<?php
	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'text'));
	//!!! Виджет, выводящий редактор нужного типа
?>
<div class="control-group">
    <?php echo $form->textAreaRow($model, 'short_text', array('rows' => 11, 'cols' => 50, 'class' => 'span5')); ?>
</div>

<?php // echo  $form->textFieldRow($model, 'supplier', array('class' => 'span5', 'maxlength' => 255))  ?>

<?php // echo $form->textFieldRow($model, 'supplier_inn', array('class' => 'span5', 'maxlength' => 255))  ?>

<?php echo $form->checkBoxRow($model, 'active_state', array('class' => '', 'maxlength' => 255)); ?>
<?php echo $form->checkBoxRow($model, 'in_stock', array('class' => '', 'maxlength' => 255)); ?>
<style>
	.control-group .controls img {
		max-height: 200px;
	}	
</style>