<?php
$this->breadcrumbs = array(
//	'Prices Ftp Autoload Mailboxes'=>array('index'),
    Yii::t('prices', 'Mailbox sources'),
);
$this->pageTitle = Yii::t('prices', 'Mailbox sources');

$this->admin_header = array(
    array(
        'name' => Yii::t('prices', 'Editing warehouses'),
        'url' => array('/prices/adminStores/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('prices', 'Prices'),
        'url' => array('/prices/admin/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('crosses', 'Cross-tables'),
        'url' => array('/crosses/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Suppliers'),
        'url' => array('/parsersApi/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('shop_cart', 'Orders to suppliers'),
        'url' => array('/shop_cart/adminItems/supplierOrder'),
        'active' => false,
    ),
);

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
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Export price lists'),
        'url' => array('/prices/adminPricesExportRules/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Load price list'),
        'url' => array('/prices/admin/create'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('prices', 'Loaded Files') ?></h1>


<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'prices-ftp-autoload-mailboxes-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        array(
            'header'=>'Имя файла',
            'name'=>'name',
            'value'=>'CHtml::link($data["name"], $data["path"])',
            'type'=>'raw'
        ),
        array(
            'header'=>'Дата загрузки',
            'name'=>'date',
        ),
    ),
));
?>