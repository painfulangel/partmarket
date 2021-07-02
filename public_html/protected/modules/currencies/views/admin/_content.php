
<p class="help-block"><?= Yii::t('currencies', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('currencies', 'Indispensable to filling.') ?></p>

<?php // echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 45)); ?>

<?php echo $form->textFieldRow($model, 'exchange', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'percent', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'marker', array('class' => 'span5', 'maxlength' => 5)); ?>

<?php echo $form->checkBoxRow($model, 'visibility_state', array()); ?>

<?php echo $form->checkBoxRow($model, 'basic', array()); ?>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('currencies', 'Add') : Yii::t('currencies', 'Save'),
    ));
    ?>
</div>