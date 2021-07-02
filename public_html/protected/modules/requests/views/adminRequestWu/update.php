<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('requests', 'PU Requests') => array('admin'), Yii::t('requests', 'Edit Requests for replacement parts PU')));

$this->pageTitle = Yii::t('requests', 'Edit Requests for replacement parts PU');

$this->admin_header = array(
    array(
        'name' => Yii::t('requests', 'VIN Request'),
        'url' => array('/requests/adminRequestVin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('requests', 'Request for prices'),
        'url' => array('/requests/adminRequestGetPrice/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('requests', 'Requests for replacement parts PU'),
        'url' => array('/requests/adminRequestWu/admin'),
        'active' => true,
    ),
);
?>

<h1><?= Yii::t('requests', 'Edit Requests for replacement parts PU') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>