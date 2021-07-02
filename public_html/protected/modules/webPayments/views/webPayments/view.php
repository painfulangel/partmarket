<?php
$this->pageTitle = Yii::t('webPayments', 'Payment details');
$this->breadcrumbs = array(
    Yii::t('webPayments', 'Payment details'),
);
?>
<h1><?= Yii::t('webPayments', 'Payment details') ?> №<?= $model->id ?></h1>
<div><?= empty($model->finish_date) ? Yii::t('webPayments', 'The payment is processed. Refresh the page after a while.') : Yii::t('webPayments', 'Your payment has been successfully credited.') ?></div>