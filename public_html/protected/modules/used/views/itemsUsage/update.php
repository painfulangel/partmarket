<?php
/* @var $this ItemsUsageController */
/* @var $model UsedItemsUsage */

$this->breadcrumbs=array(
	'Used Items Usages'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UsedItemsUsage', 'url'=>array('index')),
	array('label'=>'Create UsedItemsUsage', 'url'=>array('create')),
	array('label'=>'View UsedItemsUsage', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UsedItemsUsage', 'url'=>array('admin')),
);
?>

<h1>Update UsedItemsUsage <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>