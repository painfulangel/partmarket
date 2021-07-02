<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'pricesdatameta-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>
<p class="help-block"><?= Yii::t('prices', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('prices', 'Indispensable to filling.') ?></p>

<?php echo $form->errorSummary($model); ?>
<?php echo $form->textFieldRow($model, 'brand', array('class' => 'span5', 'maxlength' => 45)); ?>
<?php echo $form->textFieldRow($model, 'article', array('class' => 'span5', 'maxlength' => 45)); ?>
<?php
	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'content'));
	//!!! Виджет, выводящий редактор нужного типа
?>
<?php if (!empty($model->image)) { ?>
    <div class="control-group">
        <div class="controls">
            <?php echo CHtml::image('/'.$model->getImage('big'), $model->article, array('style' => 'max-height: 150px;')) ?>
        </div>
    </div>
<?php } ?>
<?php echo $form->fileFieldRow($model, '_image', array()); ?>
<?php echo $form->textFieldRow($model, 'meta_title', array('class' => 'span5', 'maxlength' => 255)) ?>
<?php echo $form->textAreaRow($model, 'meta_description', array('rows' => 5, 'class' => 'span5', 'maxlength' => 255)) ?>
<?php echo $form->textAreaRow($model, 'meta_keywords', array('rows' => 5, 'class' => 'span5', 'maxlength' => 255)) ?>

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