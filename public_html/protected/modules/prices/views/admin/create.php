<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('prices', 'Prices') => array('admin'), Yii::t('prices', 'Loading of prices')));

$this->pageTitle = Yii::t('prices', 'Loading of prices');

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
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Load price list'),
        'url' => array('/prices/admin/create'),
        'active' => true,
    ),
);
?>

<h1><?= Yii::t('prices', 'Loading of prices') ?></h1>

<?php
echo $this->renderPartial('_form', array(
    'model' => $model,
    'priceGroupsList' => $priceGroupsList,
    'stores' => $stores,
    'currencies' => $currencies,
        )
);
?>