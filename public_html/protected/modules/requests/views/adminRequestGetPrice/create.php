<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array('Запросы на цены' => array('admin'), 'Создание запроса на цену'));

$this->pageTitle = 'Создание запроса на цену';
?>

<h1>Create RequestGetPrice</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>