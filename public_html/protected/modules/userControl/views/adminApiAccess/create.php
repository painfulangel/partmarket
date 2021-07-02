<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('userControl', 'Administrative users access to Api') => array('admin'), Yii::t('userControl', 'Creating a user access to Api')));

$this->pageTitle = Yii::t('userControl', 'Creating a user access to Api');
?>

<h1><?= Yii::t('userControl', 'Creating a user access to Api') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>