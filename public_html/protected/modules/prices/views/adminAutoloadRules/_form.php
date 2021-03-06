<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'prices-ftp-autoload-rules-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
        ));
if (empty($model->method_type))
    $model->method_type = 'email';
?>

<p class="help-block"><?= Yii::t('prices', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('prices', 'Indispensable to filling.') ?></p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'rule_name', array('class' => 'span5 span5 tool-tip', 'maxlength' => 127)); ?>
<?php echo $form->dropDownListRow(
        $model,
        'method_type',
        array(
            //'email' => Yii::t('prices', 'by Email'),
            '' => Yii::t('prices', 'Select method type'),
            'url' => Yii::t('prices', 'by Url'),
            'ftp' => Yii::t('prices', 'by FTP'),
            'local' => Yii::t('prices', 'From a local directory')
        ),
        array('class' => 'span5 span5 tool-tip', 'maxlength' => 127)); ?>
<?php //echo $form->textFieldRow($model, 'mail_subject', array('class' => 'span5 change_all change_email')); ?>
<?php //echo $form->textFieldRow($model, 'mail_body', array('class' => 'span5 change_all change_email')); ?>
<?php //echo $form->textFieldRow($model, 'mail_from', array('class' => 'span5 change_all change_email')); ?>
<?php // echo $form->textFieldRow($model, 'mail_file', array('class' => 'span5 change_all change_email')); ?>
<?php //echo $form->dropDownListRow($model, 'mail_id', CHtml::listData(PricesFtpAutoloadMailboxes::model()->findAll(), 'id', 'mailbox'), array('class' => 'span5 change_all change_email')); ?>

