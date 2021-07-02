<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('katalogVavto', 'Editing brands') => array('admin'), Yii::t('katalogVavto', 'Create brand')));

$this->pageTitle = Yii::t('katalogVavto', 'Create brand');

$this->admin_subheader = array(
	array(
		'name' => Yii::t('admin_layout', 'Settings'),
		'url' => array('/katalogVavto/adminKatalog/admin'),
		'active' => false,
	),
    array(
        'name' => Yii::t('admin_layout', 'Brands'),
        'url' => array('/katalogVavto/adminBrands/admin'),
        'active' => true,
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
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('katalogVavto', 'Create brand') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>