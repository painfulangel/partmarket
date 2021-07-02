<?php
$this->breadcrumbs = array(
    Yii::t('prices', 'Prices'),
);

$this->pageTitle=Yii::t('prices', 'Prices');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('crosses-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?= Yii::t('prices', 'Prices') ?></h1>


<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'prices-grid',
    'dataProvider' => $model->search(),
    // 'filter' => $model,
    'columns' => array(
        'id',
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'value' => '$data->name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier',
            'value' => '$data->supplier',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'create_date',
            'type' => 'raw',
            'value' => 'date("d.m.Y","{$data->create_date}")',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view}',
            'buttons' => array(
                'view' => array(
                    'url' => 'array(\'view\', \'id\' => $data->id)',
                ),
            ),
            'htmlOptions' => array('style' => 'width: 90px;'),
        ),
    ),
));
?>
