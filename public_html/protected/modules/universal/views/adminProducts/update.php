<?php
	$title = Yii::t('universal', 'Universal catalog products').'"'.$razdel->name.'"';
	
	$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('universal', 'Universal catalog') => array('/universal/admin/admin'), 
													 $title => array('admin', 'id' => $razdel->primaryKey), 
													 Yii::t('universal', 'Update universal catalog product')));

	$this->pageTitle = Yii::t('universal', 'Update universal catalog product');
?>

<h1><?php echo Yii::t('universal', 'Update universal catalog product'); ?></h1>
<?php $model->id_razdel = $razdel->primaryKey; ?>
<?php echo $this->renderPartial('_form', array('model' => $model, 'chars' => $chars)); ?>