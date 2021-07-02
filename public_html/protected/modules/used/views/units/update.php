<?php
/* @var $this UnitsController */
/* @var $model UsedUnits */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Update Units').': '.$model->name;
$this->breadcrumbs=array(
	'Used Units'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>