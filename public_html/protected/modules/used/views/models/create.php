<?php
/* @var $this ModelsController */
/* @var $model UsedModels */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Create Model');
$this->breadcrumbs=array(
	'Used Models'=>array('index'),
	'Create',
);
?>

<h1><?php echo Yii::t(UsedModule::TRANSLATE_PATH, 'Create Model');?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>