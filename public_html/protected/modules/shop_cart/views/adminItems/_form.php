<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'items-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
        ));
$itemStatus = new ItemsStatus;
?>


<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'brand', array('class' => 'span5', 'maxlength' => 255, 'disabled' . $model->isFormEnabled() => 'on')); ?>

<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255, 'disabled' . $model->isFormEnabled() => 'on')); ?>

<?php echo $form->textFieldRow($model, 'article', array('class' => 'span5', 'maxlength' => 255, 'disabled' . $model->isFormEnabled() => 'on')); ?>

<?php echo $form->textFieldRow($model, 'price', array('class' => 'span5', 'maxlength' => 45, 'onchange' => 'ShopCartPriceAllChange()', 'disabled' . $model->isFormEnabled() => 'on')); ?>

<?php echo $form->textFieldRow($model, 'quantum', array('class' => 'span5', 'onchange' => 'ShopCartPriceAllChange()', 'disabled' . $model->isFormEnabled() => 'on')); ?>
<div class="control-group ">
    <?php echo $form->labelEx($model, 'price_total', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php echo CHtml::textField('Items_price_all', $model->quantum * $model->price, array('class' => 'span5 merge_form_field', 'maxlength' => 255, 'disabled' => 'on')) ?>
    </div>
</div>

<?php echo $form->textFieldRow($model, 'delivery', array('class' => 'span5', 'maxlength' => 255, 'disabled' . $model->isFormEnabled() => 'on')); ?>

<?php echo $form->textFieldRow($model, 'supplier_inn', array('class' => 'span5', 'maxlength' => 255, 'disabled' . $model->isFormEnabled() => 'on')); ?>

<?php echo $form->textFieldRow($model, 'supplier', array('class' => 'span5', 'maxlength' => 255, 'disabled' . $model->isFormEnabled() => 'on')); ?>

<?php echo $form->textFieldRow($model, 'store', array('class' => 'span5', 'maxlength' => 255, 'disabled' . $model->isFormEnabled() => 'on')); ?>

<?php echo $form->dropDownListRow($model, 'status', $itemStatus->getList(), array('class' => 'span5', 'disabled' . $model->isFormEnabled() => 'on')); ?>

<?php echo $form->textAreaRow($model, 'description', array('rows' => 3, 'cols' => 50, 'class' => 'span5')); ?>


<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('shop_cart', 'Add') : Yii::t('shop_cart', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
