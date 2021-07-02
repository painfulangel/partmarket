<?php
/* @var $this SetsImagesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Used Sets Images',
);

$this->menu=array(
	array('label'=>'Create UsedSetsImages', 'url'=>array('create')),
	array('label'=>'Manage UsedSetsImages', 'url'=>array('admin')),
);
?>

<h1>Used Sets Images</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
