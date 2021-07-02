<?php
	$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('crosses', 'Cross-tables') => array('admin'), Yii::t('crosses', 'Edit of cross')));

	$this->pageTitle = Yii::t('crosses', 'Edit of cross');
	$this->admin_header = $top_menu;
?>
<h1><?= Yii::t('crosses', 'Edit of cross') ?></h1>
<?php echo $this->renderPartial('_form', array('model' => $model, 'base_id' => $model->table_id)); ?>