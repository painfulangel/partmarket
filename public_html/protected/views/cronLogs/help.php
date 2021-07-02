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
        'url' => array('/cronLogs/help'),
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
echo CHtml::link(Yii::t('admin_layout', 'Loader Price lists'), '/upload_files/examples/zapscript_price_manager.rar') . '<br/> ';
//echo '<p>' . Yii::t('config', 'Detailed instructions for the platform you can get here:') . ' ' . CHtml::link('wiki.partexpert.ru', 'http://wiki.partexpert.ru') . '</p>';

if (Yii::app()->language == 'en') {
?>
<iframe src="http://wiki.partexpert.ru/index.php?title=%D0%97%D0%B0%D0%B3%D0%BB%D0%B0%D0%B2%D0%BD%D0%B0%D1%8F_%D1%81%D1%82%D1%80%D0%B0%D0%BD%D0%B8%D1%86%D0%B0/en" width="100%" height="500" align="left">
    Your browser does not support floating frames!
 </iframe>
<?php
} else {
?>
<?php
}
?>