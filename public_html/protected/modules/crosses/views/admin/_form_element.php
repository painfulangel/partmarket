<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'crosses-data-form',
    'enableAjaxValidation' => false,
)); ?>
<p class="help-block"><?php echo Yii::t('crosses', "Fields are required"); ?></p>
<?php echo $form->errorSummary($model); ?>
<?php echo $form->dropDownListRow($model, 'base_id', CHtml::listData(CrossesBase::model()->findAll(), 'id', 'name'), array('class' => 'span5', 'maxlength' => 11)); ?>
<?php echo $form->textFieldRow($model, 'origion_article', array('class' => 'span5', 'maxlength' => 127)); ?>
<?php echo $form->textFieldRow($model, 'origion_brand', array('class' => 'span5', 'maxlength' => 127)); ?>
<?php //echo $form->textFieldRow($model, 'partsid', array('class' => 'span5', 'maxlength' => 127 /*    ,'hint'=>Yii::t('crosses','')*/)); ?>
<?php echo $form->textFieldRow($model, 'cross_article', array('class' => 'span5', 'maxlength' => 127)); ?>
<?php echo $form->textFieldRow($model, 'cross_brand', array('class' => 'span5', 'maxlength' => 127)); ?>
<!--	--><?php //echo $form->textFieldRow($model,'new_state',array('class'=>'span5')); ?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('prices', 'Add') : Yii::t('prices', 'Save'),
    )); ?>
</div>
<?php $this->endWidget(); ?>