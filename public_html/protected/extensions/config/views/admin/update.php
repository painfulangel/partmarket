<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('config', 'Site configuration') => array('admin'), Yii::t('config', 'Changing values ') . $model->label));

$this->pageTitle = Yii::t('config', 'Changing values ') . $model->label;
?>

<h1> <?php echo Yii::t('config', 'Changing values ') . $model->label; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>