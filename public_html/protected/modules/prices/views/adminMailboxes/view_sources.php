<?php
$this->breadcrumbs = array(
//	'Prices Ftp Autoload Mailboxes'=>array('index'),
    Yii::t('prices', 'Mailbox sources'),
);
$this->pageTitle = Yii::t('prices', 'Mailbox sources');

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

<h1><?= Yii::t('prices', 'Mailbox sources') ?></h1>

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('prices', 'Create'), array('addSource', 'id'=>$model->id), array('class' => 'btn')) ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'prices-ftp-autoload-mailboxes-grid',
    'dataProvider' => $dataProvider,
    //'filter' => $model,
    'columns' => array(
        'id',
        'rule_name',
        'mail_from',
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
            'value' => '$data->download_count',//'$data->download_count!=0?$data->download_count:\'<span style="color:red">0</span>\'',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'header'=>Yii::t('prices', 'Count of loaded files'),
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'mail_file',
            'type' => 'raw',
            'value' => '$data->getUploadedFiles()',//'$data->download_count!=0?$data->download_count:\'<span style="color:red">0</span>\'',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        //'pop_port',
//        'delete_old',
        /*
          'just_new',
          'last_update',
         */
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{updateSource} {deleteSource}',
            'buttons' => array(
                'updateSource' => array(
                    'label' => Yii::t('prices', 'Update source'),
                    'url' => 'array(\'updateSource\',\'id\'=>$data->id)',
                    'icon' => 'pencil',
                ),
                'deleteSource' => array(
                    'label' => Yii::t('prices', 'Delete source'),
                    'url' => 'array(\'deleteSource\',\'id\'=>$data->id)',
                    'icon' => 'trash',
                ),
            ),
        ),
    ),
));
?>