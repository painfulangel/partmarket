<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('prices', 'Editing warehouses')));

$this->pageTitle = Yii::t('prices', 'Editing warehouses');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('stores-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1><?= Yii::t('prices', 'Editing warehouses') ?></h1>
<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('prices', 'Create'), array('create'), array('class' => 'btn')) ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'stores-grid',
    'dataProvider' => $model->search(),
    //'filter'=>$model,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier_inn',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'count_state',
             'filter' => array('0' => Yii::t('prices', 'No'), '1' => Yii::t('prices', 'Yes')),
            'checkedButtonLabel' => Yii::t('prices', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('prices', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'my_available',
             'filter' => array('0' => Yii::t('prices', 'No'), '1' => Yii::t('prices', 'Yes')),
            'checkedButtonLabel' => Yii::t('prices', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('prices', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'prepay',
    		'filter' => array('0' => Yii::t('prices', 'No'), '1' => Yii::t('prices', 'Yes')),
    		'checkedButtonLabel' => Yii::t('prices', 'Deactivate'),
    		'uncheckedButtonLabel' => Yii::t('prices', 'Activate'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete} ',
            'buttons' => array(
                'update' => array(
                    'url' => 'array(\'update\', \'id\' => $data->id, \'Stores_page\' => (isset($_GET[\'Stores_page\']) ? $_GET[\'Stores_page\'] : \'\'))',
                ),
            ),
        ),
    ),
));
?>
