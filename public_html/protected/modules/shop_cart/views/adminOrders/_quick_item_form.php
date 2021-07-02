<div id="quick_item_form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'items-form',
        'action' => array('adminItems/create'),
        'enableAjaxValidation' => false,
        'type' => 'horizontal',
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->hiddenField($model, 'order_id'); ?>
    <?php echo $form->hiddenField($model, 'user_id'); ?>

    <?php echo $form->textFieldRow($model, 'brand', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'article', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'price', array('class' => 'span5', 'maxlength' => 45)); ?>

    <?php echo $form->textFieldRow($model, 'quantum', array('class' => 'span5')); ?>

    <?php echo $form->textFieldRow($model, 'delivery', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'supplier_inn', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'supplier', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'store', array('class' => 'span5', 'maxlength' => 255)); ?>

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
</div>