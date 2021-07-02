<?php ?>

<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'items-grid',
    'dataProvider' => $model->getItemsDataProvider(),
    //'filter' => $model,
    'template' => '{items} {pager}',
    'columns' => array(
        array(
            'header' => '№',
            'sortable' => false,
            'value' => '$row+1',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'brand',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('brand', $data->brand, array('class' => 'preprint span2', 'id' => 'brand_' . $data->id));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('name', $data->name, array('class' => 'preprint span2', 'id' => 'name_' . $data->id));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'article',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('article', $data->article, array('class' => 'preprint span2', 'id' => 'article_' . $data->id));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('price', $data->price, array('class' => 'preprint span1', 'id' => 'price_' . $data->id));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'quantum',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('quantum', $data->quantum, array('class' => 'preprint span1', 'id' => 'quantum_' . $data->id));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'delivery',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('delivery', $data->delivery, array('class' => 'preprint span1', 'id' => 'delivery_' . $data->id));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('supplier', $data->supplier, array('class' => 'preprint span2', 'id' => 'supplier_' . $data->id));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier_inn',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('supplier', $data->supplier_inn, array('class' => 'preprint span2', 'id' => 'supplier_inn_' . $data->id));
    }),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'store',
//            'sortable' => false,
//            'type' => 'raw',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//            'value' => function($data) {
//        return CHtml::textField('store', $data->store, array('class' => 'preprint span2', 'id' => 'store_' . $data->id));
//    },
//        ),
    ),
));
?>

