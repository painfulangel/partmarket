<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('delivery', 'Transport companies') => array('index'), Yii::t('delivery', 'Update transport company')));

$this->pageTitle = Yii::t('delivery', 'Update transport company');
?>
<h1><?php echo Yii::t('delivery', 'Update transport company'); ?></h1>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>