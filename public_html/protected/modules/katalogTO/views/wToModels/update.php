<?php
$this->breadcrumbs=array(
	'Wto Models'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WToModels','url'=>array('index')),
	array('label'=>'Create WToModels','url'=>array('create')),
	array('label'=>'View WToModels','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage WToModels','url'=>array('admin')),
);
?>

<h1>Update WToModels <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>