<?php
/* @var $this ImagesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Used Images',
);

$this->menu=array(
	array('label'=>'Create UsedImages', 'url'=>array('create')),
	array('label'=>'Manage UsedImages', 'url'=>array('admin')),
);
?>

<h1>Used Images</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
