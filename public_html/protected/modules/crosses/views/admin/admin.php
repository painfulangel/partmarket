<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('crosses', 'Cross-tables')));

$this->pageTitle = Yii::t('crosses', 'Cross-tables');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('crosses-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
$this->admin_header = $top_menu;
?>
<h1><?php echo Yii::t('crosses', 'Cross-tables') ?></h1>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('crosses', 'Create New Cross Base'), array('newCrossBase'), array('class' => 'btn')); ?>
</div>
<?php if (Yii::app()->user->hasFlash('info')) { ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('info'); ?>
    </div>
<?php } ?>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'crosses-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => "function() { 
        jQuery('#date_create_search').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true'})); 
    }",
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
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
            'type' => 'raw',
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
                    'dateFormat' => 'dd.mm.yy'
                )
            ), true),
            'value' => 'date("d.m.Y","{$data->create_date}")',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'active_state',
    		'filter' => array('0' => Yii::t('crosses', 'No'), '1' => Yii::t('crosses', 'Yes')),
    		'checkedButtonLabel' => Yii::t('crosses', 'Deactivate'),
    		'uncheckedButtonLabel' => Yii::t('crosses', 'Activate'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
		array(
			'class' => 'ext.jtogglecolumn.JToggleColumn',
			'name' => 'garanty',
			'filter' => array('0' => Yii::t('crosses', 'No'), '1' => Yii::t('crosses', 'Yes')),
			'checkedButtonLabel' => Yii::t('crosses', 'Deactivate'),
			'uncheckedButtonLabel' => Yii::t('crosses', 'Activate'),
			'headerHtmlOptions' => array('style' => 'text-align: center;'),
			'htmlOptions' => array('style' => 'text-align: center;'),
		),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'look_for_coincidence',
    		'filter' => array('0' => Yii::t('crosses', 'No'), '1' => Yii::t('crosses', 'Yes')),
    		'checkedButtonLabel' => Yii::t('crosses', "Don't look for coincidences"),
    		'uncheckedButtonLabel' => Yii::t('crosses', "Look for coincidences"),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
    	array(
    		'class' => 'bootstrap.widgets.TbDataColumn',
    		'name' => 'files_upload',
    		'value' => '$data->filesUpload()',
    		'htmlOptions' => array('style' => 'text-align: center;'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{exportBase} {showTable} {update} {delete}',
            'buttons' => array(
            	'exportBase' => array(
            		'label' => Yii::t('crosses', 'Export of the table of cross'),
            		'url' => 'array("/crosses/admin/exportBase/","id" => $data->id)',
            		'imageUrl' => '/images/icons/csv.png',
            		'options' => array('class' => 'admin_exporttable', 'target' => '_blank'),
            	),
                'showTable' => array(
                    'label' => Yii::t('crosses', 'View of the table of cross base'),
                    'url' => 'array("/crosses/admin/crossBase/","base_id" => $data->id)',
                    'imageUrl' => '/images/icons/table.png',
                    'options' => array('class' => 'admin_showtable'),
                ),
                'update' => array(
                    'url' => 'array(\'updateCrossBase\', \'id\' => $data->id, \'Crosses_page\' => (isset($_GET[\'Crosses_page\']) ? $_GET[\'Crosses_page\'] : \'\'))',
                ),
            	'delete' => array(
            		'url' => 'array(\'deleteCrossBase\', \'id\' => $data->id, \'Crosses_page\' => (isset($_GET[\'Crosses_page\']) ? $_GET[\'Crosses_page\'] : \'\'))',
            	),
            ),
            'htmlOptions' => array('style' => 'width: 90px;'),
        ),
    ),
));
?>