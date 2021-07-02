<?php
/* @var $this BrandsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Used Brands',
);

$this->menu=array(
	array('label'=>'Create UsedBrands', 'url'=>array('create')),
	array('label'=>'Manage UsedBrands', 'url'=>array('admin')),
);
?>

<h1>Used Brands</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
