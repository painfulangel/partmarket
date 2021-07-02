<?php
	$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('universal', 'Universal catalog') => array('admin'), Yii::t('universal', 'Update universal catalog section')));
	
	$this->pageTitle = Yii::t('universal', 'Update universal catalog section');
?>

<h1><?php echo Yii::t('universal', 'Update universal catalog section') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>