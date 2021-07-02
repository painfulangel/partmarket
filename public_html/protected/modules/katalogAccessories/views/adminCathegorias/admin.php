<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('katalogAccessories', 'Edit categories')));

$this->pageTitle = Yii::t('katalogAccessories', 'Edit categories');

Yii::app()->clientScript->registerCssFile('/libs/treetable/jquery.treeTable.css');
Yii::app()->clientScript->registerScriptFile('/libs/treetable/jquery.treeTable.js');
Yii::app()->clientScript->registerScript('treetable', "
$('.table').treeTable({
	expandable: true,
	initialState: 'expanded'
});
");

$this->admin_subheader = array(
    array(
        'name' => Yii::t('admin_layout', 'Categories'),
        'url' => array('/katalogAccessories/adminCathegorias/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Goods'),
        'url' => array('/katalogAccessories/adminItems/admin'),
        'active' => false,
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

<h1><?= Yii::t('katalogAccessories', 'Edit categories') ?></h1>

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('katalogAccessories', 'Create'), array('create'), array('class' => 'btn')) ?>
</div>


<?php
$this->widget('TbGridViewTree', array(
    'id' => 'page-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => "js:function() {
        $('.table').treeTable({
                expandable: true,
                initialState: 'expanded'
        });
        }",
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
            'name' => 'slug',
            'headerHtmlOptions' => array(
                'width' => 200,
            ),
        ),
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
            'name' => 'order',
            'class' => 'ext.OrderColumn.NestedSetOrderColumn',
            'htmlOptions' => array('style' => 'min-width: 25px;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        	'buttons' => array(
        		'view' => array(
        			'url' => 'Yii::app()->createAbsoluteUrl(\'/katalogAccessories/cathegorias/view\', array(\'id\' => $data->id))',
        			'options' => array('target' => '_blank'),
        		),
        	),
            'template' => '{view} {update} {delete}',
        ),
    ),
))
?>