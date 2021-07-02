<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('currencies', 'currency') => array('admin'), Yii::t('currencies', 'Editing currency')));

$this->pageTitle = Yii::t('currencies', 'Editing currency');
?>

<h1><?= Yii::t('currencies', 'Editing currency') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>