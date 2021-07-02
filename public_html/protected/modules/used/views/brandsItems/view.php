<?php
/* @var $this BrandsItemsController */
/* @var $model UsedBrandsItems */

$this->breadcrumbs=array(
	'Used Brands Items'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List UsedBrandsItems', 'url'=>array('index')),
	array('label'=>'Create UsedBrandsItems', 'url'=>array('create')),
	array('label'=>'Update UsedBrandsItems', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UsedBrandsItems', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsedBrandsItems', 'url'=>array('admin')),
);
?>

<h1>View UsedBrandsItems #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'sort',
	),
)); ?>
