<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('messages', 'Register of messages')));

$this->pageTitle = Yii::t('messages', 'Register of messages');

/*Yii::app()->clientScript->registerScript('search', "
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
");*/
?>
<h1><?php echo Yii::t('messages', 'Register of messages') ?></h1>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'user-messages-grid',
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
            'name' => 'email',
            'type' => 'raw',
            'value' => '$data->getEmail()',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'user_id',
            'type' => 'raw',
        	'filter' => '',
            'value' => '$data->getUserName()',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'theme',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'date_start',
            'type' => 'raw',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'language' => 'ru',
                'attribute' => 'date_start',
                'htmlOptions' => array(
                    'id' => 'date_start_search'
                ),
                'options' => array(
                    'showAnim' => 'fold',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'showButtonPanel' => 'true',
                    'dateFormat' => 'dd.mm.yy',
                ),
            ), true),
            'value' => 'date(\'d.m.Y H:i:s\',$data->date_start)',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'date_last_answer',
            'type' => 'raw',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'language' => 'ru',
                'attribute' => 'date_last_answer',
                'htmlOptions' => array(
                    'id' => 'date_last_answer_search'
                ),
                'options' => array(
                    'showAnim' => 'fold',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'showButtonPanel' => 'true',
                    'dateFormat' => 'dd.mm.yy',
                ),
            ), true),
            'value' => '$data->getDateLastAnswer()',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'closed',
    		'filter' => array('0' => Yii::t('messages', 'No'), '1' => Yii::t('messages', 'Yes')),
    		'checkedButtonLabel' => Yii::t('messages', 'Enable'),
    		'uncheckedButtonLabel' => Yii::t('messages', 'Disable'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{messages}',
        	'buttons' => array(
        		'messages' => array(
        			'url' => 'Yii::app()->createUrl("/userControl/adminUserMessages/messages", array("id_dialog" => $data["id"]))',	
        			//'options' => array('target' => '_blank'),
        			'label' => Yii::t('menu', 'Messages'),
        			'imageUrl' => '/images/admin_icons/icon-list.png',
        		),
        	),
        ),
    ),
));
?>