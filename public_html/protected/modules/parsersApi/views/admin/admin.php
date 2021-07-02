<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('parsersApi', 'Parsers/API price')));

$this->pageTitle = Yii::t('parsersApi', 'Parsers/API price');

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

$this->admin_subheader = array(
   
    array(
        'name' => Yii::t('parsersApi', 'Parsers price'),
        'url' => array('/parsers/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('parsersApi', 'Parsers/API price'),
        'url' => array('/parsersApi/admin/admin'),
        'active' => true,
    ),
    
);
?>

<h1><?= Yii::t('parsersApi', 'Parsers/API price') ?></h1>

<?php
$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs',
    'tabs' => array(
        array(
            'label' => Yii::t('parsersApi', 'Suppliers website'),
            'content' => $this->renderPartial('_admin_content', array('model' => $model), true, false),
            'active' => true
        ),
        array(
            'label' => Yii::t('parsersApi', 'All suppliers'),
            'content' => $this->renderPartial('_all_suppliers', array('model' => $model_all), true, false),
        ),
    ),
))
?>


