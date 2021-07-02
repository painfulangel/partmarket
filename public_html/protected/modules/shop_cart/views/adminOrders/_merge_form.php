<div id="merge_form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'merge-form',
        'enableAjaxValidation' => false,
        'type' => 'horizontal',
        'action' => array('adminOrders/mergeOrders'),
            // 'htmlOptions' => array('target' => '_footer_iframe'),
    ));
    ?>
    <?php echo $form->hiddenField($model, 'id', array('class' => 'span5 merge_form_field', 'maxlength' => 255)) ?>

    <div id="merge_form_fields">
        <div class="control-group ">
            <?php echo $form->labelEx($model, 'id', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo CHtml::textField('merge_id[]', '', array('class' => 'span5 merge_form_field', 'maxlength' => 255)) ?>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <?php
//        $this->widget('bootstrap.widgets.TbButton', array(
//            'buttonType' => 'submit',
//            'type' => 'primary',
//            'label' => $model->isNewRecord ? 'Добавить' : 'Сохранить',
//        ));
        echo CHtml::link(Yii::t('shop_cart', 'One more ID'), '', array('class' => 'btn btn-primary', 'onclick' => 'ShopCartMergeAddField()'));
        echo CHtml::link(Yii::t('shop_cart', 'Unite'), '', array('class' => 'btn btn-primary', 'onclick' => 'ShopCartMergeCheck()'));
        ?>
    </div>


    <?php $this->endWidget(); ?>
</div>