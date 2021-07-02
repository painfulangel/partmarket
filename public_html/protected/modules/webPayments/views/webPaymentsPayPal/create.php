<?php
$this->breadcrumbs = array(
    'Пополнение счета',
);

$this->pageTitle = 'Пополнение счета';
?>

<h1>Пополнение счета</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>