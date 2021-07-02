<?php
$this->breadcrumbs=array(
	'Wtos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WTo','url'=>array('index')),
	array('label'=>'Manage WTo','url'=>array('admin')),
);
?>

<h1>Create WTo</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>