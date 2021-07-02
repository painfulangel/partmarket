<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('crosses', 'Cross-tables') => array('admin'), Yii::t('crosses', 'Edit of cross table')));

$this->pageTitle = Yii::t('crosses', 'Edit of cross table');
$this->admin_header = $top_menu;
?>
<h1><?= Yii::t('crosses', 'Edit of cross table') ?></h1>
<?php echo $this->renderPartial('_form_cross_table', array('model' => $model)); ?>