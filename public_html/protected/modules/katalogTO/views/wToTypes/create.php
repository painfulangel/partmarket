<?php
$this->breadcrumbs=array(
	'Wto Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WToTypes','url'=>array('index')),
	array('label'=>'Manage WToTypes','url'=>array('admin')),
);
?>

<h1>Create WToTypes</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>