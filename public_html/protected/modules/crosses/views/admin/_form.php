<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'crosses-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>
<p class="help-block"><?= Yii::t('crosses', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('crosses', 'Indispensable to filling.') ?></p>
<input type="hidden" name="Crosses[base_id]" value="<?php echo $base_id; ?>">
<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 45)); ?>

<?php if ($model->isNewRecord) { ?>

    <?php echo $form->fileFieldRow($model, 'crossFile', array()); ?>

    <?php echo $form->{Yii::app()->controller->module->radionButtonFunction}($model, 'crossCharset', Yii::app()->controller->module->extraCharacters, array('class' => '')); ?>
    <?= Yii::t('crosses', 'Cross table should be in one of the formats: txt or csv (separated by tabs), and have a similar structure') ?> <a href="/upload_files/examples/demo_cross.zip"><?= Yii::t('crosses', 'example') ?></a>
    <br><br>
<?php } ?>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('crosses', 'Add') : Yii::t('crosses', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
