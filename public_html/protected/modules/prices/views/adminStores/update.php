<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('prices', 'Storages') => array('admin'), Yii::t('prices', 'Edit the storage')));

$this->pageTitle = Yii::t('prices', 'Edit the storage');
?>
<h1><?= Yii::t('prices', 'Edit the storage') ?></h1>
<?php
echo $this->renderPartial('_form', array(
    'model' => $model,
    'priceGroupsList' => $priceGroupsList,
    'currencies' => $currencies,
));
?>