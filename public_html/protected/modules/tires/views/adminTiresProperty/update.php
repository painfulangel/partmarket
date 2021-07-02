<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('tires', 'Tires property') => array('admin'), Yii::t('tires', 'Editing tires property value')));

$this->pageTitle = Yii::t('tires', 'Editing tires property value');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('tires', 'Tires catalog'),
        'url' => array('/tires/adminTires/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('tires', 'Tires property'),
        'url' => array('/tires/adminTiresProperty/admin'),
        'active' => true,
    ),
);
?>
<h1><?php echo Yii::t('tires', 'Editing tires property value').' "'.$model->property->name.'"'; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>