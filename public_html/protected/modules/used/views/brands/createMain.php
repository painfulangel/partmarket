<?php
/* @var $this BrandsController */
/* @var $model UsedBrands */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Create Brand');
$this->breadcrumbs=array(
	'Бренды'=>array('index'),
	'Create',
);
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php $this->renderPartial('_formMain', array('model'=>$model)); ?>