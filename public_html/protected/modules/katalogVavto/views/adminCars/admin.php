<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('katalogVavto', 'Editing models')));

$this->pageTitle = Yii::t('katalogVavto', 'Editing models');

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
        'active' => true,
    ),
    array(
        'name' =>Yii::t('admin_layout', 'Goods'),
        'url' => array('/katalogVavto/adminItems/admin'),
        'active' => false,
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

<h1><?= Yii::t('katalogVavto', 'Editing models') ?></h1>

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('katalogVavto', 'Create'), array('create'), array('class' => 'btn')) ?>
</div>


<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'page-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'headerHtmlOptions' => array(
                'width' => 50,
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'title',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'short_title',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'parent_id',
            'value' => '$data->brand->title',
            'filter' => ($model->selectList()),
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'name' => 'slug',
            'headerHtmlOptions' => array(
                'width' => 200,
            ),
        ),
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
            'name' => 'order',
            'class' => 'ext.OrderColumn.OrderColumn',
            'htmlOptions' => array('style' => 'min-width: 25px;'),
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
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view} {update} {delete}',
        ),
    ),
))
?>
