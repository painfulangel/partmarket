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


<div id="filte">
    <p>


        <?php echo $model->getAttributeLabel('article'); ?> 
        <?php echo $form->textField($model, 'article', array('style' => 'margin:0 3px 0 10px; width:50px;')); ?>

        <?php echo $model->getAttributeLabel('order_id'); ?> 
        <?php echo $form->textField($model, 'order_id', array('style' => 'margin:0 3px 0 10px; width:50px;')); ?>


        <?php echo $model->getAttributeLabel('status'); ?> 
        <?php echo $form->dropDownList($model, 'status', $itemStatus->getSearchList(), array('class' => 'items_change_status', 'stayle' => 'margin:0 3px 0 10px; width:50px;')); ?>



    </p>
    <p><?php echo $model->getAttributeLabel('date_from'); ?> <?php
        echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model' => $model,
            'language' => 'ru',
            'attribute' => 'date_from',
            'htmlOptions' => array(
                'id' => 'date_from',
                'style' => 'margin:0 3px 0 10px; width:50px;',
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
        <?php echo $model->getAttributeLabel('date_to'); ?> 
        <?php
        echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model' => $model,
            'language' => 'ru',
            'attribute' => 'date_to',
            'htmlOptions' => array(
                'id' => 'date_to',
                'style' => 'margin:0 3px 0 10px;  width:50px;',
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

    <p>
        <?php echo $form->radioButtonListInlineRow($model, 'duration', CTimeDuration::getList(), array()); ?>

        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'success',
            'label' => Yii::t('shop_cart', 'Search'),
        ));
        ?> </p>


</div>




<?php $this->endWidget(); ?>
