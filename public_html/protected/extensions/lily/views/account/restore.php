<?php
/* @var $this Controller */
$this->pageTitle = Yii::t('lily', '{appName} - Restore e-mail account', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
$this->breadcrumbs = array(
    Yii::t('lily', 'Restore e-mail account')
);
?><h1><?php echo Yii::t('lily', 'Restore controll to account'); ?></h1>

<p><?php echo Yii::t('lily', 'Please type your e-mail address:'); ?></p>

<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'restore-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>

    <p class="note"><?php echo Yii::t('lily', 'Fields with {requiredSign} are required.', array('{requiredSign}' => '<span class="required">*</span>')); ?></p>

    <?php echo $form->textFieldRow($model, 'email', array('class' => 'span3')); ?>



    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('lily', 'Restore'),
        ));
        ?>
        <?php echo CHtml::link(Yii::t('lily', 'Cancel'), $this->createUrl(Yii::app()->user->isGuest ? 'user/login' : 'account/list'), array('class' => 'btn btn-primary')); ?>

    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
