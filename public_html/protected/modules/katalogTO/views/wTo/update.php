<?php
$this->breadcrumbs=array(
	'Wtos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WTo','url'=>array('index')),
	array('label'=>'Create WTo','url'=>array('create')),
	array('label'=>'View WTo','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage WTo','url'=>array('admin')),
);
?>

<h1>Update WTo <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>