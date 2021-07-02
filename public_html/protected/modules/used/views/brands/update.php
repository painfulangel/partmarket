<?php
/* @var $this BrandsController */
/* @var $model UsedBrands */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Update Brand');
$this->breadcrumbs=array(
	Yii::t(UsedModule::TRANSLATE_PATH, 'Used Brands')=>array('/used/brands/admin'),
	$model->name=>array('view','id'=>$model->id),
	Yii::t(UsedModule::TRANSLATE_PATH, 'Update'),
);
Yii::app()->clientScript->registerMetaTag($model->keywords, 'keywords');
Yii::app()->clientScript->registerMetaTag($model->description, 'description');
?>

<h1>Редактирование марки: <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>