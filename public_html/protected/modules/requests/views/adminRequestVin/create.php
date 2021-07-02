<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array('VIN Запросы' => array('admin'), 'Создание VIN Запроса'));

$this->pageTitle = 'Создание VIN Запроса';
?>

<h1>Создание VIN Запроса</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>