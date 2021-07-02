<?php if (Yii::app()->user->hasFlash('contact')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('contact'); ?>
    </div>
<?php else: ?>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'request-wu-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'type' => 'horizontal',
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->textFieldRow($model, 'vin', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php echo $form->textFieldRow($model, 'car_model', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php echo $form->textFieldRow($model, 'car_brand', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?>
    <div class="control-group ">
        <?php echo $form->labelEx($model, 'phone', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('CMaskedTextField', array(
                'model' => $model,
                'attribute' => 'phone',
                'mask' => '+7 (999) 999-9999',
                //'charMap' => array('.'=>'[\.]' , ','=>'[,]'),
                'htmlOptions' => array('class' => 'span5', 'maxlength' => 11)
            ));
            ?>
        </div>
        <?php echo $form->error($model, 'phone'); ?>
    </div>
    <?php echo $form->textAreaRow($model, 'text', array('rows' => 6, 'cols' => 50, 'class' => 'span5')); ?>
    <?php echo $form->textFieldRow($model, 'item', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php /*if(CCaptcha::checkRequirements() && Yii::app()->user->isGuest):?>
    <?= CHtml::activeLabelEx($model, 'verifyCode') ?>
    <?php $this->widget('CCaptcha')?>
    <br/>
    <?= CHtml::activeTextField($model, 'verifyCode') ?>
    <?php endif;*/ ?>
    <?php if (Yii::app()->user->isGuest) { ?>
    	<div class="g-recaptcha" data-sitekey="<?php echo $recaptchakey; ?>"></div>
    <?php } ?>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('requests', 'Send') : Yii::t('requests', 'Save'),
        ));
        ?>
    </div>
    <?php $this->endWidget(); ?>
<?php endif; ?>