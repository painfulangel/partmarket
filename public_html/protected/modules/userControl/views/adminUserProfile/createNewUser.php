<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('userControl', 'Creation of the new user')));

$this->pageTitle = Yii::t('userControl', 'Creation of the new user');
?>

<h1><?php echo Yii::t('userControl', 'Creation of the new user') ?></h1>
<?php echo $this->renderPartial('_new_user', array('model_profile' => $model)); ?>