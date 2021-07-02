<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('cities', 'Cities') => array('admin'), Yii::t('cities', 'Edit')));

$this->pageTitle = Yii::t('cities', 'Edit');
?>
<h1><?php echo Yii::t('cities', 'Edit'); ?></h1>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>