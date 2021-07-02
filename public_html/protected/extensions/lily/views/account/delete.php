<?php
/* @var $this Controller */
$this->pageTitle = Yii::t('lily', '{appName} - Delete account', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
$this->breadcrumbs = array(
    Yii::t('lily', 'Accounts') => $this->createUrl('account/list'),
    Yii::t('lily', 'Delete')
);
?><h1><?php echo Yii::t('lily', 'Delete account'); ?></h1>
<div>
    <form action="" method="POST">
        <p class="note"><?php echo Yii::t('lily', 'Do you really want to delete your account {displayId} (service {serviceName})?', array('{displayId}' => $account->displayId, '{serviceName}' => $account->serviceName)); ?></p>
        <div class="row buttons">
            <?php echo CHtml::submitButton(Yii::t('lily', 'Yes'), array('name' => 'accept')); ?>
            <?php echo CHtml::link(Yii::t('lily', 'Cancel'), $this->createUrl('account/list')); ?>
        </div>
    </form>
</div>