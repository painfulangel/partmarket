<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('prices', 'Prices')));

$this->pageTitle = Yii::t('prices', 'Prices');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('prices-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->admin_subheader = array(
    array(
        'name' => Yii::t('prices', 'Prices'),
        'url' => array('/prices/admin/admin'),
        'active' => true,
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
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Load price list'),
        'url' => array('/prices/admin/create'),
        'active' => false,
    ),
);
?>

<h1><?= Yii::t('prices', 'Prices') ?></h1>
<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('prices', 'Load price list'), array('create'), array('class' => 'btn')) ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'prices-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => "function() { 
        jQuery('#date_create_search').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true'})); 
    }",
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'htmlOptions' => array('style' => 'text-align: center;width:70px;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'value' => '$data->name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'store_name',
            'value' => '$data->store_name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'rule_name',
            'value' => '$data->rule_name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'count_position',
            'value' => '$data->count_position',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'create_date',
            'type' => 'raw',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'language' => 'ru',
                'attribute' => 'create_date',
                'htmlOptions' => array(
                    'id' => 'date_create_search'
                ),
                'options' => array(
                    'showAnim' => 'fold',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'showButtonPanel' => 'true',
                    'dateFormat' => 'dd.mm.yy',
                ),
                    ), true),
            'value' => 'date("d.m.Y H:i:s","{$data->create_date}")',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'header' => Yii::t('prices', 'Status'),
            'value' => '($data->getQueue())?Yii::t("prices","Process"):Yii::t("prices","Complete")',
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
            'template' => '{exportTable} {showTable} {update} {delete}',
            'buttons' => array(
                'update' => array(
                    'url' => 'array(\'update\', \'id\' => $data->id, \'Prices_page\' => (isset($_GET[\'Prices_page\']) ? $_GET[\'Prices_page\'] : \'\'))',
                ),
                'showTable' => array(
                    'label' => Yii::t('prices', 'Viewing of a price'),
                    'url' => 'array("priceTable","id" => $data->id)',
                    'icon' => 'table',
//                    'options' => array('class' => 'admin_showtable'),
                ),
                'exportTable' => array(
                    'label' => Yii::t('prices', 'Export of a price'),
                    'url' => 'array("exportTable","id" => $data->id)',
                    'icon' => 'csv',
                    'options' => array('class' => '', 'target' => '_blank'),
                ),
            ),
            'htmlOptions' => array('style' => 'width: 90px;'),
        ),
    ),
));
?>
