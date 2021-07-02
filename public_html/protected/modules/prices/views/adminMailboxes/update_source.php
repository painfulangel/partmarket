<?php
$this->breadcrumbs = array(
    Yii::t('prices', 'Mailboxes') => array('admin'),
    Yii::t('prices', 'The edit mailbox'),
);

$this->pageTitle = Yii::t('prices', 'The edit mailbox');

$this->admin_header = array(
    array(
        'name' => Yii::t('prices', 'Editing warehouses'),
        'url' => array('/prices/adminStores/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('prices', 'Prices'),
        'url' => array('/prices/admin/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('crosses', 'Cross-tables'),
        'url' => array('/crosses/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Suppliers'),
        'url' => array('/parsersApi/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('shop_cart', 'Orders to suppliers'),
        'url' => array('/shop_cart/adminItems/supplierOrder'),
        'active' => false,
    ),
);
$this->admin_subheader = array(

    array(
        'name' => Yii::t('prices', 'Prices'),
        'url' => array('/prices/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Auto Price list'),
        'url' => array('/prices/adminAutoloadRules/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Mailboxes'),
        'url' => array('/prices/adminMailboxes/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Export price lists'),
        'url' => array('/prices/adminPricesExportRules/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Load price list'),
        'url' => array('/prices/admin/create'),
        'active' => false,
    ),
);


$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'prices-ftp-autoload-rules-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
));
?>
<h1><?= Yii::t('prices', 'Add mailbox source') ?></h1>
<p class="help-block"><?= Yii::t('prices', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('prices', 'Indispensable to filling.') ?></p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'rule_name', array('class' => 'span5 span5 tool-tip', 'maxlength' => 127)); ?>

<?php echo $form->textFieldRow($model, 'mail_subject', array('class' => 'span5 change_all change_email')); ?>
<?php echo $form->textFieldRow($model, 'mail_body', array('class' => 'span5 change_all change_email')); ?>
<?php echo $form->textFieldRow($model, 'mail_from', array('class' => 'span5 change_all change_email')); ?>
<?php // echo $form->textFieldRow($model, 'mail_file', array('class' => 'span5 change_all change_email')); ?>
<?php echo $form->hiddenField($model, 'mail_id', array('value' => $mailbox->id)); ?>


<?php echo $form->textFieldRow($model, 'search_file_criteria', array('class' => ' span5 tool-tip ', 'title' => '', 'maxlength' => 255)); ?>


<?php echo $form->{Yii::app()->controller->module->radionButtonFunction}($model, 'charset', Yii::app()->controller->module->extraCharacters, array('class' => '')); ?>

<?php echo $form->dropDownListRow($model, 'store_id', CHtml::listData(Stores::model()->findAll(), 'id', 'name'), array('class' => ' span5 tool-tip', 'title' => '')); ?>

<?php
//echo $form->dropDownListRow($model, 'load_period_days', array(
//    '*' => 'каждый день',
//    '*/02' => 'каждые 2 дня',
//    '*/03' => 'каждые 3 дня',
//    '*/04' => 'каждые 4 дня',
//    '*/05' => 'каждые 5 дня',
//        ), array('class' => ' span5 tool-tip', 'title' => '* - для запуска каждый день ; */n - для запуска каждые n дней; n - для запуска в n день', 'maxlength' => 32));
?>

<?php
//echo $form->dropDownListRow($model, 'load_period_hours', array(
//    '*' => 'каждый час',
//    '*/02' => 'каждые 2 часа',
//    '*/03' => 'каждые 3 часа',
//    '*/04' => 'каждые 4 часа',
//    '*/05' => 'каждые 5 часов',
//    '*/06' => 'каждые 6 часов',
//    '*/12' => 'каждые 12 часов',
//    '*/18' => 'каждые 18 часов',
//        ), array('class' => ' span5 tool-tip', 'title' => '* - для запуска каждый час ; */n - для запуска каждые n часов; n - для запуска в n часов', 'maxlength' => 32));
//
?>

<?php
//echo $form->dropDownListRow($model, 'load_period_minutes', array(
//    '*/5' => 'каждые 5 минут',
//    '*/10' => 'каждые 10 минут',
//    '*/15' => 'каждые 15 минут',
//    '*/20' => 'каждые 20 минут',
//    '*/30' => 'каждые 30 минут',
//    '*/45' => 'каждые 45 минут',
//        ), array('class' => ' span5 tool-tip', 'title' => '* - для запуска каждую минуту ; */n - для запуска каждые n минут; n - для запуска в n минут', 'maxlength' => 32));
?>

<?php // echo $form->textFieldRow($model, 'load_period_days', array('class' => ' span5 tool-tip', 'title' => '* - для запуска каждый день ; */n - для запуска каждые n дней; n - для запуска в n день', 'maxlength' => 32));  ?>

<?php // echo $form->textFieldRow($model, 'load_period_hours', array('class' => ' span5 tool-tip', 'title' => '* - для запуска каждый час ; */n - для запуска каждые n часов; n - для запуска в n часов', 'maxlength' => 32));  ?>

<?php // echo $form->textFieldRow($model, 'load_period_minutes', array('class' => ' span5 tool-tip', 'title' => '* - для запуска каждую минуту ; */n - для запуска каждые n минут; n - для запуска в n минут', 'maxlength' => 32));  ?>

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