<?php echo $form->textFieldRow($model, 'remote_url', array('class' => 'span5 span5 tool-tip change_all change_url', 'title' => Yii::t('prices', 'Insert a direct link to the file'), 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'ftp_server', array('class' => 'span5 span5 tool-tip change_all change_ftp', 'title' => Yii::t('prices', 'Leave empty to load the price with the hosting site'), 'maxlength' => 127)); ?>

<?php echo $form->textFieldRow($model, 'ftp_login', array('class' => ' span5 tool-tip change_all change_ftp', 'title' => '', 'maxlength' => 127)); ?>

<?php echo $form->textFieldRow($model, 'ftp_password', array('class' => ' span5 tool-tip change_all change_ftp', 'placeholder' => (!$model->isNewRecord ? Yii::t('prices', 'To change the password, enter a new password.') : ''), 'title' => '', 'maxlength' => 127)); ?>


<?php // echo $form->DropDownListRow($model, 'ftp_auth_type', array('0'=>'?????????????????? (??????????, ????????????)','1'=>'??????????','2'=>'??',), array('class' => ' span5 tool-tip', 'title' => '')); ?>

<?php echo $form->textFieldRow($model, 'ftp_destination_folder', array('class' => ' span5 tool-tip change_all change_ftp change_all change_local', 'title' => '', 'maxlength' => 255)); ?>

<p class="hint"><?= Yii::t('prices', 'For the local server, specify only the path to the directory from the root of the site') ?></p>

<?php echo $form->textFieldRow($model, 'search_file_criteria', array('class' => ' span5 tool-tip ', 'title' => '', 'maxlength' => 255)); ?>


<?php echo $form->{Yii::app()->controller->module->radionButtonFunction}($model, 'charset', Yii::app()->controller->module->extraCharacters, array('class' => '')); ?>

<?php echo $form->dropDownListRow($model, 'store_id', CHtml::listData(Stores::model()->findAll(), 'id', 'name'), array('class' => ' span5 tool-tip', 'title' => '')); ?>

<?php
//echo $form->dropDownListRow($model, 'load_period_days', array(
//    '*' => '???????????? ????????',
//    '*/02' => '???????????? 2 ??????',
//    '*/03' => '???????????? 3 ??????',
//    '*/04' => '???????????? 4 ??????',
//    '*/05' => '???????????? 5 ??????',
//        ), array('class' => ' span5 tool-tip', 'title' => '* - ?????? ?????????????? ???????????? ???????? ; */n - ?????? ?????????????? ???????????? n ????????; n - ?????? ?????????????? ?? n ????????', 'maxlength' => 32));
?>

<?php
//echo $form->dropDownListRow($model, 'load_period_hours', array(
//    '*' => '???????????? ??????',
//    '*/02' => '???????????? 2 ????????',
//    '*/03' => '???????????? 3 ????????',
//    '*/04' => '???????????? 4 ????????',
//    '*/05' => '???????????? 5 ??????????',
//    '*/06' => '???????????? 6 ??????????',
//    '*/12' => '???????????? 12 ??????????',
//    '*/18' => '???????????? 18 ??????????',
//        ), array('class' => ' span5 tool-tip', 'title' => '* - ?????? ?????????????? ???????????? ?????? ; */n - ?????? ?????????????? ???????????? n ??????????; n - ?????? ?????????????? ?? n ??????????', 'maxlength' => 32));
//
?>

<?php
//echo $form->dropDownListRow($model, 'load_period_minutes', array(
//    '*/5' => '???????????? 5 ??????????',
//    '*/10' => '???????????? 10 ??????????',
//    '*/15' => '???????????? 15 ??????????',
//    '*/20' => '???????????? 20 ??????????',
//    '*/30' => '???????????? 30 ??????????',
//    '*/45' => '???????????? 45 ??????????',
//        ), array('class' => ' span5 tool-tip', 'title' => '* - ?????? ?????????????? ???????????? ???????????? ; */n - ?????? ?????????????? ???????????? n ??????????; n - ?????? ?????????????? ?? n ??????????', 'maxlength' => 32));
?>
<?php
if (empty($model->cron_general)) {
    $model->cron_general = 7;
}
echo $form->dropDownListRow($model, 'cron_general', array(
//    '1' => '???????????? 5 ??????????',
//    '2' => '???????????? 30 ??????????',
    '3' => Yii::t('prices', 'Everyone hour'),
    '4' => Yii::t('prices', 'Each 3 hours'),
    '5' => Yii::t('prices', 'Each 6 hours'),
    '6' => Yii::t('prices', 'Each 12 hours'),
    '7' => Yii::t('prices', 'Every day'),
    '8' => Yii::t('prices', 'each 2 days'),
    '9' => Yii::t('prices', 'each 3 days'),
//    '*/45' => '???????????? 45 ??????????',
        ), array('class' => ' span5 tool-tip', 'maxlength' => 32));
?>
<?php // echo $form->textFieldRow($model, 'load_period_days', array('class' => ' span5 tool-tip', 'title' => '* - ?????? ?????????????? ???????????? ???????? ; */n - ?????? ?????????????? ???????????? n ????????; n - ?????? ?????????????? ?? n ????????', 'maxlength' => 32));  ?>

<?php // echo $form->textFieldRow($model, 'load_period_hours', array('class' => ' span5 tool-tip', 'title' => '* - ?????? ?????????????? ???????????? ?????? ; */n - ?????? ?????????????? ???????????? n ??????????; n - ?????? ?????????????? ?? n ??????????', 'maxlength' => 32));  ?>

<?php // echo $form->textFieldRow($model, 'load_period_minutes', array('class' => ' span5 tool-tip', 'title' => '* - ?????? ?????????????? ???????????? ???????????? ; */n - ?????? ?????????????? ???????????? n ??????????; n - ?????? ?????????????? ?? n ??????????', 'maxlength' => 32));  ?>

<?php echo $form->textFieldRow($model, 'start_line', array('class' => ' span5 tool-tip', 'title' => Yii::t('prices', 'Starting line number at which to begin processing a price'))); ?>

<?php echo $form->textFieldRow($model, 'finish_line', array('class' => ' span5 tool-tip', 'title' => Yii::t('prices', 'The last line number on which the price will be processed'))); ?>


<?php
$columns = array('' => '');
$z = 1;
for ($i = 'A'; $i <= 'ZZ'; $i++) {
    if ($z > 702)
        break;
    $columns[$z] = Yii::t('prices', 'Column') . ' ' . $z . ' (' . $i . ')';
    $z++;
}
//print_r($columns);
?>



<?php echo $form->dropDownListRow($model, 'article', $columns, array('class' => ' span5 tool-tip', 'title' => Yii::t('prices', 'for xml of prices - the name of a tag, for all others - number of a column'), 'maxlength' => 127)); ?>
<div class="control-group ">    
    <?php
    $field = 'article';
    echo $form->labelEx($model, 'replace_' . $field, array('class' => 'control-label'));
    ?>
    <div class="controls">
        <?php echo $form->textField($model, 'replace_' . $field, array('class' => ' span5 tool-tip', 'placeholder' => Yii::t('prices', 'Fill to populate with default data'), 'maxlength' => 127)); ?>
        <?php echo CHtml::checkBox('replace_' . $field, (!empty($model->replace_article) ? '1' : '0'), array('class' => 'attr_active', 'attr' => $field)); ?>
    </div>
</div>

<?php echo $form->dropDownListRow($model, 'brand', $columns, array('class' => ' span5 tool-tip', 'title' => Yii::t('prices', 'for xml of prices - the name of a tag, for all others - number of a column'), 'maxlength' => 127)); ?>
<div class="control-group ">    
    <?php
    $field = 'brand';
    echo $form->labelEx($model, 'replace_' . $field, array('class' => 'control-label'));
    ?>
    <div class="controls">
        <?php echo $form->textField($model, 'replace_' . $field, array('class' => ' span5 tool-tip', 'placeholder' => Yii::t('prices', 'Fill to populate with default data'), 'maxlength' => 127)); ?>
        <?php echo CHtml::checkBox('replace_' . $field, (!empty($model->replace_article) ? '1' : '0'), array('class' => 'attr_active', 'attr' => $field)); ?>
    </div>
</div>

<?php echo $form->dropDownListRow($model, 'name', $columns, array('class' => ' span5 tool-tip', 'title' => Yii::t('prices', 'for xml of prices - the name of a tag, for all others - number of a column'), 'maxlength' => 127)); ?>
<div class="control-group ">    
    <?php
    $field = 'name';
    echo $form->labelEx($model, 'replace_' . $field, array('class' => 'control-label'));
    ?>
    <div class="controls">
        <?php echo $form->textField($model, 'replace_' . $field, array('class' => ' span5 tool-tip', 'placeholder' => Yii::t('prices', 'Fill to populate with default data'), 'maxlength' => 127)); ?>
        <?php echo CHtml::checkBox('replace_' . $field, (!empty($model->replace_article) ? '1' : '0'), array('class' => 'attr_active', 'attr' => $field)); ?>
    </div>
</div>

<?php echo $form->dropDownListRow($model, 'price', $columns, array('class' => ' span5 tool-tip', 'title' => Yii::t('prices', 'for xml of prices - the name of a tag, for all others - number of a column'), 'maxlength' => 127)); ?>
<div class="control-group ">    
    <?php
    $field = 'price';
    echo $form->labelEx($model, 'replace_' . $field, array('class' => 'control-label'));
    ?>
    <div class="controls">
        <?php echo $form->textField($model, 'replace_' . $field, array('class' => ' span5 tool-tip', 'placeholder' => Yii::t('prices', 'Fill to populate with default data'), 'maxlength' => 127)); ?>
        <?php echo CHtml::checkBox('replace_' . $field, (!empty($model->replace_article) ? '1' : '0'), array('class' => 'attr_active', 'attr' => $field)); ?>
    </div>
</div>

<?php echo $form->dropDownListRow($model, 'quantum', $columns, array('class' => ' span5 tool-tip', 'title' => Yii::t('prices', 'for xml of prices - the name of a tag, for all others - number of a column'), 'maxlength' => 127)); ?>
<div class="control-group ">
    <?php
    $field = 'quantum';
    echo $form->labelEx($model, 'replace_' . $field, array('class' => 'control-label'));
    ?>
    <div class="controls">
        <?php echo $form->textField($model, 'replace_' . $field, array('class' => ' span5 tool-tip', 'placeholder' => Yii::t('prices', 'Fill to populate with default data'), 'maxlength' => 127)); ?>
        <?php echo CHtml::checkBox('replace_' . $field, (!empty($model->replace_article) ? '1' : '0'), array('class' => 'attr_active', 'attr' => $field)); ?>
    </div>
</div>

<?php echo $form->dropDownListRow($model, 'multiply', $columns, array('class' => ' span5 tool-tip', 'title' => Yii::t('prices', 'for xml of prices - the name of a tag, for all others - number of a column'), 'maxlength' => 127)); ?>
<div class="control-group ">
    <?php
    $field = 'multiply';
    echo $form->labelEx($model, 'replace_' . $field, array('class' => 'control-label'));
    ?>
    <div class="controls">
        <?php echo $form->textField($model, 'replace_' . $field, array('class' => ' span5 tool-tip', 'placeholder' => Yii::t('prices', 'Fill to populate with default data'), 'maxlength' => 127)); ?>
        <?php echo CHtml::checkBox('replace_' . $field, (!empty($model->replace_article) ? '1' : '0'), array('class' => 'attr_active', 'attr' => $field)); ?>
    </div>
</div>

<?php echo $form->dropDownListRow($model, 'delivery', $columns, array('class' => ' span5 tool-tip', 'title' => Yii::t('prices', 'for xml of prices - the name of a tag, for all others - number of a column'), 'maxlength' => 127)); ?>
<div class="control-group ">    
    <?php
    $field = 'delivery';
    echo $form->labelEx($model, 'replace_' . $field, array('class' => 'control-label'));
    ?>
    <div class="controls">
        <?php echo $form->textField($model, 'replace_' . $field, array('class' => ' span5 tool-tip', 'placeholder' => Yii::t('prices', 'Fill to populate with default data'), 'maxlength' => 127)); ?>
        <?php echo CHtml::checkBox('replace_' . $field, (!empty($model->replace_article) ? '1' : '0'), array('class' => 'attr_active', 'attr' => $field)); ?>
    </div>
</div>

<?php echo $form->checkBoxRow($model, 'send_admin_mail', array('class' => ' tool-tip', 'title' => '')); ?>
<?php echo $form->checkBoxRow($model, 'active_state', array('class' => ' tool-tip', 'title' => '')); ?>

<?php echo $form->textFieldRow($model, 'xml_element_tag', array('class' => ' span5 tool-tip', 'title' => Yii::t('prices', 'xml for the price - the name of the tag parts'))); ?>
<p class="hint"><?= Yii::t('prices', 'Required only for xml files') ?></p>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('prices', 'Add') : Yii::t('prices', 'Save'),
    ));
    ?>
