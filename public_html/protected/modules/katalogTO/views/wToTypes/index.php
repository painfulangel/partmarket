<?php
$this->breadcrumbs=array(
	'Wto Types',
);

$this->menu=array(
	array('label'=>'Create WToTypes','url'=>array('create')),
	array('label'=>'Manage WToTypes','url'=>array('admin')),
);
?>

<h1>Wto Types</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
