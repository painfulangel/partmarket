<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'user-profile-form',
    'action' => $model->isNewRecord ? Yii::app()->createUrl($this->route) : '/userControl/userProfile/update',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
        ));
?>
<p class="help-block"><?= Yii::t('userControl', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('userControl', 'Indispensable to filling.') ?></p>
<?php echo $form->errorSummary($model); ?>
<?php echo $form->emailFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 255)); ?>
<?php
if ($model->isNewRecord)
    echo $form->passwordFieldRow($model, 'reg_password', array('class' => 'span5', 'maxlength' => 255, 'autocomplete' => 'off'));
?>
<?php /*<div class="control-group ">
    Email будет использоваться для отправки уведомлений с сайта
    <div class="controls">

    </div>

</div>*/ ?>
<?php echo $form->textFieldRow($model, 'first_name', array('class' => 'span5', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, 'second_name', array('class' => 'span5', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, 'father_name', array('class' => 'span5', 'maxlength' => 255)); ?>
<div class="control-group ">
    <?php echo $form->labelEx($model, 'phone', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php
        /*$this->widget('CMaskedTextField', array(
            'model' => $model,
            'attribute' => 'phone',
            'mask' => '+7 (999) 999-9999',
            //'charMap' => array('.'=>'[\.]' , ','=>'[,]'),
            'htmlOptions' => array('class' => 'span5')
        ));*/
        ?>
        <?php $this->widget("ext.maskedInput.MaskedInput", array(
            "model" => $model,
            "attribute" => "phone",
            "mask" => '+9 (999) 999-9999',
            "clientOptions" => array("autoUnmask"=> true),
            'htmlOptions' => array('class' => 'span5')
        ));?>
    </div>
    <?php echo $form->error($model, 'phone'); ?>
</div>
<div class="control-group ">
    <?php echo $form->labelEx($model, 'extra_phone', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php
        $this->widget('CMaskedTextField', array(
            'model' => $model,
            'attribute' => 'extra_phone',
            'mask' => '+7 (999) 999-9999',
            //'charMap' => array('.'=>'[\.]' , ','=>'[,]'),
            'htmlOptions' => array('class' => 'span5')
        ));
        ?>
    </div>
    <?php echo $form->error($model, 'extra_phone'); ?>
</div>

<?php echo $form->textFieldRow($model, 'skype', array('class' => 'span5', 'maxlength' => 255)); ?>

<div class="control-group ">
    <div class="controls">
        <?php echo $form->labelEx($model, 'delivery'); ?>
    </div>
</div>
<?php echo $form->textFieldRow($model, 'delivery_zipcode', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'delivery_country', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'delivery_city', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'delivery_street', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'delivery_house', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textAreaRow($model, 'comment', array('class' => 'span5', 'row' => 5)); ?>

<div class="legal-entity-toggle ">
    <?php
    echo $form->radioButtonListInlineRow($model, 'legal_entity', array('0' => $model->getAttributeLabel('legal_entity1'), '1' => $model->getAttributeLabel('legal_entity2'), '2' => $model->getAttributeLabel('legal_entity3')), array('disabled' . ($model->isNewRecord ? 'off' : 'off') => ($model->isNewRecord ? 'off' : 'on'), 'class' => 'legal-entity-fire-toggle', 'id' => 'legal_entity_id'));
    ?>
</div>
<script>
    $(function () {
<?php if ($model->legal_entity == 1) { ?>
            //            $('#legal-block').show();

            //            $('#ip-block').hide();
            $('#ip-block').html('');
            $('#legal-block').html($('#temp_legal-block').html());
            $('#legal-block').show();
<?php } else if ($model->legal_entity == 2) {
    ?>
            //            $('#ip-block').show();

            //            $('#legal-block').hide();
            $('#legal-block').html('');
            $('#ip-block').html($('#temp_ip-block').html());
            $('#ip-block').show();
<?php } ?>
        $('.legal-entity-fire-toggle').change(function () {
            var current_value = $('.legal-entity-toggle input:checked').val();
            if (current_value == "1") {

                $('#ip-block').hide();
                $('#ip-block').html('');
                $('#legal-block').html($('#temp_legal-block').html());
                $('#legal-block').show();
            } else if (current_value == "2") {

                $('#legal-block').hide();
                $('#legal-block').html('');
                $('#ip-block').html($('#temp_ip-block').html());
                $('#ip-block').show();
            } else {
                $('#legal-block').hide();
                $('#ip-block').hide();
                $('#legal-block').html('');
                $('#ip-block').html('');
            }
        });
    });
</script>

<?php
$legal_temp = '';
$ip_temp = '';
$form_scenario = $model->scenario;
$model->scenario = 'main_form';
?>
<div id = "legal-block" style = "display: none;">

    <?php $legal_temp.= $form->textFieldRow($model, 'organization_type', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.=$form->textFieldRow($model, 'organization_name', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'organization_inn', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'organization_director', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'organization_ogrn', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'okpo', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php
    $legal_temp.= '<div class="control-group ">     <div class="controls">';
    $legal_temp.= $form->labelEx($model, 'bank_name');
    $legal_temp.='</div></div>';
    ?>


    <?php $legal_temp.= $form->textFieldRow($model, 'bank', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'bank_kpp', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'bank_bik', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'bank_rc', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'bank_ks', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php
    $legal_temp.= '<div class="control-group ">     <div class="controls">';
    $legal_temp.= $form->labelEx($model, 'legal');
    $legal_temp.='</div></div>';
    ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'legal_zipcode', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'legal_country', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'legal_city', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'legal_street', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $legal_temp.= $form->textFieldRow($model, 'legal_house', array('class' => 'span5', 'maxlength' => 255)); ?>
</div>

<div id="ip-block" style="display: none;">

    <?php $ip_temp.= $form->textFieldRow($model, 'organization_name', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'organization_inn', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'ogrnip', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'okpo', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php
    $ip_temp.= '<div class="control-group ">     <div class="controls">';
    $ip_temp.= $form->labelEx($model, 'bank_name');
    $ip_temp.='</div></div>';
    ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'bank', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'bank_kpp', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'bank_bik', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'bank_rc', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'bank_ks', array('class' => 'span5', 'maxlength' => 255)); ?>


    <?php
    $ip_temp.= '<div class="control-group ">     <div class="controls">';
    $ip_temp.= $form->labelEx($model, 'legal');
    $ip_temp.='</div></div>';
    ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'legal_zipcode', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'legal_country', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'legal_city', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'legal_street', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php $ip_temp.= $form->textFieldRow($model, 'legal_house', array('class' => 'span5', 'maxlength' => 255)); ?>


</div>
<?php
$model->scenario = $form_scenario;
?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('userControl', 'Save') : Yii::t('userControl', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
<div id="temp_ip-block" style="display: none;">
    <?php echo $ip_temp ?>
</div>
<div id="temp_legal-block" style="display: none;">
    <?php echo $legal_temp ?>
</div>