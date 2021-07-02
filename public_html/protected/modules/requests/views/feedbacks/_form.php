<?php if (Yii::app()->user->hasFlash('contact')): ?>

    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('contact'); ?>
    </div>

<?php else: ?>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'feedbacks-form',
        'enableAjaxValidation' => false,
//    'type' => 'horizontal',
    ));
    ?>

    <p class="help-block"><?= Yii::t('requests', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('requests', 'Indispensable to filling.') ?></p>


    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textAreaRow($model, 'text', array('class' => 'span5', 'rows' => 6)); ?>



    <?php if(CCaptcha::checkRequirements() && Yii::app()->user->isGuest):?>
    <?= CHtml::activeLabelEx($model, 'verifyCode') ?>
    <?php $this->widget('CCaptcha')?>
    <br/>
    <?= CHtml::activeTextField($model, 'verifyCode') ?>
    <?php endif ?>


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
