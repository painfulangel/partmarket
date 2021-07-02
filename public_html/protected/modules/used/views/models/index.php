<?php
/* @var $this ModelsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Used Models',
);

$this->menu=array(
	array('label'=>'Create UsedModels', 'url'=>array('create')),
	array('label'=>'Manage UsedModels', 'url'=>array('admin')),
);
?>

<h1>Used Models</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
