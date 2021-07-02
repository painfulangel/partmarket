<?php
/* @var $this ItemSetsController */
/* @var $model UsedItemSets */

$this->breadcrumbs=array(
	'Used Item Sets'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List UsedItemSets', 'url'=>array('index')),
	array('label'=>'Create UsedItemSets', 'url'=>array('create')),
	array('label'=>'Update UsedItemSets', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UsedItemSets', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsedItemSets', 'url'=>array('admin')),
);
?>

<h1>View UsedItemSets #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'item_id',
		'name',
		'vendor_code',
		'original_num',
		'replacement',
		'type',
		'state',
		'comment',
		'price',
		'delivery_time',
		'availability',
		'created_at',
		'updated_at',
	),
)); ?>
