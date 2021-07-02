<br>
<br>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'type' => 'horizontal',
        ));
$itemStatus = new ItemsStatus;
?>
<div class="span2">
    <div class="control-group ">
        <?php echo $form->labelEx($model, 'article', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'article', array('class' => 'span1', 'style' => 'position: relative;z-index:1')); ?>
        </div>
    </div>
</div>
<div class="span2">
    <div class="control-group ">
        <?php echo $form->labelEx($model, 'order_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'order_id', array('class' => 'span1', 'style' => 'position: relative;z-index:1')); ?>
        </div>
    </div>
    <?php // echo $form->textFieldRow($model, 'order_id', array('class' => '')); ?>
</div>
<div class="span4">
    <?php echo $form->dropDownListRow($model, 'status', $itemStatus->getSearchList(), array('class' => 'span2')); ?>
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
                    'class' => 'span2',
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
                    'class' => 'span2',
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

<div class="span2 pull-right">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => 'Поиск',
    ));
    ?>
</div>
<div class="clear"></div>


<?php $this->endWidget(); ?>
