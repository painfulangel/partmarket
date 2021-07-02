<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('delivery', 'Delivery') => array('index'), Yii::t('delivery', 'Create delivery method')));

$this->pageTitle = Yii::t('delivery', 'Create delivery method');
?>

<h1><?= Yii::t('delivery', 'Create delivery method') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>