<?php
/* @var $this ItemsUsageController */
/* @var $model UsedItemsUsage */

$this->breadcrumbs=array(
	'Used Items Usages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UsedItemsUsage', 'url'=>array('index')),
	array('label'=>'Manage UsedItemsUsage', 'url'=>array('admin')),
);
?>

<h1>Create UsedItemsUsage</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>