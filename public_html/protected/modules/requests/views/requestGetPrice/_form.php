<?php if (Yii::app()->user->hasFlash('contact')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('contact'); ?>
    </div>
<?php else: ?>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'request-get-price-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'type' => 'horizontal',
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <p><?= Yii::t('requests', 'Please complete the order form spare parts. Manager will process Your order and contact you.') ?></p>
    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model, 'email_phone', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'detail', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'vin', array('class' => 'span5', 'maxlength' => 255)); ?>
    
	<?php echo $form->textFieldRow($model, 'brand', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'car_brand', array('class' => 'span5', 'maxlength' => 255)); ?>
    
    <?php echo $form->textFieldRow($model, 'car_model', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'car_year', array('class' => 'span5')); ?>

    <?php echo $form->textAreaRow($model, 'comment', array('rows' => 6, 'cols' => 5, 'class' => 'span5')); ?>

    <?php /*if (CCaptcha::checkRequirements() && Yii::app()->user->isGuest): ?>
        <?= CHtml::activeLabelEx($model, 'verifyCode') ?>
        <?php $this->widget('CCaptcha') ?>
        <br/>
        <?= CHtml::activeTextField($model, 'verifyCode') ?>
    <?php endif; */ ?>
    <?php if (Yii::app()->user->isGuest) { ?>
    	<div class="g-recaptcha" data-sitekey="<?php echo Yii::app()->config->get('Site.recaptchakey'); ?>"></div>
    <?php } ?>

    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('requests', 'Find') : Yii::t('requests', 'Save'),
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>
<?php endif; ?>