<?php
/* @var $this SetsImagesController */
/* @var $model UsedSetsImages */

$this->breadcrumbs=array(
	'Used Sets Images'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UsedSetsImages', 'url'=>array('index')),
	array('label'=>'Create UsedSetsImages', 'url'=>array('create')),
	array('label'=>'Update UsedSetsImages', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UsedSetsImages', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsedSetsImages', 'url'=>array('admin')),
);
?>

<h1>View UsedSetsImages #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'set_id',
		'image',
	),
)); ?>
