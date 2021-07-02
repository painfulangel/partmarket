<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('brands', 'Brands') => array('admin'), Yii::t('brands', 'Create')));

$this->pageTitle = Yii::t('brands', 'Create');
?>
<h1><?php echo Yii::t('brands', 'Create'); ?></h1>
<?php
echo $this->renderPartial('_form', array(
    'model' => $model,
	)
);
?>