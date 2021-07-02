<?php
/* @var $this ModelsController */
/* @var $model UsedModels */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Update Model').': '.$model->name;
$this->breadcrumbs=array(
	'Used Models'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);
?>

<h1> <?php echo $this->pageTitle; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>