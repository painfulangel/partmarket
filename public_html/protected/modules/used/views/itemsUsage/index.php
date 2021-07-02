<?php
/* @var $this ItemsUsageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Used Items Usages',
);

$this->menu=array(
	array('label'=>'Create UsedItemsUsage', 'url'=>array('create')),
	array('label'=>'Manage UsedItemsUsage', 'url'=>array('admin')),
);
?>

<h1>Used Items Usages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
