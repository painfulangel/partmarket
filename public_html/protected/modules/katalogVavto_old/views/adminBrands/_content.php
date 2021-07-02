<?php
Yii::app()->clientScript->registerScriptFile('/libs/redactorjs/ru.js');
Yii::app()->clientScript->registerScriptFile('/libs/django-urlify/urlify.js');
Yii::app()->clientScript->registerScript('translit', "
$('#translit-btn').click(function() {
$('#KatalogVavtoBrands_slug').val(URLify($('#KatalogVavtoBrands_title').val()));
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

<?= $form->textFieldRow($model, 'title', array('class' => 'span5', 'maxlength' => 255)) ?>
<?= $form->textFieldRow($model, 'short_title', array('class' => 'span5', 'maxlength' => 255)) ?>


<?=
$form->dropDownListRow($model, 'menu_image', array(
    '' => Yii::t('katalogVavto', 'Select the icon to display the menu on the left'),
    'kia' => 'Kia',
    'hnd' => 'Hyundai',
    'cit' => 'Citroen',
    'peug' => 'Peugeot',
    'ren' => 'Renault',
    'toy' => 'Toyota',
    'lex' => 'Lexus',
    'nis' => 'Nissan',
    'inf' => 'Infinity',
    'suz' => 'Suzuki',
    'maz' => 'Mazda',
        ), array('class' => 'span5', 'maxlength' => 255))
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
