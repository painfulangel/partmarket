<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'prices-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>
<p class="help-block"><?= Yii::t('prices', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('prices', 'Indispensable to filling.') ?></p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 45)); ?>

<?php if ($model->isNewRecord) { ?>

    <?php echo $form->fileFieldRow($model, 'priceFile', array()); ?>
    <?= Yii::t('prices', 'The price list must be in one of the formats: txt (tab separated), CSV (comma separated) or xls, and to have a structure similar') ?>
    <a href="/upload_files/examples/demo_price.zip"><?= Yii::t('prices', 'an example') ?></a>
    <br><br>
<?php } ?>

<?php echo $form->dropDownListRow($model, 'store_id', $stores, array('class' => 'span5', 'id' => 'prices_stores_reload_id')); ?>

<?php if ($model->isNewRecord) { ?>
    <?php echo $form->{Yii::app()->controller->module->radionButtonFunction}($model, 'priceCharset', Yii::app()->controller->module->extraCharacters, array('class' => '')); ?>
<?php } ?>

<div id="price_reload_form">
    <?php echo $form->textFieldRow($model, 'delivery', array('class' => 'span5')); ?>

    <?php echo $form->dropDownListRow($model, 'price_group_1', $priceGroupsList, array('class' => 'span5')); ?>

    <?php echo $form->dropDownListRow($model, 'price_group_2', $priceGroupsList, array('class' => 'span5')); ?>

    <?php echo $form->dropDownListRow($model, 'price_group_3', $priceGroupsList, array('class' => 'span5')); ?>

    <?php echo $form->dropDownListRow($model, 'price_group_4', $priceGroupsList, array('class' => 'span5')); ?>

    <?php echo $form->dropDownListRow($model, 'currency', $currencies, array('class' => 'span5')); ?>

    <?php echo $form->textFieldRow($model, 'supplier_inn', array('class' => 'span5', 'maxlength' => 20)); ?>

    <?php echo $form->textFieldRow($model, 'supplier', array('class' => 'span5', 'maxlength' => 45)); ?>

    <?php echo $form->CheckBoxRow($model, 'search_state', array('class' => '')); ?>

    <?php echo $form->dropDownListRow($model, 'language', array('' => Yii::t('languages', 'All'), '0' => Yii::t('languages', 'Basic')) + CHtml::listData(Languages::model()->findAll(), 'link_name', 'name'), array('class' => '')); ?>
</div>
<?php echo $form->CheckBoxRow($model, 'active_state', array('class' => '')); ?>
<?php
$reloadFromUrl = Yii::app()->createUrl('/prices/adminStores/getFormBlock');

$update_load = '';
if ($model->isNewRecord)
    $update_load = "$('#prices_stores_reload_id').change();";


$script = <<<SCRIPT
            $('#prices_stores_reload_id').change(function(){
                $('#price_reload_form').load('$reloadFromUrl?id='+$('#prices_stores_reload_id').attr('value'));
   });
        
        $update_load
SCRIPT;
Yii::app()->clientScript->registerScript(__CLASS__ . "#pricesFormReload", $script, CClientScript::POS_END);
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

<?php $this->endWidget(); ?>
