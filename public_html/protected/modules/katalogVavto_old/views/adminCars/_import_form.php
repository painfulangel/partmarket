<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'crosses-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>

<?= $form->errorSummary($model) ?>



<?php echo $form->fileFieldRow($model, 'fileImport', array()); ?>

<?php echo $form->{Yii::app()->controller->module->radionButtonFunction}($model, 'fileCharset', Yii::app()->controller->module->extraCharacters, array('class' => '')); ?>



<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('katalogVavto', 'Import'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
