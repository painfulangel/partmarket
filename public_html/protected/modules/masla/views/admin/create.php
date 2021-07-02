<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('masla', 'Oil catalog') => array('admin'), Yii::t('masla', 'Create')));

$this->pageTitle = Yii::t('masla', 'Create');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('masla', 'Oil catalog'),
        'url' => array('/masla/admin/'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('masla', 'Oil property'),
        'url' => array('/masla/adminProperty/'),
        'active' => false,
    ),
);
?>
<h1><?php echo Yii::t('masla', 'Create'); ?></h1>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>