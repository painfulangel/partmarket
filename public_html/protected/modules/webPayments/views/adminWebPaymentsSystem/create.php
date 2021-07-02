<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('webPayments', 'Payment systems') => array('admin'), Yii::t('webPayments', 'The creation of a system of payment')));

$this->pageTitle = Yii::t('webPayments', 'The creation of a system of payment');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('admin_layout', 'Payment system'),
        'url' => array('/webPayments/adminWebPaymentsSystem/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Made Electronic Payments'),
        'url' => array('/webPayments/adminWebPayments/admin'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('webPayments', 'The creation of a system of payment') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>