<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('katalogAccessories', 'Editing products')));

$this->pageTitle = Yii::t('katalogAccessories', 'Editing products');

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
        'name' => Yii::t('admin_layout', 'Categories'),
        'url' => array('/katalogAccessories/adminCathegorias/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Goods'),
        'url' => array('/katalogAccessories/adminItems/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Export categories'),
        'url' => array('/katalogAccessories/adminCathegorias/export'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Import categories'),
        'url' => array('/katalogAccessories/adminCathegorias/import'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Exports of goods'),
        'url' => array('/katalogAccessories/adminItems/export'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Imports of goods'),
        'url' => array('/katalogAccessories/adminItems/import'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('katalogAccessories', 'Editing products') ?></h1>

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('katalogAccessories', 'Create'), array('create', 'id' => !empty($model->cathegory_id) ? $model->cathegory_id : ''), array('class' => 'btn')) ?>
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
            'filter' => CHtml::listData(KatalogAccessoriesCathegorias::model()->findAll(), 'id', 'title'),
            'value' => '$data->cath->title',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price',
            'type' => 'raw',
            'value' => 'Yii::app()->controller->module->getPriceFormatFunction($data->price)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'image',
            'type' => 'raw',
            'value' => 'CHtml::image($data->getImage())',
            'htmlOptions' => array('class' => 'acc_image'),
            'headerHtmlOptions' => array('style' => 'text-align:center; vertical-align;'),
        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'image',
//            'type' => 'raw',
//            'filter' => array('1' => 'Да', '2' => 'Нет'),
//            'value' => '$data->image==null?\'Нет\':\'Да\'',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
//        array(
//            'class' => 'ext.AttachmentBehavior.TbEImageColumn',
//            'name' => 'image',
//            'sortable' => true,
//            'filter' => array('1' => 'Да', '2' => 'Нет'),
//            'value' => '$data->getImage()',
////            'noFileFound' => '$data->getImage()',
//            'htmlOptions' => array('style' => 'width: 60px; height: 60px; !important; margin: 5px;'),
//            'headerHtmlOptions' => array('style' => 'text-align:center; vertical-align;'),
//        ),
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
            'filter' => array('0' => Yii::t('katalogAccessories', 'No'), '1' => Yii::t('katalogAccessories', 'Yes')),
            'checkedButtonLabel' => Yii::t('katalogAccessories', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('katalogAccessories', 'Activate'),
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
<style>
	td.acc_image {
		width: 60px;
		height: 60px !important;
		margin: 5px;
	}
	
	td.acc_image img {
		max-height: 60px;
		max-width: 60px;
	}
</style>