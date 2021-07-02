<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'parsers-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => "function() { 
        jQuery('#date_create_search').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true'})); 
    }",
    'columns' => array(
    	array(
    		'class' => 'bootstrap.widgets.TbDataColumn',
    		'name' => 'id',
    		'headerHtmlOptions' => array('style' => 'width: 100px;'),
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
            'name' => 'supplier_inn',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'admin_active_state',
          	'filter' => array('0' => Yii::t('parsersApi', 'No'), '1' => Yii::t('parsersApi', 'Yes')),
            'checkedButtonLabel' => Yii::t('parsersApi', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('parsersApi', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'show_prefix',
          	'filter' => array('0' => Yii::t('parsersApi', 'No'), '1' => Yii::t('parsersApi', 'Yes')),
            'checkedButtonLabel' => Yii::t('parsersApi', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('parsersApi', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
    	),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'prepay',
    		'filter' => array('0' => Yii::t('prices', 'No'), '1' => Yii::t('prices', 'Yes')),
    		'checkedButtonLabel' => Yii::t('prices', 'Deactivate'),
    		'uncheckedButtonLabel' => Yii::t('prices', 'Activate'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
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
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            'buttons' => array(
                'update' => array(
                    'url' => 'array(\'update\', \'id\' => $data->id, \'ParsersApi_page\' => (isset($_GET[\'ParsersApi_page\']) ? $_GET[\'ParsersApi_page\'] : \'\'))',
                ),
            ),
        ),
    ),
));
?>