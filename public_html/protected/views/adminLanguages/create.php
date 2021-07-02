<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(
	Yii::t('languages', 'Setting translations') => array('admin'),
	Yii::t('languages', 'Creating a new translation'),
));

$this->pageTitle = Yii::t('languages', 'Setting translations');

$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Site settings'),
        'url' => array('/config/admin/adminTotal'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('config', 'Help'),
        'url' => array('/cronLogs/help'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Logs'),
        'url' => array('/cronLogs/admin'),
        'active' => FALSE,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Translation settings'),
        'url' => array('/adminLanguages/admin/'),
        'active' => true,
    ),
);
?>

<h1><?= Yii::t('languages', 'Creating a new translation') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>