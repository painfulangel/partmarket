<?php
/* @var $this ModificationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Used Mods',
);

$this->menu=array(
	array('label'=>'Create UsedMod', 'url'=>array('create')),
	array('label'=>'Manage UsedMod', 'url'=>array('admin')),
);
?>

<h1>Used Mods</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
