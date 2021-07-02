<?php
$this->breadcrumbs=array(
	'Wtos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WTo','url'=>array('index')),
	array('label'=>'Create WTo','url'=>array('create')),
	array('label'=>'Update WTo','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete WTo','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WTo','url'=>array('admin')),
);
?>

<h1>View WTo #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type_id',
		'descr',
		'box',
		'comment',
		'article',
		'search',
		'brand_id',
		'seo_title',
		'seo_kwords',
		'seo_descr',
		'slug',
	),
)); ?>
