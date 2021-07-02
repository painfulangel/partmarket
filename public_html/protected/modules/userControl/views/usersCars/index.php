<?php
$this->breadcrumbs = array(
    Yii::t('userControl', 'My Cars'),
);

$this->pageTitle = Yii::t('userControl', 'My Cars');
?>

<h1><?= Yii::t('userControl', 'My Cars') ?></h1>



<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'users-cars-grid',
    'dataProvider' => $model->search(),
    //'filter'=>$model,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'model',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("model")
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'brand',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("brand")
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'vin',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("vin")
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'year',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("year")
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            //'viewButtonIcon'=>'fa fa-eye mobile-btn-icon',
            'updateButtonIcon'=>'fa fa-pencil mobile-btn-icon',
            'deleteButtonIcon'=>'fa fa-trash mobile-btn-icon',
            'buttons' => array(
                'delete' => array(
                    'url' => "array('/userControl/usersCars/delete', 'id' => \$data->id)",
                ),
                'update' => array(
                    'url' => "array('/userControl/usersCars/update', 'id' => \$data->id)",
                ),
            ),
        ),
    ),
));
?>

<div class="form-actions">
    <?= CHtml::link(Yii::t('userControl', 'Add new car'), array('usersCars/create'), array('class' => 'btn btn-primary')) ?>
</div>