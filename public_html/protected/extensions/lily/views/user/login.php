<?php
/* @var $this Controller*/
$this->pageTitle = Yii::t('lily', '{appName} - Login', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
$this->breadcrumbs = array(
    Yii::t('lily', 'Login')
);
?><h1><?php echo Yii::t('lily', 'Login');?></h1>
<?php $this->widget('LAuthWidget', array('model' => $model, 'services' => $services, 'showRememberMe' => true, 'submitLabel' => Yii::t('lily', 'Login'), 'action' => '')); ?>
