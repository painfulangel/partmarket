<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('brands', 'Brands') => array('admin'), Yii::t('brands', 'Edit')));

$this->pageTitle = Yii::t('brands', 'Edit');
?>
<h1><?= Yii::t('brands', 'Edit') ?></h1>
<?php
echo $this->renderPartial('_form', array(
    'model' => $model,));
?>