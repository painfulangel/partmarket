<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('katalogAccessories', 'Catalogue (products)') => array('admin'), Yii::t('katalogAccessories', 'Edit product')));

$this->pageTitle = Yii::t('katalogAccessories', 'Edit product');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('admin_layout', 'Categories'),
        'url' => array('/katalogAccessories/adminCathegorias/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Goods'),
        'url' => array('/katalogAccessories/adminItems/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Export categories'),
        'url' => array('/katalogAccessories/adminCathegorias/export'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Import categories'),
        'url' => array('/katalogAccessories/adminCathegorias/import'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Exports of goods'),
        'url' => array('/katalogAccessories/adminItems/export'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Imports of goods'),
        'url' => array('/katalogAccessories/adminItems/import'),
        'active' => false,
    ),
);
?>

    <h1><?= Yii::t('katalogAccessories', 'Edit product') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>