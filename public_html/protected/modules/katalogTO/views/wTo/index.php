<?php
$this->breadcrumbs=array(
	'Wtos',
);

$this->menu=array(
	array('label'=>'Create WTo','url'=>array('create')),
	array('label'=>'Manage WTo','url'=>array('admin')),
);
?>

<h1>Wtos</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
