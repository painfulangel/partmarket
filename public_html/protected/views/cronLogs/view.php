<?php
$this->breadcrumbs=array(
	'Логи'=>array('admin'),
	$model->id,
);


?>

<h1>Логи</h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'text',
		'task',
//		'create_time',
	),
)); ?>
