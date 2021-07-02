<?php
/* @var $this Controller*/
$this->pageTitle = Yii::t('lily', '{appName} - Bind account', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
$this->breadcrumbs = array(
    Yii::t('lily', 'Accounts')=>$this->createUrl('account/list'),
    Yii::t('lily', 'Bind')
);
?><h1><?php echo Yii::t('lily', 'Bind new account'); ?></h1>
<p><?php echo Yii::t('lily', 'Please fill out the following form with your login credentials:'); ?></p>
<?php $this->widget('LAuthWidget', array('model' => $model, 'services' => $services, 'showRememberMe' => false, 'submitLabel' => Yii::t('lily', 'Bind'), 'action' => '')); ?>
