<?php
$this->breadcrumbs=array(
	'Wto Cars',
);

$this->menu=array(
	array('label'=>'Create WToCars','url'=>array('create')),
	array('label'=>'Manage WToCars','url'=>array('admin')),
);
?>

<h1>Wto Cars</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
