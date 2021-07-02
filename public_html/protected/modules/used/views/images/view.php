<?php
/* @var $this ImagesController */
/* @var $model UsedImages */

$this->breadcrumbs=array(
	'Used Images'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UsedImages', 'url'=>array('index')),
	array('label'=>'Create UsedImages', 'url'=>array('create')),
	array('label'=>'Update UsedImages', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UsedImages', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsedImages', 'url'=>array('admin')),
);
?>

<h1>View UsedImages #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'item_id',
		'image',
	),
)); ?>
