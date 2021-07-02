<?php
/* @var $this ModificationController */
/* @var $model UsedMod */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Create Modification');
$this->breadcrumbs=array(
	'Used Mods'=>array('index'),
	'Create',
);
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>