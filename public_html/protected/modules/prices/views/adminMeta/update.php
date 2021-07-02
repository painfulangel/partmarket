<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('prices', 'Search meta-tags') => array('admin'), Yii::t('prices', 'Edit meta data')));

$this->pageTitle = Yii::t('prices', 'Edit meta data');
?>

<h1><?= Yii::t('prices', 'Edit meta data') ?></h1>
<?php
echo $this->renderPartial('_form', array(
    'model' => $model,));
?>