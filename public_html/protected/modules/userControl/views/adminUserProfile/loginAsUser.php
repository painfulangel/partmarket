<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('userControl', 'Log in as a user')));

$this->pageTitle = Yii::t('userControl', 'Log in as a user');
?>
<h1><?= Yii::t('userControl', 'Log in as a user') ?></h1>

<div>
    <?= Yii::t('userControl', 'You have successfully logged on user name') ?>  <?= $model->getFullName() ?>

    <br/>
    <?= Yii::t('userControl', 'This mechanism allows you to place orders on behalf of clients. Principle of operation') ?> :
    <ol>
        <li>	<?= Yii::t('userControl', 'Go to page site') ?> – <a href="/"><?php echo Yii::t('userControl', 'HOME'); ?></a><?php //echo CHtml::link(Yii::t('userControl', 'HOME'), array('/site/create'); ?> </li>
        <li>	<?= Yii::t('userControl', 'Find the position and order.') ?></li>
        <li>	<?= Yii::t('userControl', 'To exit this mode, click') ?>:<img src="/images/logout.png"> </li>
    </ol>
</div>