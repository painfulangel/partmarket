<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'prices-export-rules-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
        ));
?>
<p class="help-block"><?= Yii::t('prices', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('prices', 'Indispensable to filling.') ?></p>
<?php
	//TbActiveForm
	echo $form->errorSummary($model);
	echo $form->textFieldRow($model, 'rule_name', array('class' => 'span5 ', 'maxlength' => 127));
	echo $form->checkBoxRow($model, 'human_machine', array('class' => '', 'title' => ''));
	echo $form->checkBoxRow($model, 'email_send', array('class' => ' tool-tip email_change_active', 'title' => ''));
	echo $form->textFieldRow($model, 'email', array('class' => 'span5 email_change', 'disabled'.($model->email_send == 0 ? '' : 'fd') => 'on', 'maxlength' => 255));
	echo $form->checkBoxRow($model, 'ftp_send', array('class' => ' tool-tip ftp_change_active', 'title' => ''));
	echo $form->textFieldRow($model, 'ftp_server', array('class' => 'span5 ftp_change', 'disabled'.($model->ftp_send == 0 ? '' : 'fd') => 'on', 'maxlength' => 127));
	echo $form->textFieldRow($model, 'ftp_login', array('class' => 'span5 ftp_change', 'disabled'.($model->ftp_send == 0 ? '' : 'fd') => 'on', 'maxlength' => 127));
	echo $form->textFieldRow($model, 'ftp_password', array('class' => 'span5 ftp_change', 'placeholder' => (!$model->isNewRecord ? Yii::t('prices', 'To change the password, enter a new password.') : ''), 'disabled'.($model->ftp_send == 0 ? '' : 'fd') => 'on', 'maxlength' => 127));
	// echo $form->DropDownListRow($model, 'ftp_auth_type', array('0'=>'Звичайный (логин, пароль)','1'=>'Логин','2'=>'Б',), array('class' => ' span5 tool-tip', 'disabled'.($model->ftp_send == 0 ? '' : 'fd') => 'on', 'title' => ''));
	echo $form->textFieldRow($model, 'ftp_destination_folder', array('class' => 'span5 ftp_change', 'disabled'.($model->ftp_send == 0 ? '' : 'fd') => 'on', 'maxlength' => 255));
	
	echo $form->dropDownListRow($model, 'cron_general', array(
	    '3' => Yii::t('prices', 'Everyone hour'),
	    '4' => Yii::t('prices', 'Each 3 hours'),
	    '5' => Yii::t('prices', 'Each 6 hours'),
	    '6' => Yii::t('prices', 'Each 12 hours'),
	    '7' => Yii::t('prices', 'Every day'),
	    '8' => Yii::t('prices', 'each 2 days'),
	    '9' => Yii::t('prices', 'each 3 days'),
	), array('class' => ' span5 tool-tip', 'maxlength' => 32));
	
	echo $form->dropDownListRow($model, 'price_group', array('1' => Yii::t('prices', 'Price group').' 1', 
															 '2' => Yii::t('prices', 'Price group').' 2', 
															 '3' => Yii::t('prices', 'Price group').' 3', 
															 '4' => Yii::t('prices', 'Price group').' 4',), array('class' => 'span5'));
	echo $form->checkBoxRow($model, 'active_state', array('class' => ' tool-tip', 'title' => ''));
	
	$f = new TbActiveForm();
	
	//26.04.2017
	echo $form->checkBoxRow($model, 'create_common');
?>
<fieldset class="additional_fields">
	<div class="control-group ">
		<label class="control-label"><?php echo Yii::t('prices', 'Type'); ?></label>
		<div class="controls">
<?php
echo $form->radioButtonList($model, 'type_price_delivery', array(1 => Yii::t('prices', 'with the lowest price'),
	2 => Yii::t('prices', 'with smaller delivery time'),
	3 => Yii::t('prices', 'with the lowest price and smaller delivery time'),
));
?>
		</div>
	</div>
<?php	
	echo $form->checkBoxRow($model, 'srok_more');
?>
<div class="control-group ">
	<label class="control-label"></label>
	<div class="controls"><?php echo $form->textField($model, 'srok_days').' '.Yii::t('prices', 'days');?></div>
</div>
<?php
	echo $form->checkBoxRow($model, 'sklad_otd');
?>
</fieldset>
<?php
	//26.04.2017
	
	$this->widget('bootstrap.widgets.TbGridView', array(
	    'id' => 'stores-grid',
	    'dataProvider' => $model->getAllCDataProvider(),
	    //'filter'=>$model,
	    'template' => '{items}',
	    'columns' => array(
	        array(
	            'class' => 'bootstrap.widgets.TbDataColumn',
	            'name' => 'ID',
	            'value' => '$data["id"]',
	            'headerHtmlOptions' => array('style' => 'text-align: center;'),
	            'htmlOptions' => array('style' => 'text-align: center;'),
	        ),
	        array(
	            'class' => 'bootstrap.widgets.TbDataColumn',
	            'name' => Stores::model()->getAttributeLabel('name'),
	            'value' => '$data["name"]',
	            'headerHtmlOptions' => array('style' => 'text-align: center;'),
	            'htmlOptions' => array('style' => 'text-align: center;'),
	        ),
	        array(
	            'class' => 'bootstrap.widgets.TbDataColumn',
	            'name' => Stores::model()->getAttributeLabel('supplier'),
	            'value' => '$data["supplier"]',
	            'headerHtmlOptions' => array('style' => 'text-align: center;'),
	            'htmlOptions' => array('style' => 'text-align: center;'),
	        ),
	        array(
	            'class' => 'bootstrap.widgets.TbDataColumn',
	            'name' => Stores::model()->getAttributeLabel('supplier_inn'),
	            'value' => '$data["supplier_inn"]',
	            'headerHtmlOptions' => array('style' => 'text-align: center;'),
	            'htmlOptions' => array('style' => 'text-align: center;'),
	        ),
	        array(
	            'class' => 'bootstrap.widgets.TbDataColumn',
	            'name' => Stores::model()->getAttributeLabel('delivery'),
	            'value' => '$data["delivery"]',
	            'headerHtmlOptions' => array('style' => 'text-align: center;'),
	            'htmlOptions' => array('style' => 'text-align: center;'),
	        ),
	        array(
	            'class' => 'bootstrap.widgets.TbDataColumn',
	            'type' => 'raw',
	            'name' => Yii::t('prices', 'To include in the price'),
	            'value' => 'CHtml::checkBox("stores[$data[id]]",$data["row_data"])',
	            'headerHtmlOptions' => array('style' => 'text-align: center;'),
	            'htmlOptions' => array('style' => 'text-align: center;'),
	        ),
	    ),
	));
?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('prices', 'Add') : Yii::t('prices', 'Save'),
    ));
    ?>
</div>
<?php if (!$model->create_common) { ?>
<style>
	.additional_fields {
		display: none;
	}
</style>
<?php
    }
Yii::app()->clientScript->registerScript('form_update_faster', "
$('.email_change_active').change(function(){
	if($('.email_change').attr('disabled')==undefined)
		$('.email_change').attr('disabled','on');
    else
		$('.email_change').removeAttr('disabled');
	//return false;
});
$('.ftp_change_active').change(function(){
if($('.ftp_change').attr('disabled')==undefined)
	$('.ftp_change').attr('disabled','on');
        else
	$('.ftp_change').removeAttr('disabled');
//	return false;
});
$('#PricesExportRules_create_common').click(function() {
	if ($(this).prop('checked') == true) {
		$('.additional_fields').show();
	} else {
		$('.additional_fields').hide();
	}
});
");

$this->endWidget(); ?>