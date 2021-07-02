<?php
$this->breadcrumbs = array(
	Yii::t('menu', 'Messages'),
);

$this->pageTitle = Yii::t('menu', 'Messages');
?>
<h1><?php echo Yii::t('menu', 'Messages') ?></h1>
<?php
echo CHtml::link(Yii::t('messages', 'Write new message'), array('/userControl/userMessages/new'), array('class' => 'btn btn-success', 'style' => 'margin-bottom:10px;'));

$this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'user-messages-grid',
	'dataProvider' => $model->search(),
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
			'name' => 'theme',
			'htmlOptions' => array('style' => 'text-align: center;'),
			'headerHtmlOptions' => array('style' => 'text-align: center;'),
		),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'date_start',
			'type' => 'raw',
			'value' => 'date(\'d.m.Y H:i:s\',$data->date_start)',
			'headerHtmlOptions' => array('style' => 'text-align: center;'),
			'htmlOptions' => array('style' => 'text-align: center;'),
		),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'date_last_answer',
			'type' => 'raw',
			'value' => '$data->getDateLastAnswer()',
			'headerHtmlOptions' => array('style' => 'text-align: center;'),
			'htmlOptions' => array('style' => 'text-align: center;'),
		),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'new',
			'type' => 'raw',
			'value' => '$data->getNewAnswer()',
			'headerHtmlOptions' => array('style' => 'text-align: center;'),
			'htmlOptions' => array('style' => 'text-align: center;'),
		),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'closed',
			'type' => 'raw',
			'value' => '$data->closed ? "'.Yii::t('messages', 'Yes').'" : "'.Yii::t('messages', 'No').'"',
			'headerHtmlOptions' => array('style' => 'text-align: center;'),
			'htmlOptions' => array('style' => 'text-align: center;'),
		),
		array(
			'class' => 'bootstrap.widgets.TbButtonColumn',
			'template' => '{messages}',
			'buttons' => array(
				'messages' => array(
					'url' => 'Yii::app()->createUrl("/userControl/userMessages/messages", array("id_dialog" => $data["id"]))',
					//'options' => array('target' => '_blank'),
					'label' => Yii::t('menu', 'Messages'),
					'imageUrl' => '/images/admin_icons/icon-list.png',
				),
			),
		),
	),
));