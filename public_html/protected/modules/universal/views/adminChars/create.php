<?php
	$title = Yii::t('universal', 'Universal catalog chars').'"'.$razdel->name.'"';
	
	$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('universal', 'Universal catalog') => array('/universal/admin/admin'), 
													 $title => array('admin', 'id' => $razdel->primaryKey), 
													 Yii::t('universal', 'Create universal catalog chars')));

	$this->pageTitle = Yii::t('universal', 'Create universal catalog chars');
?>

<h1><?php echo Yii::t('universal', 'Create universal catalog chars'); ?></h1>
<?php $model->id_razdel = $razdel->primaryKey; ?>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>