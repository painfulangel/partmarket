<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('katalogVavto', 'Editing goods')));

$this->pageTitle = Yii::t('katalogVavto', 'Editing goods');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('katalog-accessories-items-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->admin_subheader = array(
	array(
		'name' => Yii::t('admin_layout', 'Settings'),
		'url' => array('/katalogVavto/adminKatalog/admin'),
		'active' => false,
	),
    array(
        'name' => Yii::t('admin_layout', 'Brands'),
        'url' => array('/katalogVavto/adminBrands/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Model'),
        'url' => array('/katalogVavto/adminCars/admin'),
        'active' => false,
    ),
    array(
        'name' =>Yii::t('admin_layout', 'Goods'),
        'url' => array('/katalogVavto/adminItems/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Export brands'),
        'url' => array('/katalogVavto/adminBrands/export'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Import brands'),
        'url' => array('/katalogVavto/adminBrands/import'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Exporting models'),
        'url' => array('/katalogVavto/adminCars/export'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Import models'),
        'url' =>array('/katalogVavto/adminCars/import'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Exports of goods'),
        'url' => array('/katalogVavto/adminItems/export'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Imports of goods'),
        'url' => array('/katalogVavto/adminItems/import'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('katalogVavto', 'Editing goods') ?></h1>

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('katalogVavto', 'Create'), array('create', 'id' => !empty($model->cathegory_id) ? $model->cathegory_id : ''), array('class' => 'btn')) ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'katalog-accessories-items-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'title',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'slug',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'cathegory_id',
            'filter' => CHtml::listData(KatalogVavtoCars::model()->findAll(), 'id', 'title'),
            'value' => '$data->cath->title',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'price',
//            'type' => 'raw',
//            'value' => 'Yii::app()->controller->module->getPriceFormatFunction($data->price)',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.AttachmentBehavior.TbEImageColumn',
            'name' => 'image',
            'sortable' => true,
            'filter' => array('1' => Yii::t('katalogVavto', 'Yes'), '2' => Yii::t('katalogVavto', 'No')),
            'noFileFound' => '/images/nofoto.png',
            'htmlOptions' => array('style' => 'max-width: 60px; max-height: 60px !important; margin: 5px;'),
            'headerHtmlOptions' => array('style' => 'text-align:center; vertical-align;'),
        ),
//        'meta_title',
//        'meta_description',
//        'meta_keywords',
//        'root',
//        'lft',
        /*
          'rgt',
          'level',
          'parent_id',
          'order',
          'title',
          'short_title',
          'text',
          'short_text',
          'slug',
          'image',
          'cathegory_id',
          'price',
          'supplier',
          'supplier_inn',
         */
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'active_state',
            'filter' => array('0' => Yii::t('katalogVavto', 'No'), '1' => Yii::t('katalogVavto', 'Yes')),
            'checkedButtonLabel' => Yii::t('katalogVavto', 'Disable'),
            'uncheckedButtonLabel' => Yii::t('katalogVavto', 'Enable'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'in_stock',
    		'filter' => array('0' => Yii::t('katalogVavto', 'No'), '1' => Yii::t('katalogVavto', 'Yes')),
    		'checkedButtonLabel' => Yii::t('katalogVavto', 'Disable'),
    		'uncheckedButtonLabel' => Yii::t('katalogVavto', 'Enable'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'buttons' => array(
                'view' => array(
                    'url' => 'array(\'items/view\',\'id\'=>$data->id)',
                ),
            ),
        ),
    ),
));
?>
