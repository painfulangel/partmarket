<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('messages', 'Register of messages') => '/userControl/adminUserMessages/admin', Yii::t('menu', 'Messages')));

$this->pageTitle = Yii::t('menu', 'Messages');

Yii::app()->clientScript->registerCssFile('/libs/treetable/jquery.treeTable.css');
Yii::app()->clientScript->registerScriptFile('/libs/treetable/jquery.treeTable.js');
Yii::app()->clientScript->registerScript('treetable', "
	$('.table').treeTable({
		expandable: true,
		initialState: 'expanded'
	});");
?>
<h1><?php echo Yii::t('menu', 'Messages').' "'.$dialog->theme.'"'; ?></h1>
<?php
if (!$dialog->closed) {
?>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('messages', 'Answer'), array('answer', 'id_dialog' => $dialog->primaryKey), array('class' => 'btn btn-primary')); ?>
    <?php echo CHtml::link(Yii::t('messages', 'Close dialog'), array('close', 'id_dialog' => $dialog->primaryKey), array('class' => 'btn btn-danger')); ?>
</div>
<?php
} else {
?>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('messages', 'Open dialog'), array('open', 'id_dialog' => $dialog->primaryKey), array('class' => 'btn btn-success')); ?>
</div>
<?php
}

$this->widget('TbGridViewTree', array(
    'id' => 'page-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => "js:function() {
        $('.table').treeTable({
        	expandable: true,
        	initialState: 'expanded'
        });
     }",
    'columns' => array(
        array(
            'name' => 'id',
            'headerHtmlOptions' => array(
                'width' => 50,
            ),
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
            'name' => 'date',
            'type' => 'raw',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'language' => 'ru',
                'attribute' => 'date',
                'htmlOptions' => array(
                    'id' => 'date_search'
                ),
                'options' => array(
                    'showAnim' => 'fold',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'showButtonPanel' => 'true',
                    'dateFormat' => 'dd.mm.yy',
                ),
            ), true),
            'value' => 'date(\'d.m.Y H:i:s\',$data->date)',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'message',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'attachment',
            'type' => 'raw',
            'value' => '$data->getImage()',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        /*array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        	'buttons' => array(
        		'view' => array(
        			'url' => 'Yii::app()->createAbsoluteUrl(\'/katalogAccessories/cathegorias/view\', array(\'id\' => $data->id))',
        			'options' => array('target' => '_blank'),
        		),
        	),
            'template' => '{view} {update} {delete}',
        ),*/
    ),
));
?>