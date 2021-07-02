<?php
/* @var $this ModificationController */
/* @var $model UsedMod */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Update Mod').': '.$model->name;
$this->breadcrumbs=array(
	'Used Mods'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>