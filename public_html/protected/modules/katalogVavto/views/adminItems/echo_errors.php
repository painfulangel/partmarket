<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('katalogVavto', 'Editing goods') => array('admin'), Yii::t('katalogVavto', 'Import goods')));

$this->pageTitle = Yii::t('katalogVavto', 'Import goods');
if (empty($text)) {
    $text = Yii::t('katalogVavto', 'Import was without errors');
} else {
    $text = '<b>' . Yii::t('katalogVavto', 'Import was with errors.') . '</b><br>' . $text;
}

$this->admin_subheader = array(
	array(
		'name' => Yii::t('admin_layout', 'Settings'),
		'url' => array('/katalogVavto/adminKatalog/admin'),
		'active' => false,
	),
    array(
        'name' => Yii::t('admin_layout', 'Brands'),
        'url' => array('/katalogVavto/adminBrands/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Model'),
        'url' => array('/katalogVavto/adminCars/admin'),
        'active' => false,
    ),
    array(
        'name' =>Yii::t('admin_layout', 'Goods'),
        'url' => array('/katalogVavto/adminItems/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Export brands'),
        'url' => array('/katalogVavto/adminBrands/export'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Import brands'),
        'url' => array('/katalogVavto/adminBrands/import'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Exporting models'),
        'url' => array('/katalogVavto/adminCars/export'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Import models'),
        'url' =>array('/katalogVavto/adminCars/import'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Exports of goods'),
        'url' => array('/katalogVavto/adminItems/export'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Imports of goods'),
        'url' => array('/katalogVavto/adminItems/import'),
        'active' => true,
    ),
);
?>

<h1><?= Yii::t('katalogVavto', 'Import goods') ?></h1>

<?php
echo $text?>