<?php
/* @var $this BrandsController */
/* @var $model UsedBrands */

$this->breadcrumbs=array(
	'Used Brands'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List UsedBrands', 'url'=>array('index')),
	array('label'=>'Create UsedBrands', 'url'=>array('create')),
	array('label'=>'Update UsedBrands', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UsedBrands', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsedBrands', 'url'=>array('admin')),
);
?>

<h1>View UsedBrands #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'slug',
		'title',
		'keywords',
		'description',
		'text',
		'image',
		'sort',
		'status',
	),
)); ?>
