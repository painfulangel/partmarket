<?php
/* @var $this ItemsController */
/* @var $model UsedItems */

$this->breadcrumbs=array(
	'Used Items'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1>Update UsedItems <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>