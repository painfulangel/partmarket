<?php
/* @var $this SetsImagesController */
/* @var $model UsedSetsImages */

$this->breadcrumbs=array(
	'Used Sets Images'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UsedSetsImages', 'url'=>array('index')),
	array('label'=>'Create UsedSetsImages', 'url'=>array('create')),
	array('label'=>'View UsedSetsImages', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UsedSetsImages', 'url'=>array('admin')),
);
?>

<h1>Update UsedSetsImages <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>