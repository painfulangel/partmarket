<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'orders-grid',
    'dataProvider' => $model->getDataProvider(),
    // 'filter' => $model,
    'template' => '{items}',
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'sortable' => false,
            'type' => 'raw',
            'value' => '$data->id',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'user_id',
            'sortable' => false,
            'type' => 'raw',
            'value' => 'UserProfile::getUserOrderInfo($data->user_id)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'create_date',
            'sortable' => false,
            'type' => 'raw',
            'value' => 'date(\'d.m.Y H:i:s\',$data->create_date)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'sortable' => false,
            'type' => 'raw',
            'name' => 'total_cost',
            'value' => 'Yii::app()->controller->module->getPriceFormatFunction($data->total_cost)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'sortable' => false,
            'type' => 'raw',
            'name' => 'delivery_cost',
            'value' => 'Yii::app()->controller->module->getPriceFormatFunction($data->delivery_cost)',
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
        return $orderStatus->getName($data->status);
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'payed_status',
            'sortable' => false,
            'type' => 'raw',
            'value' => function($data) use($orderStatus) {
        return $orderStatus->getPayedName($data->payed_status);
    },
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
    ),
));
?>