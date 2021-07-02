<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('userControl', 'Administrative users access to Api')));

$this->pageTitle = Yii::t('userControl', 'Administrative users access to Api');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('users-api-access-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php echo $this->renderPartial('../adminApiAccess/_admin', array('model' => $model->getUserApiModel)); ?>
