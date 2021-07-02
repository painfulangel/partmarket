<?php
/* @var $this Controller */
$this->pageTitle = Yii::t('lily', '{appName} - Edit account', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
$this->breadcrumbs = array(
    Yii::t('lily', 'Accounts') => $this->createUrl('account/list'),
    Yii::t('lily', 'Edit')
);
?><h1><?php echo Yii::t('lily', 'Edit account'); ?></h1>

<p><?php echo Yii::t('lily', 'Please type the password, you want to have and repeat in next field:'); ?></p>

<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'password-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>

    <p class="note"><?php echo Yii::t('lily', 'Fields with {requiredSign} are required.', array('{requiredSign}' => '<span class="required">*</span>')); ?></p>

    <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span3')); ?>
    <?php echo $form->passwordFieldRow($model, 'password_repeat', array('class' => 'span3')); ?>

    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('lily', 'Save'),
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
