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
        return CHtml::textField('brand', $data->brand, array('class' => 'span1', 'id' => 'brand_'.$data->id, 'disabled'.$data->isFormEnabled() => 'on'));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textArea('name', $data->name, array('class' => 'span2', 'id' => 'name_'.$data->id, 'disabled'.$data->isFormEnabled() => 'on', 'style' => 'min-height: 100px;'));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'article',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('article', $data->article, array('class' => 'span2', 'id' => 'article_'.$data->id, 'disabled'.$data->isFormEnabled() => 'on'));
    }),
    array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price_purchase',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('price_purchase', $data->price_purchase, array('class' => 'span1', 'id' => 'price_purchase_'.$data->id, 'disabled'.$data->isFormEnabled() => 'on'));
    }),
    array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('price', $data->price, array('class' => 'span1', 'id' => 'price_'.$data->id, 'disabled'.$data->isFormEnabled() => 'on'));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'quantum',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('quantum', $data->quantum, array('class' => 'span1', 'id' => 'quantum_'.$data->id, 'disabled'.$data->isFormEnabled() => 'on'));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'delivery',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('delivery', $data->delivery, array('class' => 'span1', 'id' => 'delivery_'.$data->id, 'disabled'.$data->isFormEnabled() => 'on'));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('supplier', $data->supplier, array('class' => 'span1', 'id' => 'supplier_'.$data->id, 'disabled'.$data->isFormEnabled() => 'on'));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier_inn',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('supplier', $data->supplier_inn, array('class' => 'span1', 'id' => 'supplier_inn_'.$data->id, 'disabled'.$data->isFormEnabled() => 'on'));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'status',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) use($itemStatus) {
        return CHtml::dropDownList('status', $data->status, $itemStatus->getList(), array('class' => 'span1', 'id' => 'status_'.$data->id, 'disabled'.$data->isFormEnabled() => 'on'));
    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'payed_status',
            'sortable' => false,
            'type' => 'raw',
            'value' => function($data) use($itemStatus) {
        return $itemStatus->getPayedName($data->payed_status);
    },
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'store',
//            'sortable' => false,
//            'type' => 'raw',
//            'value' => '$data->store',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{save} {delete}',
            'buttons' => array(
                'save' => array(
                    'label' => Yii::t('shop_cart', 'Save') ,
                    'url' => '$data->id',
                    'imageUrl' => '/images/icons/save.png',
                    'options' => array('class' => 'admin_order_buttons', 'target' => '_blank'),
                    'click' => 'function(){ ShopCartSaveItem($(this).attr("href"), 0); return false; }',
                    'visible' => '$data->isFormEnabled()!=\'\'',
                ),
                'delete' => array(
                    'url' => 'array(\'adminItems/delete\',\'id\'=>$data->id)',
                    'visible' => '$data->isFormEnabled()!=\'\'',
                ),
            ),
        ),
    ),
));
?>