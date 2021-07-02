<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('pricegroups', 'Price groups') => array('admin'), Yii::t('pricegroups', 'Editing price range')));

$this->pageTitle = Yii::t('pricegroups', 'Editing price range');
?>

<h1><?= Yii::t('pricegroups', 'Editing price range') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>