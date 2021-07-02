<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('crosses', 'Cross-tables') => array('admin'), Yii::t('crosses', 'Download of cross base')));

$this->pageTitle = Yii::t('crosses', 'Download of cross base');
$this->admin_header = $top_menu;
?>
<h1><?= Yii::t('crosses', 'Download of cross base') ?></h1>
<?php echo $this->renderPartial('_form_cross_table', array('model' => $model)); ?>