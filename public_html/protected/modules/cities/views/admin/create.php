<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('cities', 'Cities') => array('admin'), Yii::t('cities', 'Create')));

$this->pageTitle = Yii::t('cities', 'Create');
?>
<h1><?php echo Yii::t('cities', 'Create'); ?></h1>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>