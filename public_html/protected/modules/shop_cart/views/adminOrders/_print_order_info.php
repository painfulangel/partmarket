<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'orders-grid',
    'dataProvider' => $model->getDataProvider(),
    'template' => '{items}',
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'sortable' => false,
            'type' => 'raw',
            'value' => function($data) {
        return CHtml::textField('id', $data->id, array('class' => 'preprint span2'));
    },
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'user_id',
            'sortable' => false,
            'type' => 'raw',
            'value' => function($data) {
        return CHtml::textField('user_id', UserProfile::getUserOrderInfo($data->user_id), array('class' => 'preprint span2'));
    },
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'create_date',
            'sortable' => false,
            'type' => 'raw',
            'value' => function($data) {
        return CHtml::textField('create_date', date('d.m.Y H:i:s', $data->create_date), array('class' => 'preprint span2'));
    },
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'sortable' => false,
            'type' => 'raw',
            'name' => 'total_cost',
            'value' => function($data) {
        return CHtml::textField('total_cost', Yii::app()->controller->module->getPriceFormatFunction($data->total_cost), array('class' => 'preprint span2'));
    },
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'status',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) use($orderStatus) {
        return CHtml::textField('status', $orderStatus->getName($data->status), array('class' => 'preprint span2'));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'payed_status',
            'sortable' => false,
            'type' => 'raw',
            'value' => function($data) use($orderStatus) {
        return CHtml::textField('payed_status', $orderStatus->getPayedName($data->payed_status), array('class' => 'preprint span2'));
    },
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
    ),
));
?>