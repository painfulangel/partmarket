<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('delivery', 'Delivery') => array('index'), Yii::t('delivery', 'Update delivery method')));

$this->pageTitle = Yii::t('delivery', 'Update delivery method');
?>
<h1><?php echo Yii::t('delivery', 'Update delivery method'); ?></h1>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>