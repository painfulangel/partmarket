<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('pricegroups', 'Edit the rules')));

$this->pageTitle = Yii::t('pricegroups', 'Edit the rules');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('prices-rules-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Price politics'),
        'url' => array('/pricegroups/adminGroups/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Payment system'),
        'url' => array('/webPayments/adminWebPaymentsSystem/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Currency'),
        'url' => array('/currencies/admin/admin'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('pricegroups', 'Edit the rules') ?></h1>
<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('pricegroups', 'Create'), array('create', 'id' => (isset($_GET['id']) ? $_GET['id'] : '')), array('class' => 'btn')) ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'prices-rules-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        // 'id',
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'group_id',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'top_value',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'koeficient',
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
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            'buttons' => array(
                'update' => array(
                    'url' => 'array(\'update\', \'id\' => $data->id, \'PricesRules_page\' => (isset($_GET[\'PricesRules_page\']) ? $_GET[\'PricesRules_page\'] : \'\'))',
                ),
            ),
        ),
    ),
));
?>
