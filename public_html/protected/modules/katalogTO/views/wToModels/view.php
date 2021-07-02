<?php
$this->breadcrumbs=array(
	'Wto Models'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List WToModels','url'=>array('index')),
	array('label'=>'Create WToModels','url'=>array('create')),
	array('label'=>'Update WToModels','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete WToModels','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WToModels','url'=>array('admin')),
);
?>

<h1>View WToModels #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'car_id',
		'name',
		'sort',
		'is_active',
		'content',
		'title',
		'kwords',
		'descr',
		'img',
		'seo_text',
		'slug',
	),
)); ?>
