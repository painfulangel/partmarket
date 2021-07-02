<?php
$this->breadcrumbs=array(
	'Wto Models',
);

$this->menu=array(
	array('label'=>'Create WToModels','url'=>array('create')),
	array('label'=>'Manage WToModels','url'=>array('admin')),
);
?>

<h1>Wto Models</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
