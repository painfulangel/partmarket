<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('userControl', 'Balance user')));

$this->pageTitle = Yii::t('userControl', 'Balance user');
?>

<h1><?= Yii::t('userControl', 'Balance user') ?></h1>

<?php echo $this->renderPartial('../adminUserBalance/_form', array('model' => UserBalanceOperations::getNewModel($model))); ?>

<?php echo $this->renderPartial('../adminApiAccess/_admin', array('model' => $model->getUserApiModel())); ?>
<h1><?= Yii::t('userControl', 'Personal data') ?></h1>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>