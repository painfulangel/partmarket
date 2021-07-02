<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('requests', 'VIN Request') => array('admin'), Yii::t('requests', 'Edit the VIN Request')));

$this->pageTitle = Yii::t('requests', 'Edit the VIN Request');
$this->admin_header = array(
    array(
        'name' => Yii::t('requests', 'VIN Request'),
        'url' => array('/requests/adminRequestVin/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('requests', 'Request for prices'),
        'url' => array('/requests/adminRequestGetPrice/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('requests', 'Requests for replacement parts PU'),
        'url' => array('/requests/adminRequestWu/admin'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('requests', 'Edit the VIN Request') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>