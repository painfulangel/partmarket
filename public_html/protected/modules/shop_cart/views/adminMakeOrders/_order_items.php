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
//            'value' => function($data) {
//        return CHtml::textField('brand', $data->brand, array('class' => 'span2', 'id' => 'brand_' . $data->id, 'disabled' . $data->isFormEnabled() => 'on'));
//    }
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//            'value' => function($data) {
//        return CHtml::textField('name', $data->name, array('class' => 'span2', 'id' => 'name_' . $data->id, 'disabled' . $data->isFormEnabled() => 'on'));
//    }
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'article',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//            'value' => function($data) {
//        return CHtml::textField('article', $data->article, array('class' => 'span2', 'id' => 'article_' . $data->id, 'disabled' . $data->isFormEnabled() => 'on'));
//    }
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => 'Yii::app()->controller->module->getPriceFormatFunction($data->price)',
//            'value' => function($data) {
//        return CHtml::textField('price', $data->price, array('class' => 'span1', 'id' => 'price_' . $data->id, 'disabled' . $data->isFormEnabled() => 'on'));
//    }
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'quantum',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) {
        return CHtml::textField('quantum', $data->quantum, array('style' => 'width: 40px;', 'id' => 'quantum_' . $data->id, 'disabled' . $data->isFormEnabled() => 'on'));
    }
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'delivery',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//            'value' => function($data) {
//        return CHtml::textField('delivery', $data->delivery, array('class' => 'span1', 'id' => 'delivery_' . $data->id, 'disabled' . $data->isFormEnabled() => 'on'));
//    }
        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'supplier',
//            'sortable' => false,
//            'type' => 'raw',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
////            'value' => function($data) {
////        return CHtml::textField('supplier', $data->supplier, array('class' => 'span2', 'id' => 'supplier_' . $data->id, 'disabled' . $data->isFormEnabled() => 'on'));
////    }
//    ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'supplier_inn',
//            'sortable' => false,
//            'type' => 'raw',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//            'value' => function($data) {
//        return CHtml::textField('supplier', $data->supplier_inn, array('class' => 'span2', 'id' => 'supplier_inn_' . $data->id, 'disabled' . $data->isFormEnabled() => 'on'));
//    }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'status',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) use($itemStatus) {
        return $itemStatus->getName($data->status); //CHtml::dropDownList('status', $data->status, $itemStatus->getList(), array('class' => 'span2', 'id' => 'status_' . $data->id, 'disabled' . $data->isFormEnabled() => 'on'));
    }),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'payed_status',
//            'sortable' => false,
//            'type' => 'raw',
//            'value' => function($data) use($itemStatus) {
//        return $itemStatus->getPayedName($data->payed_status);
//    },
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
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
            'template' => '{save} ',
            'buttons' => array(
                'save' => array(
                    'label' =>Yii::t('shop_cart', 'Save') ,
                    'url' => '$data->id',
                    'imageUrl' => '/images/icons/save.png',
                    'options' => array('class' => 'admin_order_buttons', 'target' => '_blank'),
                    'click' => 'function(){ShopCartSaveItem($(this).attr("href"));return false;}',
                    'visible' => '$data->isFormEnabled()!=\'\'',
                ),
                'delete' => array(
                    'url' => 'array(\'items/delete\',\'id\'=>$data->id)',
                    'visible' => '$data->isFormEnabled()!=\'\'',
                ),
            ),
        ),
    ),
));
?>

