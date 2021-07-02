<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('shop_cart', 'Goods') => array('admin'), Yii::t('shop_cart', 'Editing items')));

$this->pageTitle = Yii::t('shop_cart', 'Editing items');
?>

<h1><?= Yii::t('shop_cart', 'Editing items') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>