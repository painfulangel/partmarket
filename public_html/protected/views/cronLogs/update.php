<?php
$this->breadcrumbs=array(
	'Cron Logs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CronLogs','url'=>array('index')),
	array('label'=>'Create CronLogs','url'=>array('create')),
	array('label'=>'View CronLogs','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage CronLogs','url'=>array('admin')),
);
?>

<h1>Update CronLogs <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>