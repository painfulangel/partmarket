<?php
/* @var $this ItemsUsageController */
/* @var $model UsedItemsUsage */

$this->breadcrumbs=array(
	'Used Items Usages'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UsedItemsUsage', 'url'=>array('index')),
	array('label'=>'Create UsedItemsUsage', 'url'=>array('create')),
	array('label'=>'Update UsedItemsUsage', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UsedItemsUsage', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsedItemsUsage', 'url'=>array('admin')),
);
?>

<h1>View UsedItemsUsage #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'mod_id',
		'item_id',
	),
)); ?>
