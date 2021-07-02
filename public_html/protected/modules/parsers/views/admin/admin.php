<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('parsers', 'Parsers/API price')));

$this->pageTitle =Yii::t('parsers', 'Parsers/API price');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('parsers-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->admin_header = array(
    array(
        'name' => Yii::t('prices', 'Editing warehouses'),
        'url' => array('/prices/adminStores/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('prices', 'Prices'),
        'url' => array('/prices/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('crosses', 'Cross-tables'),
        'url' => array('/crosses/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Suppliers'),
        'url' => array('/parsersApi/admin/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('shop_cart', 'Orders to suppliers'),
        'url' => array('/shop_cart/adminItems/supplierOrder'),
        'active' => false,
    ),
    array(
        'name' =>Yii::t('prices', 'Search meta-tags'),
        'url' => array('/prices/adminMeta/admin'),
        'active' => false,
    ),
    array(
        'name' =>Yii::t('brands', 'Brands'),
        'url' => array('/brands/admin/admin'),
        'active' => false,
    ),
);

$this->admin_subheader = array(
   
    array(
        'name' => Yii::t('parsersApi', 'Parsers price'),
        'url' => array('/parsers/admin/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('parsersApi', 'Parsers/API price'),
        'url' => array('/parsersApi/admin/admin'),
        'active' => false,
    ),
    
);
?>

<h1><?= Yii::t('parsers', 'Parsers/API price') ?></h1>

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('parsers', 'Create'), array('create'), array('class' => 'btn')) ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'parsers-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
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
            'value' => '$data->name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier_inn',
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
            'type' => 'raw',
            'name' => 'create_date',
            'value' => 'date("d.m.Y","{$data->create_date}")',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'active_state',
            'filter' => array('0' => Yii::t('parsers', 'No'), '1' => Yii::t('parsers', 'Yes')),
            'checkedButtonLabel' => Yii::t('parsers', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('parsers', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        /*
         * 'price_group_1',
          'price_group_2',
          'price_group_3',
          'price_group_4',
          'active_state',
          'delivery',

          'create_date',
          'currency',
          'codeblock',
         */
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            'buttons' => array(
                'update' => array(
                    'url' => 'array(\'update\', \'id\' => $data->id, \'Parsers_page\' => (isset($_GET[\'Parsers_page\']) ? $_GET[\'Parsers_page\'] : \'\'))',
                ),
            ),
        ),
    ),
));
?>
