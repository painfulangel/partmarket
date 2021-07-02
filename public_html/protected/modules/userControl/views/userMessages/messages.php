<?php
$this->breadcrumbs = array(Yii::t('menu', 'Messages') => '/userControl/userMessages/index', $dialog->theme);

$this->pageTitle = $dialog->theme;

Yii::app()->clientScript->registerCssFile('/libs/treetable/jquery.treeTable.css');
Yii::app()->clientScript->registerScriptFile('/libs/treetable/jquery.treeTable.js');
Yii::app()->clientScript->registerScript('treetable', "
	$('.table').treeTable({
		expandable: true,
		initialState: 'expanded'
	});");
?>
<h1><?php echo $dialog->theme; ?></h1>
<?php
if (!$dialog->closed) {
?>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('messages', 'Answer'), array('answer', 'id_dialog' => $dialog->primaryKey), array('class' => 'btn btn-primary')); ?>
</div>
<?php
}

$this->widget('TbGridViewTree', array(
    'id' => 'page-grid',
    'dataProvider' => $model->search(),
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
            'value' => '$data->getUserName()',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'date',
            'type' => 'raw',
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