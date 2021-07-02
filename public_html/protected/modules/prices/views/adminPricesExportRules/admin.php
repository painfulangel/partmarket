
<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('prices', 'Prices') => array('admin/admin'), Yii::t('prices', 'Auto upload price lists')));

$this->pageTitle = Yii::t('prices', 'Auto upload price lists');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('prices-export-rules-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->admin_subheader = array(
  
    array(
        'name' => Yii::t('prices', 'Prices'),
        'url' => array('/prices/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Auto Price list'),
        'url' => array('/prices/adminAutoloadRules/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Mailboxes'),
        'url' => array('/prices/adminMailboxes/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Export price lists'),
        'url' => array('/prices/adminPricesExportRules/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Load price list'),
        'url' => array('/prices/admin/create'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('prices', 'Auto upload price lists') ?></h1>

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('prices', 'Add rule'), array('create'), array('class' => 'btn')) ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'prices-export-rules-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        array(
            'class' => 'bootstrap.widgets.TbDataCo'
            . 'lumn',
            'name' => 'rule_name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'ftp_server',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'ftp_destination_folder',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'download_time',
            'value' => '$data->download_time!=0?date(\'d.m.Y H:i\',$data->download_time):Yii::t(\'prices\', \'Не запускалось\')',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'download_count',
            'type' => 'raw',
            'value' => '$data->download_count!=0?$data->download_count:\'<span style="color:red">0</span>\'',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'active_state',
            'filter' => array('0' => Yii::t('prices', 'No'), '1' => Yii::t('prices', 'Yes')),
            'checkedButtonLabel' => Yii::t('prices', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('prices', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}  {start} {delete}',
            'buttons' => array(
                'view' => array(
                    'url' => 'array(\'admin/ruleAdmin\',\'id\'=>$data->id)',
                ),
                'start' => array(
                    'label' => Yii::t('prices', 'Run rule'),
                    'url' => 'array(\'start\',\'id\'=>$data->id)',
                    'icon' => 'run',
                    'options' => array('class' => 'admin_order_buttons', 'target' => '_blank'),
//                        'click' => 'function(){ShopCartSaveItem($(this).attr("href"));return false;}',
//                        'visible' => '$data->isFormEnabled()!=\'\'',
                ),
            ),
        ),
    ),
));
?>
