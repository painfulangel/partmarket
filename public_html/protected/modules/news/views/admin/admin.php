<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('news', 'Editing news')));

$this->pageTitle = Yii::t('news', 'Editing news');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('news-grid', {
		data: $(this).serialize()
	});
	return false;
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
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Clients reviews'),
        'url' => array('/requests/adminFeedbacks/admin'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('news', 'Editing news') ?></h1>
<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('news', 'Create'), array('create'), array('class' => 'btn')) ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'news-grid',
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
            'name' => 'title',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'short_title',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'short_text',
        	'type' => 'raw',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'link',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'active_state',
            'filter' => array('0' => Yii::t('news', 'No'), '1' => Yii::t('news', 'Yes')),
            'checkedButtonLabel' => Yii::t('news', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('news', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'visibility_state',
            'filter' => array('0' => Yii::t('news', 'No'), '1' => Yii::t('news', 'Yes')),
            'checkedButtonLabel' => Yii::t('news', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('news', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'buttons' => array(
                'update' => array(
                    'url' => 'array(\'update\', \'id\' => $data->id, \'News_page\' => (isset($_GET[\'News_page\']) ? $_GET[\'News_page\'] : \'\'))',
                ),
                'view' => array(
                    'url' => 'array(\'/news/default/view\', \'link\' => $data->link)',
                ),
            ),
        ),
    ),
));
?>
