<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('tires', 'Tires property') => array('admin'), Yii::t('tires', 'Creating tires property value')));

$this->pageTitle = Yii::t('tires', 'Creating tires property value');

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
<h1><?php echo Yii::t('tires', 'Creating tires property value'); ?></h1>
<?php
	echo $this->renderPartial('_form', array('model' => $model));
?>