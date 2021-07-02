<?php
/* @var $this ImagesController */
/* @var $model UsedImages */

$this->breadcrumbs=array(
	'Used Images'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UsedImages', 'url'=>array('index')),
	array('label'=>'Manage UsedImages', 'url'=>array('admin')),
);
?>

<h1>Create UsedImages</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>