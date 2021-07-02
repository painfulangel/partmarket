
<p class="help-block"><?= Yii::t('menu', 'Fields contain') ?> <span class="required">*</span> <?= Yii::t('menu', 'must be filled.') ?></p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->dropDownListRow($model, 'menu_type', Yii::app()->controller->module->typeList, array('class' => 'span5', 'maxlength' => 10)); ?>

<?php echo $form->textFieldRow($model, 'menu_value', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->dropDownListRow($model, 'echo_position', Yii::app()->controller->module->positionList, array('class' => 'span5', 'maxlength' => 45)); ?>

<?php echo $form->textFieldRow($model, 'title', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->checkBoxRow($model, 'visible', array('class' => '')); ?>

