<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('prices', 'Prices') => array('admin/admin'), Yii::t('prices', 'Auto upload price lists') => array('admin'), Yii::t('prices', 'Run rule auto unloading')));

$this->pageTitle = Yii::t('prices', 'Run rule auto unloading');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('prices', 'Prices'),
        'url' => array('/prices/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Auto Price list'),
        'url' => array('/prices/adminAutoloadRules/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Mailboxes'),
        'url' => array('/prices/adminMailboxes/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Export price lists'),
        'url' => array('/prices/adminPricesExportRules/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Load price list'),
        'url' => array('/prices/admin/create'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('prices', 'Run rule auto unloading') ?></h1>

<p><?= Yii::t('prices', 'Run rule auto unloading') ?><?= $model->id . (!empty($model->rule_name) ? ' ( ' . Yii::t('prices', 'with a name') . ' ' . $model->rule_name . ')' : '') . ' ' . Yii::t('prices', 'It is started.') ?></p>