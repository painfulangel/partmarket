<?php
/* @var $this UnitsController */
/* @var $model UsedUnits */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Create Units');
$this->breadcrumbs=array(
	'Used Units'=>array('index'),
	'Create',
);

?>

<h1><?php echo $this->pageTitle;?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>