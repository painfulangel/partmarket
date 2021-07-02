<?php
$this->breadcrumbs=array(
	'Wto Models'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WToModels','url'=>array('index')),
	array('label'=>'Manage WToModels','url'=>array('admin')),
);
?>

<h1>Create WToModels</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>