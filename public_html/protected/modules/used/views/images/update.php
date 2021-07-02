<?php
/* @var $this ImagesController */
/* @var $model UsedImages */

$this->breadcrumbs=array(
	'Used Images'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UsedImages', 'url'=>array('index')),
	array('label'=>'Create UsedImages', 'url'=>array('create')),
	array('label'=>'View UsedImages', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UsedImages', 'url'=>array('admin')),
);
?>

<h1>Update UsedImages <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>