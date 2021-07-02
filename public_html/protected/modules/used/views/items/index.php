<?php
/* @var $this ItemsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Used Items',
);

$this->menu=array(
	array('label'=>'Create UsedItems', 'url'=>array('create')),
	array('label'=>'Manage UsedItems', 'url'=>array('admin')),
);
?>

<h1>Used Items</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
