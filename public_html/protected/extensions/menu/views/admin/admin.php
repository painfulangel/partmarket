<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('menu', 'Menu')));

$this->pageTitle = Yii::t('menu', 'Menu');


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('menus-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Menu'),
        'url' => array('/menu/admin/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Page – Side'),
        'url' => array('/pages_left/admin/index'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Page – Top'),
        'url' => array('/pages_top/admin/index'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'News'),
        'url' => array('/news/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Clients reviews'),
        'url' => array('/requests/adminFeedbacks/admin'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('menu', 'Menu') ?></h1>
<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('menu', 'Create'), array('create'), array('class' => 'btn')) ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'menus-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        //'id',
        // 'order',
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'menu_type',
            'value' => 'Yii::app()->controller->module->getType($data->menu_type)',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'echo_position',
            'value' => 'Yii::app()->controller->module->getPosition($data->echo_position)',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'menu_value',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'title',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'visible',
            'filter' => array('0' => Yii::t('menu', 'No'), '1' => Yii::t('menu', 'Yes')),
            'checkedButtonLabel' => Yii::t('menu', 'Disable'),
            'uncheckedButtonLabel' => Yii::t('menu', 'Enable'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'name' => 'order',
            'class' => 'ext.OrderColumn.OrderColumn',
            'htmlOptions' => array('class' => 'order_buttons'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            'buttons' => array(
                'update' => array(
                    'url' => 'array(\'update\', \'id\' => $data->id, \'Menus_page\' => (isset($_GET[\'Menus_page\']) ? $_GET[\'Menus_page\'] : \'\'))',
                ),
            ),
        ),
    ),
));
?>
