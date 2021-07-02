<?php
/* @var $this ModificationController */
/* @var $model UsedMod */

$this->breadcrumbs=array(
	'Used Mods'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List UsedMod', 'url'=>array('index')),
	array('label'=>'Create UsedMod', 'url'=>array('create')),
	array('label'=>'Update UsedMod', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UsedMod', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsedMod', 'url'=>array('admin')),
);
?>

<h1>View UsedMod #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'brand_id',
		'model_id',
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
