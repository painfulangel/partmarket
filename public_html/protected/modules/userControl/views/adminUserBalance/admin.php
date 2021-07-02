<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('userControl', 'List of user operations')));

$this->pageTitle = Yii::t('userControl', 'List of user operations');


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-balance-operations-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Clients'),
        'url' => array('/userControl/adminUserProfile/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Create Client'),
        'url' => array('/userControl/adminUserProfile/createNewUser'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Rights to users'),
        'url' => array('/auth/assignment/index'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('userControl', 'List of user operations') ?></h1>



<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'user-balance-operations-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'template' => '{items} {pager}',
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'create_time',
            'type' => 'raw',
            'value' => 'date(\'d.m.Y H:i:s\',$data->create_time)',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'value',
            'type' => 'raw',
            'value' => 'Yii::app()->getModule(\'currencies\')->getFormatPrice($data->value)',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'balance',
            'type' => 'raw',
            'value' => 'Yii::app()->getModule(\'currencies\')->getFormatPrice($data->balance)',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'comment',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
    ),
));
?>
<h1><?= Yii::t('userControl', 'Balance user') ?></h1>
<?php echo $this->renderPartial('_form', array('model' => UserBalanceOperations::getNewModel($model))); ?>