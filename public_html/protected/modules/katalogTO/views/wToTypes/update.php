<?php
$this->breadcrumbs=array(
	'Wto Types'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WToTypes','url'=>array('index')),
	array('label'=>'Create WToTypes','url'=>array('create')),
	array('label'=>'View WToTypes','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage WToTypes','url'=>array('admin')),
);
?>

<h1>Update WToTypes <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>