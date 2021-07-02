<?php
$this->breadcrumbs=array(
	'Cron Logs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CronLogs','url'=>array('index')),
	array('label'=>'Manage CronLogs','url'=>array('admin')),
);
?>

<h1>Create CronLogs</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>