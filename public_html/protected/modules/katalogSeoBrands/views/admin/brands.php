<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('katalogSeoBrands', 'Brands')));

$this->pageTitle = Yii::t('katalogSeoBrands', 'Brands');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('admin_layout', 'Settings'),
        'url' => array('/katalogSeoBrands/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('katalogSeoBrands', 'Brands'),
        'url' => array('/katalogSeoBrands/admin/brands'),
        'active' => true,
    ),
);
?>
<h1><?php echo Yii::t('katalogSeoBrands', 'Brands') ?></h1>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'page-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'headerHtmlOptions' => array(
                'width' => 50,
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'brand',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'main',
            'filter' => array('0' => Yii::t('katalogVavto', 'No'), '1' => Yii::t('katalogVavto', 'Yes')),
            'checkedButtonLabel' => Yii::t('katalogVavto', 'Disable'),
            'uncheckedButtonLabel' => Yii::t('katalogVavto', 'Enable'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
    ),
))
?>
