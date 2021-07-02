<?php
$this->breadcrumbs=array(
	'Wto Cars'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WToCars','url'=>array('index')),
	array('label'=>'Create WToCars','url'=>array('create')),
	array('label'=>'View WToCars','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage WToCars','url'=>array('admin')),
);
?>

<h1>Update WToCars <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>