<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('webPayments', 'Payment systems')));

$this->pageTitle = Yii::t('webPayments', 'Payment systems');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});

$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('web-payments-system-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->admin_subheader = array(
    array(
        'name' => Yii::t('admin_layout', 'Payment system'),
        'url' => array('/webPayments/adminWebPaymentsSystem/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Made Electronic Payments'),
        'url' => array('/webPayments/adminWebPayments/admin'),
        'active' => false,
    ),
);
?>
<h1><?php echo Yii::t('webPayments', 'Payment systems'); ?></h1>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'web-payments-system-grid',
    'dataProvider' => $model->search(),
    // 'filter' => $model,
    'columns' => array(
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'id',
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//            'htmlOptions' => array('style' => 'text-align: center;'),
//        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'description',
        	'type' => 'raw',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'commission',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'active_state',
            'filter' => array('0' => Yii::t('webPayments', 'No'), '1' => Yii::t('webPayments', 'Yes')),
            'checkedButtonLabel' => Yii::t('webPayments', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('webPayments', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'show_balance',
    		'filter' => array('0' => Yii::t('webPayments', 'No'), '1' => Yii::t('webPayments', 'Yes')),
    		'checkedButtonLabel' => Yii::t('webPayments', 'Deactivate'),
    		'uncheckedButtonLabel' => Yii::t('webPayments', 'Activate'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'show_prepay',
            'filter' => array('0' => Yii::t('webPayments', 'No'), '1' => Yii::t('webPayments', 'Yes')),
            'checkedButtonLabel' => Yii::t('webPayments', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('webPayments', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('class' => 'toggle'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'show_order',
            'filter' => array('0' => Yii::t('webPayments', 'No'), '1' => Yii::t('webPayments', 'Yes')),
            'checkedButtonLabel' => Yii::t('webPayments', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('webPayments', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('class' => 'toggle'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}',
        ),
    ),
));
?>
