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
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'brand',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'vin',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'year',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
        ),
    ),
));
?>

<div class="form-actions">
    <?= CHtml::link(Yii::t('userControl', 'To add the new car'), array('usersCars/create'), array('class' => 'btn btn-primary')) ?>
</div>
