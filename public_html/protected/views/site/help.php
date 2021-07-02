<?php
/* @var $this SiteController */
$this->breadcrumbs = array(
//	'Cron Logs'=>array('index'),
    Yii::t('config', 'Help'),
);
$this->pageTitle = Yii::t('config', 'Help');
$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Site settings'),
        'url' => array('/config/admin/adminTotal'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('config', 'Help'),
        'url' => array('/site/help'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Logs'),
        'url' => array('/cronLogs/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Translation settings'),
        'url' => array('/adminLanguages/admin/'),
        'active' => false,
    ),
);
?>
<h1><?= Yii::t('config', 'Help') ?></h1>

<?php
echo CHtml::link(Yii::t('admin_layout', 'Loader Price lists'), '/upload_files/examples/partexpert_price_manager.rar') . '<br/> ';
?>