<?php
/* @var $this ItemsController */
/* @var $model UsedItems */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Create Items');
$this->breadcrumbs=array(
	'Used Items'=>array('index'),
	'Create',
);
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php $this->renderPartial('_formAjax', array(
	'model'=>$model,
	'mod'=>$mod,
	'modification'=>$modification,
	'node'=>$node,
	'unit'=>$unit,
)); ?>