</div>

<?php
Yii::app()->clientScript->registerScript('form_update_faster', "
   
$('#PricesFtpAutoloadRules_method_type').change(function(){
    $('.change_all').attr('disabled','on');
    $('.change_'+$('#PricesFtpAutoloadRules_method_type').val()).removeAttr('disabled');
    if($('#PricesFtpAutoloadRules_method_type').val() == 'url'){
        console.log('url');
        $('#PricesFtpAutoloadRules_remote_url').attr('required','required');
    }else{
        $('#PricesFtpAutoloadRules_remote_url').removeAttr('required');
    }
});

$('body').ready(function(){
    $('.change_all').attr('disabled','on');
    $('.change_'+$('#PricesFtpAutoloadRules_method_type').val()).removeAttr('disabled');
});


$('.attr_active').change(function(){
field=$(this).attr('attr');
    if($('#PricesFtpAutoloadRules_replace_'+field).attr('disabled')==undefined){
        $('#PricesFtpAutoloadRules_replace_'+field).attr('disabled','on');
        $('#PricesFtpAutoloadRules_'+field).removeAttr('disabled');
        $('#PricesFtpAutoloadRules_replace_'+field).val('');
        
    }else{
        $('#PricesFtpAutoloadRules_replace_'+field).removeAttr('disabled');
        $('#PricesFtpAutoloadRules_'+field).attr('disabled','on');
    }});

$('.attr_active').each(function(){
    field=$(this).attr('attr');
    if($('#PricesFtpAutoloadRules_replace_'+field).val==''){
        $('#PricesFtpAutoloadRules_replace_'+field).removeAttr('disabled');
        $('#PricesFtpAutoloadRules_'+field).attr('disabled','on');
    }else{
        $('#PricesFtpAutoloadRules_replace_'+field).attr('disabled','on');
        $('#PricesFtpAutoloadRules_'+field).removeAttr('disabled');
    }
});


");
?>

<?php $this->endWidget(); ?>
