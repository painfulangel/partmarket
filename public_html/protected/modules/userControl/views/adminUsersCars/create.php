<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('userControl', 'Users') => '/userControl/adminUserProfile/admin', Yii::t('userControl', 'Cars user')));

$this->pageTitle = Yii::t('userControl', 'Cars user');
?>
<h1><?php echo Yii::t('userControl', 'Cars user') ?></h1>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>