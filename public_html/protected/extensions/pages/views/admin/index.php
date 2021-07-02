<?php
$this->pageTitle = Yii::t('pages', 'Static pages').' ' . (Yii::app()->controller->module->position == '_top' ? Yii::t('pages', '(top)') : Yii::t('pages', '(side)') );

$this->breadcrumbs = AdminBreadcrumbs::get(array($this->pageTitle));
Yii::app()->clientScript->registerCssFile('/libs/treetable/jquery.treeTable.css');
Yii::app()->clientScript->registerScriptFile('/libs/treetable/jquery.treeTable.js');
Yii::app()->clientScript->registerScript('treetable', "
$('.table').treeTable({
	expandable: true,
	initialState: 'expanded'
});
");
$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Menu'),
        'url' => array('/menu/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Page – Side'),
        'url' => array('/pages_left/admin/index'),
        'active' => Yii::app()->controller->module->position == '_left' ? true : false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Page – Top'),
        'url' => array('/pages_top/admin/index'),
        'active' => Yii::app()->controller->module->position == '_top' ? true : false,
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

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('pages', 'Create page'), array('create'), array('class' => 'btn')) ?>
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
            'name' => 'page_title',
        ),
        array(
            'name' => 'slug',
            'headerHtmlOptions' => array(
                'width' => 200,
            ),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'is_published',
//            'name' => 'active_state',
            'filter' => array('0' => Yii::t('pages', 'No'), '1' => Yii::t('pages', 'Yes')),
            'checkedButtonLabel' => Yii::t('pages', 'Unpublished'),
            'uncheckedButtonLabel' => Yii::t('pages', 'Publish'),
            'headerHtmlOptions' => array('width' => 100),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            //'header' => 'Order',
            'name' => 'order',
            'class' => 'ext.OrderColumn.NestedSetOrderColumn',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
        ),
    ),
))
?>