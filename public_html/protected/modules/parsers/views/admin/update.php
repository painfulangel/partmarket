<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('parsers', 'Parsers/API price') => array('admin'), Yii::t('parsers', 'Edit parser')));

$this->pageTitle = Yii::t('parsers', 'Edit parser');

$this->admin_header = array(
    array(
        'name' => Yii::t('prices', 'Editing warehouses'),
        'url' => array('/prices/adminStores/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('prices', 'Prices'),
        'url' => array('/prices/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('crosses', 'Cross-tables'),
        'url' => array('/crosses/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Suppliers'),
        'url' => array('/parsersApi/admin/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('shop_cart', 'Orders to suppliers'),
        'url' => array('/shop_cart/adminItems/supplierOrder'),
        'active' => false,
    ),
);
$this->admin_subheader = array(
   
    array(
        'name' => Yii::t('parsersApi', 'Parsers price'),
        'url' => array('/parsers/admin/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('parsersApi', 'Parsers/API price'),
        'url' => array('/parsersApi/admin/admin'),
        'active' => false,
    ),
    
);
?>

<h1><?= Yii::t('parsers', 'Edit parser') ?></h1>

<?php
echo $this->renderPartial('_form', array(
    'model' => $model,
    'priceGroupsList' => $priceGroupsList,
    'currencies' => $currencies,
));
?>