<?php
$this->breadcrumbs=array(
	'Wto Cars'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WToCars','url'=>array('index')),
	array('label'=>'Manage WToCars','url'=>array('admin')),
);
?>

<h1>Create WToCars</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>