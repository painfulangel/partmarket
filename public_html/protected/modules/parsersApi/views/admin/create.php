<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('parsersApi', 'Parsers/API price') => array('admin'), Yii::t('parsersApi', 'Create parser')));

$this->pageTitle = Yii::t('parsersApi', 'Create parser');

$this->admin_subheader = array(
   
    array(
        'name' => Yii::t('parsersApi', 'Parsers price'),
        'url' => array('/parsers/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('parsersApi', 'Parsers/API price'),
        'url' => array('/parsersApi/admin/admin'),
        'active' => true,
    ),
    
);
?>
<h1><?= Yii::t('parsersApi', 'Create parser') ?></h1>
<?php
echo $this->renderPartial('_form', array(
    'model' => $model,
    'priceGroupsList' => $priceGroupsList,
    'currencies' => $currencies,
));
?>