<?php
/* @var $this UnitsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Used Units',
);

$this->menu=array(
	array('label'=>'Create UsedUnits', 'url'=>array('create')),
	array('label'=>'Manage UsedUnits', 'url'=>array('admin')),
);
?>

<h1>Used Units</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
