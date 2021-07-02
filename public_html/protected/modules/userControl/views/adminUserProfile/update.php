<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('userControl', 'Personal data')));

$this->pageTitle = Yii::t('userControl', 'Personal data');
?>

<h1><?= Yii::t('userControl', 'Personal data') ?></h1>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>