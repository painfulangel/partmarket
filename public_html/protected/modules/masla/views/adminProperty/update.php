<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('masla', 'Oil property') => array('admin'), Yii::t('masla', 'Editing oil property value')));

$this->pageTitle = Yii::t('masla', 'Editing oil property value');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('masla', 'Oil catalog'),
        'url' => array('/masla/admin/'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('masla', 'Oil property'),
        'url' => array('/masla/adminProperty/'),
        'active' => true,
    ),
);
?>
<h1><?php echo Yii::t('masla', 'Editing oil property value').' "'.$model->property->name.'"'; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model, 'property' => $property)); ?>