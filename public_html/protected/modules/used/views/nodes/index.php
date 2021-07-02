<?php
/* @var $this NodesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Used Nodes',
);

$this->menu=array(
	array('label'=>'Create UsedNodes', 'url'=>array('create')),
	array('label'=>'Manage UsedNodes', 'url'=>array('admin')),
);
?>

<h1>Used Nodes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
