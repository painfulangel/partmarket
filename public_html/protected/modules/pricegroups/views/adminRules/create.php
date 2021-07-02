<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('pricegroups', 'Rules') => array('admin'), Yii::t('pricegroups', 'Create a new rule')));

$this->pageTitle = Yii::t('pricegroups', 'Create a new rule');
$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Price politics'),
        'url' => array('/pricegroups/adminGroups/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Payment system'),
        'url' => array('/webPayments/adminWebPaymentsSystem/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Currency'),
        'url' => array('/currencies/admin/admin'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('pricegroups', 'Create a new rule') ?></h1>

<?php
echo $this->renderPartial('_form', array(
    'model' => $model,
    'priceGroupsList' => $priceGroupsList,));
?>