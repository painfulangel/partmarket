<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('prices', 'Search meta-tags') => array('admin'), Yii::t('prices', 'Create meta data')));

$this->pageTitle = Yii::t('prices', 'Create meta data');
?>
<h1><?php echo Yii::t('prices', 'Create meta data'); ?></h1>
<?php
echo $this->renderPartial('_form', array(
    'model' => $model,
	)
);
?>