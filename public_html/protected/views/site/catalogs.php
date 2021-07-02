<?php
/* @var $this SiteController */
$this->breadcrumbs = array(
//	'Cron Logs'=>array('index'),
    Yii::t('config', 'Help'),
);
$this->pageTitle = Yii::t('config', 'Help');
$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'ACCESSORIES CATALOGUE'),
        'url' => array('/katalogAccessories/adminCathegorias/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Own catalog, SEO'),
        'url' => array('/katalogVavto/adminBrands/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('tires', 'Tires catalog'),
        'url' => array('/tires/adminTires/admin'),
        'active' => false,
    ),
);
?>
<h1><?= Yii::t('config', 'Help') ?></h1>