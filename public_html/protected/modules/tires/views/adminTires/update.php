<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('tires', 'Tires catalog') => array('admin'), Yii::t('tires', 'Editing tires')));

$this->pageTitle = Yii::t('tires', 'Editing tires');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('tires', 'Tires catalog'),
        'url' => array('/tires/adminTires/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('tires', 'Tires property'),
        'url' => array('/tires/adminTiresProperty/admin'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('tires', 'Editing tires') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>