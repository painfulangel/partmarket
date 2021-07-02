<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('currencies', 'currency') => array('admin'), Yii::t('currencies', 'Creation of new currency')));

$this->pageTitle = Yii::t('currencies', 'Creation of new currency');
?>

<h1><?= Yii::t('currencies', 'Creation of new currency') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>