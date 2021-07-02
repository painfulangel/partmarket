<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('userControl', 'New user created')));

$this->pageTitle = Yii::t('userControl', 'New user created');
?>

<h1><?= Yii::t('userControl', 'New user created') ?></h1>
<b><?= Yii::t('userControl', 'Username') ?>:</b> <?= $model->email ?><br/>
<b><?= Yii::t('userControl', 'Password') ?>:</b> <?= $password ?><br/>


<?= CHtml::link(Yii::t('userControl', 'Log in as that user'), array('loginAsUser', 'id' => $model->uid), array('class' => 'btn btn-primary')) ?>
<?=
CHtml::link(Yii::t('userControl', 'Create new customer'), array('createNewUser'), array('class' => 'btn btn-primary'))?>