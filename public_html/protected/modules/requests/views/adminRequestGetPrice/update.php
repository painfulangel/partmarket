<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('requests', 'Request for prices') => array('admin'), Yii::t('requests', 'Edit request price')));

$this->pageTitle = Yii::t('requests', 'Edit request price');

$this->admin_header = array(
    array(
        'name' => Yii::t('requests', 'VIN Request'),
        'url' => array('/requests/adminRequestVin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('requests', 'Request for prices'),
        'url' => array('/requests/adminRequestGetPrice/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('requests', 'Requests for replacement parts PU'),
        'url' => array('/requests/adminRequestWu/admin'),
        'active' => false,
    ),
   
);
?>

<h1><?= Yii::t('requests', 'Edit request price') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>