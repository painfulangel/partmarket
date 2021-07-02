<?php
/* @var $this ItemSetsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Used Item Sets',
);

$this->menu=array(
	array('label'=>'Create UsedItemSets', 'url'=>array('create')),
	array('label'=>'Manage UsedItemSets', 'url'=>array('admin')),
);
?>

<h1>Used Item Sets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
