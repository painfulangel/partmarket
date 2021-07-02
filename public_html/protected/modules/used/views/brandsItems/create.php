<?php
/* @var $this BrandsItemsController */
/* @var $model UsedBrandsItems */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Create Brands Items');
$this->breadcrumbs=array(
	'Used Brands Items'=>array('index'),
	'Create',
);
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>