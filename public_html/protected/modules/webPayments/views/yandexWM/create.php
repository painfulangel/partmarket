<?php
$this->breadcrumbs = array(
    Yii::t('webPayments', 'Depositing'),
);

$this->pageTitle = Yii::t('webPayments', 'Depositing');
?>

<h1><?= Yii::t('webPayments', 'Depositing') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>