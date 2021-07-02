<?php
$this->breadcrumbs=array(
	'Cron Logs',
);

$this->menu=array(
	array('label'=>'Create CronLogs','url'=>array('create')),
	array('label'=>'Manage CronLogs','url'=>array('admin')),
);
?>

<h1>Cron Logs</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
