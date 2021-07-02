<?php
$this->breadcrumbs = array(
//	'Prices Ftp Autoload Mailboxes'=>array('index'),
    Yii::t('prices', 'Mailboxes'),
);
$this->pageTitle = Yii::t('prices', 'Mailboxes');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('prices-ftp-autoload-mailboxes-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->admin_header = array(
    array(
        'name' => Yii::t('prices', 'Editing warehouses'),
        'url' => array('/prices/adminStores/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('prices', 'Prices'),
        'url' => array('/prices/admin/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('crosses', 'Cross-tables'),
        'url' => array('/crosses/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Suppliers'),
        'url' => array('/parsersApi/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('shop_cart', 'Orders to suppliers'),
        'url' => array('/shop_cart/adminItems/supplierOrder'),
        'active' => false,
    ),
);

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
        'active' => true,
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

<h1><?= Yii::t('prices', 'Mailboxes') ?></h1>

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('prices', 'Create'), array('create'), array('class' => 'btn')) ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'prices-ftp-autoload-mailboxes-grid',
    'dataProvider' => $model->search(),
    //'filter' => $model,
    'columns' => array(
        'id',
        'mailbox',
        //'password',
    	array(
    		'class' => 'bootstrap.widgets.TbDataColumn',
    		'name' => 'password',
            'value' => '"******"',
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        'protocol',
        'pop_adress',
        'pop_port',
        'imap_address',
        'imap_port',
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
            'value' => '$data->getUploadedFiles()',//'$data->download_count!=0?$data->download_count:\'<span style="color:red">0</span>\'',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
//        'delete_old',
        /*
          'just_new',
          'last_update',
         */
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'headerHtmlOptions' => array('style' => 'width: 170px;'),
            'template' => '{deleteAllFiles} {checkNow} {viewSources} {addSource} {update} {delete}',
            'buttons' => array(
                'addSource' => array(
                    'label' => Yii::t('prices', 'Add source'),
                    'url' => 'array(\'addSource\',\'id\'=>$data->id)',
                    'icon' => 'add-sources',
                ),
                'viewSources' => array(
                    'label' => Yii::t('prices', 'View sources'),
                    'url' => 'array(\'viewSources\',\'id\'=>$data->id)',
                    'icon' => 'results',
                ),
                'checkNow' => array(
                    'label' => Yii::t('prices', 'Check mail now'),
                    'url' => 'array(\'checkNow\',\'id\'=>$data->id)',
                    'icon' => 'run',
                ),
                'deleteAllFiles' => array(
                    'label' => Yii::t('prices', 'Delete all downloaded files'),
                    'url' => 'array(\'deleteAllFiles\',\'id\'=>$data->id)',
                    'icon' => 'delete-files',
                    'click' => 'function() {if(!confirm("Are you sure?")) {return false;}}',
                ),
            ),
        ),
    ),
));
?>

<div>
    <p><div style="display:inline-block; width: 16px; height: 16px; background: #126303;"></div> – прайс-лист был загружен  сегодня.</p>
    <p><div style="display:inline-block;width: 16px; height: 16px; background: #54e008;"></div> – прайс-лист был загружен 2-3 дня назад.</p>
    <p><div style="display:inline-block;width: 16px; height: 16px; background: #cfdd04;"></div> – прайс лист был загружен 3-5 дней назад</p>
    <p><div style="display:inline-block;width: 16px; height: 16px; background: #e5b104;"></div> – прайс-лист был загружен 5-7 дней назад.</p>
    <p><div style="display:inline-block;width: 16px; height: 16px; background: #ef3f04;"></div> – прайс-лист был загружен более 7 дней назад.</p>
</div>
