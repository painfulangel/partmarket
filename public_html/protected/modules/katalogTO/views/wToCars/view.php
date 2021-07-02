<?php
$this->breadcrumbs=array(
	'Wto Cars'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List WToCars','url'=>array('index')),
	array('label'=>'Create WToCars','url'=>array('create')),
	array('label'=>'Update WToCars','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete WToCars','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WToCars','url'=>array('admin')),
);
?>

<h1>View WToCars #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'sort',
		'is_active',
		'content',
		'title',
		'kwords',
		'descr',
		'img',
		'truck',
		'seo_text',
		'slug',
	),
)); ?>
