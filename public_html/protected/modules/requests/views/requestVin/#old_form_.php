<?php if (Yii::app()->user->hasFlash('contact')): ?>

    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('contact'); ?>
    </div>

<?php else: ?>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'request-vin-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    <div class="vin-form span4">
        <div class="vin_code" ><?php echo $form->textField($model, 'vin', array('style' => 'height: 14px;top: -2px;line-height: 14px;position: relative;')); ?></div>
        <div class="car_model" ><?php echo $form->textField($model, 'car_model', array('style' => 'height: 14px;top: 3px;line-height: 14px;position: relative;')); ?></div>
        <div class="car_year" ><?php echo $form->textField($model, 'car_year', array('style' => 'height: 14px;top: 7px;line-height: 14px;position: relative;')); ?></div>
        <div class="engine_model" ><?php echo $form->textField($model, 'engine_model', array('style' => 'height: 14px;top: 12px;line-height: 14px;position: relative;')); ?></div>
        <div class="body" ><?php echo $form->textField($model, 'body', array('style' => 'height: 14px;top: 16px;line-height: 14px;position: relative;')); ?></div>
    </div>

    <div class="span4">
        <?php echo $form->textFieldRow($model, 'name', array('class' => 'span3', 'maxlength' => 255)); ?>

        <?php echo $form->textFieldRow($model, 'email', array('class' => 'span3', 'maxlength' => 255)); ?>

        <div class="control-group ">
            <?php echo $form->labelEx($model, 'phone', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                $this->widget('CMaskedTextField', array(
                    'model' => $model,
                    'attribute' => 'phone',
                    'mask' => '+7 (999) 999-9999',
                    //'charMap' => array('.'=>'[\.]' , ','=>'[,]'),
                    'htmlOptions' => array('class' => 'span3', 'maxlength' => 11)
                ));
                ?>
            </div>
            <?php echo $form->error($model, 'phone'); ?>
        </div>


        <?php echo $form->textAreaRow($model, 'text', array('rows' => 4, 'cols' => 50, 'class' => 'span3')); ?>

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
                'label' => $model->isNewRecord ? 'Отправить' : 'Сохранить',
            ));
            ?>
        </div>

    </div>


    <?php $this->endWidget(); ?>
<?php endif; ?>