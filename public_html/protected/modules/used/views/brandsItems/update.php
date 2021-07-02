<?php
/* @var $this BrandsItemsController */
/* @var $model UsedBrandsItems */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Update Brands Items').': '.$model->name;
$this->breadcrumbs=array(
	Yii::t(UsedModule::TRANSLATE_PATH, 'Brands Items') => array('admin'),
	'Редактирование',
);
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>