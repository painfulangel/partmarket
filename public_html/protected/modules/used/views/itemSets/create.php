<?php
/* @var $this ItemSetsController */
/* @var $model UsedItemSets */

$this->breadcrumbs=array(
	'Used Item Sets'=>array('index'),
	'Create',
);
?>

<h1><?php echo Yii::t(UsedModule::TRANSLATE_PATH, 'Create ItemSets');?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'items'=>$items, 'index'=>$index)); ?>