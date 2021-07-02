<?php
/* @var $this NodesController */
/* @var $model UsedNodes */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Update Nodes').': '.$model->name;
$this->breadcrumbs=array(
	'Used Nodes'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>