<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array('БУ Запросы' => array('admin'), 'Создание запроса на запчасти БУ'));

$this->pageTitle = 'Создание запроса на запчасти БУ';
?>

<h1>Создание запроса на запчасти БУ</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>