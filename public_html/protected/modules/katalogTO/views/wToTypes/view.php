<?php
$this->breadcrumbs=array(
	'Wto Types'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List WToTypes','url'=>array('index')),
	array('label'=>'Create WToTypes','url'=>array('create')),
	array('label'=>'Update WToTypes','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete WToTypes','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WToTypes','url'=>array('admin')),
);
?>

<h1>View WToTypes #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'model_id',
		'name',
		'sort',
		'is_active',
		'content',
		'title',
		'kwords',
		'descr',
		'img',
		'mod',
		'engine',
		'engine_model',
		'engine_obj',
		'engine_horse',
		'type_year',
		'seo_text',
		'tecdoc_url',
		'tecdoc_id',
		'slug',
	),
)); ?>
