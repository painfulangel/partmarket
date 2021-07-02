<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array( Yii::t('languages', 'Setting translations')));

$this->pageTitle = Yii::t('languages', 'Setting translations');

$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Site settings'),
        'url' => array('/config/admin/adminTotal'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('config', 'Help'),
        'url' => array('/cronLogs/help'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Logs'),
        'url' => array('/cronLogs/admin'),
        'active' => FALSE,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Translation settings'),
        'url' => array('/adminLanguages/admin/'),
        'active' => true,
    ),
);
?>
<h1><?= Yii::t('languages', 'Setting translations') ?></h1>

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('languages', 'Create'), array('create'), array('class' => 'btn')) ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'languages-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'name',
        'short_name',
        'link_name',
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'active',
    		'filter' => array('0' => Yii::t('languages', 'No'), '1' => Yii::t('languages', 'Yes')),
    		'checkedButtonLabel' => Yii::t('languages', 'Disable'),
    		'uncheckedButtonLabel' => Yii::t('languages', 'Enable'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete} {download}',
            'buttons' => array(
                'download' => array(
                    'label' => Yii::t('languages', 'Download files'),
                    'url' => 'array("download","link_name" => $data->link_name)',
                    'icon' => 'waybill',
                    'options' => array('class' => '', 'target' => '_blank'),
                ),
            ),
        ),
    ),
));
?>
