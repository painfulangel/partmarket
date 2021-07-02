<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'users-cars-grid',
    'dataProvider' => $details->search(),
    //'filter'=>$model,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'brand',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$details->getAttributeLabel("brand")
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'article',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$details->getAttributeLabel("article")
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$details->getAttributeLabel("name")
            ),
        ),/*
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'filter' => '',
            'name' => 'price',
            'value' => '$data->getButton()',//'<button rel="$data->article" class="btn">'.Yii::t('userControlDetail', 'Price').'</button>',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),*/
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            //'viewButtonIcon'=>'fa fa-eye mobile-btn-icon',
            //'updateButtonIcon'=>'fa fa-pencil mobile-btn-icon',
            //'deleteButtonIcon'=>'fa fa-trash mobile-btn-icon',
            'buttons' => array(
                'delete' => array(
                    'url' => "array('/userControl/adminUsersCars/deleteDetail', 'id' => \$data->id)",
                ),
                'update' => array(
                    'url' => "\$data->id",
                    'click' => 'function(){ updateDetail($(this)); return false; }',
                ),
            ),
        ),
    ),
));
?>
<button class="btn add_detail"><?php echo Yii::t('userControlDetail', 'Add detail'); ?></button>
<div class="add_detail">
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'type' => 'horizontal',
    'id' => 'users-cars-detail-form',
    'enableAjaxValidation' => false,
));
?>
<input type="hidden" name="id">
<?php echo $form->errorSummary($model);
echo $form->textFieldRow($model, 'brand', array('class' => 'span5', 'maxlength' => 100));
echo $form->textFieldRow($model, 'article', array('class' => 'span5', 'maxlength' => 100));
echo $form->textAreaRow($model, 'name', array('rows' => 6, 'cols' => 50, 'class' => 'span5')); ?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('userControl', 'Save'),
    ));
    ?>
</div>
<?php
$this->endWidget();
?>
</div>