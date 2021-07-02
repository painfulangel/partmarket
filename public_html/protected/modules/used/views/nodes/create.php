<?php
/* @var $this NodesController */
/* @var $model UsedNodes */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Create Nodes');
$this->breadcrumbs=array(
	'Used Nodes'=>array('index'),
	'Create',
);
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>