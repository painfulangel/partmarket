<?php
/* @var $this ModelsController */
/* @var $model UsedModels */

$this->breadcrumbs=array(
	'Used Models'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List UsedModels', 'url'=>array('index')),
	array('label'=>'Create UsedModels', 'url'=>array('create')),
	array('label'=>'Update UsedModels', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UsedModels', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsedModels', 'url'=>array('admin')),
);
?>

<h1>View UsedModels #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'brand_id',
		'name',
		'slug',
		'title',
		'keywords',
		'description',
		'text',
		'image',
		'sort',
	),
)); ?>
