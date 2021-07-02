<?php
/* @var $this CrossesDataController */
/* @var $model CrossesData */

$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('crosses', 'Cross-tables') => array('crossBase', 'base_id' => $base_id), Yii::t('crosses', 'Create New Element')));

$this->pageTitle = Yii::t('crosses', 'Create New Element');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('crosses-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
$this->admin_header = $top_menu;
?>
<h1><?= Yii::t('crosses', 'Create New Element') ?></h1>
<?php $this->renderPartial('_form_element', array('model' => $model)); ?>