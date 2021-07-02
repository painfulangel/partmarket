<?php
/* @var $this BrandsItemsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Used Brands Items',
);

$this->menu=array(
	array('label'=>'Create UsedBrandsItems', 'url'=>array('create')),
	array('label'=>'Manage UsedBrandsItems', 'url'=>array('admin')),
);
?>

<h1>Used Brands Items</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
