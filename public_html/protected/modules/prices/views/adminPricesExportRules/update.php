<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('prices', 'Prices') => array('admin/admin'), Yii::t('prices', 'Auto upload price lists') => array('admin'), Yii::t('prices', 'The edit rule upload price')));

$this->pageTitle = Yii::t('prices', 'The edit rule upload price');

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

<h1><?= Yii::t('prices', 'The edit rule upload price') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>