<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('webPayments', 'Electronic payments')));

$this->pageTitle = Yii::t('webPayments', 'Electronic payments');


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('web-payments-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->admin_subheader = array(
    array(
        'name' => Yii::t('admin_layout', 'Payment system'),
        'url' => array('/webPayments/adminWebPaymentsSystem/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Made Electronic Payments'),
        'url' => array('/webPayments/adminWebPayments/admin'),
        'active' => true,
    ),
);
?>

<h1><?= Yii::t('webPayments', 'Electronic payments') ?></h1>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'web-payments-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'model_id',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'method',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'value',
            'type' => 'raw',
            'value' => 'Yii::app()->getModule(\'currencies\')->getDefaultPrice($data->value)',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'start_date',
            'type' => 'raw',
            'value' => 'date(\'Y.m.d H:i:s\',$data->start_date)',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'finish_date',
            'type' => 'raw',
            'value' => '!empty($data->finish_date)?date(\'Y.m.d H:i:s\',$data->finish_date):\'Не оплачен\'',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'user_id',
            'type' => 'raw',
            'value' => 'CHtml::link(UserProfile::getUserOrderInfo($data->user_id),Yii::app()->createUrl(\'/userControl/adminUserProfile/view\',array(\'id\'=>$data->user_id)),array(\'target\'=>\'_blank\'))',
            //'filter' => UserProfile::model()->getSelectList(),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
    ),
));
?>
