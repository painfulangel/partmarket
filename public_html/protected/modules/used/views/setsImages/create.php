<?php
/* @var $this SetsImagesController */
/* @var $model UsedSetsImages */

$this->breadcrumbs=array(
	'Used Sets Images'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UsedSetsImages', 'url'=>array('index')),
	array('label'=>'Manage UsedSetsImages', 'url'=>array('admin')),
);
?>

<h1>Create UsedSetsImages</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>