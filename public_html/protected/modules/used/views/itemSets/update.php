<?php
/* @var $this ItemSetsController */
/* @var $model UsedItemSets */
$this->pageTitle = 'Редактировать комплект - '.$model->name;
$this->breadcrumbs=array(
	'Used Item Sets'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UsedItemSets', 'url'=>array('index')),
	array('label'=>'Create UsedItemSets', 'url'=>array('create')),
	array('label'=>'View UsedItemSets', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UsedItemSets', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php $this->renderPartial('_formUpdate', array('model'=>$model)); ?>