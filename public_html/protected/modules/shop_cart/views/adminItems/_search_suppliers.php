<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'type' => 'horizontal',
        ));
$itemStatus = new ItemsStatus;
?>
<div class="clear"></div>
<div class="span4">
    <?php echo $form->dropDownListRow($model, 'supplier', AllSuppliers::model()->getList(), array('class' => 'span2 admin_search_field','style'=>'width:170px')); ?>
</div>
<div class="span4">
    <?php echo $form->dropDownListRow($model, 'get_status', AllSuppliers::model()->getStateList(), array('class' => 'span2 admin_search_field','style'=>'width:170px')); ?>
</div>
<div class="clear"></div>
<div class="span4">
    <div class="control-group ">
        <?php echo $form->labelEx($model, 'date_from', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'language' => 'ru',
                'attribute' => 'date_from',
                'htmlOptions' => array(
                    'id' => 'date_from',
                    'class' => 'span2 admin_search_field',
                    'style'=>'width:170px'
                ),
                'options' => array(
                    'showAnim' => 'fold',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'showButtonPanel' => 'true',
                    'dateFormat' => 'dd.mm.yy',
                ),
                    ), true);
            ?>   
        </div>
    </div>
</div>
<div class="span4">
    <div class="control-group ">
        <?php echo $form->labelEx($model, 'date_to', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'language' => 'ru',
                'attribute' => 'date_to',
                'htmlOptions' => array(
                    'id' => 'date_to',
                    'class' => 'span2 admin_search_field',
                    'style'=>'width:170px'
                ),
                'options' => array(
                    'showAnim' => 'fold',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'showButtonPanel' => 'true',
                    'dateFormat' => 'dd.mm.yy',
                ),
                    ), true);
            ?>   
        </div>
    </div>
</div>
<div class="clear"></div>
<?php echo $form->radioButtonListInlineRow($model, 'duration', CTimeDuration::getList(), array()); ?>
<div class="span8 pull-center">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('shop_cart', 'Search'),
    ));
    ?>
</div>
<div class="clear"></div>


<?php $this->endWidget(); ?>
