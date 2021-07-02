<?php
$this->breadcrumbs = array(
    Yii::t('shop_cart', 'Sheet goods'),
);
$this->pageTitle = Yii::t('shop_cart', 'Sheet goods');
?>

<?php
$this->renderPartial('userControl.views.userProfile._cabinet_top', array('title' => Yii::t('shop_cart', 'Sheet goods')));
?>
<?php
Yii::app()->clientScript->registerScript('search', "

$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('Items-grid', {
		data: $(this).serialize()
	});
        $('#istatus'+$('.items_change_status').val()).click();
	return false;
});
");
?>



<div class="search-form" >
    <?php
    $this->renderPartial('shop_cart.views.items._search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->
<?php
$tabs = array();

foreach ($itemStatus->getSearchList() as $k => $v) {
    $tabs[] = array(
        'label' => $v,
        'content' => '',
        'linkOptions' => array('id' => 'istatus' . $k, 'onclick' => '$(".items_change_status").val("' . $k . '");$.fn.yiiGridView.update(\'Items-grid\', {
		data: $(\'.search-form form\').serialize()
	});'),
        'active' => $model->status == $k ? true : false,
    );
}
$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs',
    'tabs' =>
    $tabs,
));
?>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'Items-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => "function() { 
        jQuery('#date_create_search').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true'})); 
    }",
    'pagerCssClass' => 'pagination pagination-centered',
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'order_id',
            'type' => 'raw',
            'value' => 'CHtml::link($data->order_id,array(\'/shop_cart/orders/update\',\'id\'=>$data->order_id))',
//            'value' => 'CHtml::link($data->order_id,Yii::app()->createUrl(\'/shop_cart/adminOrders/update\',array(\'id\'=>$data->order_id)),array(\'target\'=>\'_blank\'))',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
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
                    'dateFormat' => 'dd.mm.yy',
                ),
                    ), true),
            'value' => 'date(\'d.m.Y H:i:s\',$data->create_date)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'article',
            'type' => 'raw',
            'value' => '$data->article',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'brand',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'type' => 'raw',
            'value' => '$data->name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'delivery',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'price',
//            'type' => 'raw',
//            'value' => 'Yii::app()->getModule(\'shop_cart\')->getPriceFormatFunction($data->price)',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'quantum',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price_total',
            'sortable' => true,
            'value' => 'Yii::app()->getModule(\'shop_cart\')->getPriceFormatFunction($data->price_total)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'status',
            'filter' => $itemStatus->getList(),
            'type' => 'raw',
            'value' => function($data) use($itemStatus) {
                return $itemStatus->getUpdateBlock($data);
            },
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
//        'description',
//        array(
//            'class' => 'bootstrap.widgets.TbButtonColumn',
//            'template' => '{delete}',
//            'buttons' => array(
//                'delete' => array(
//                    'visible' => '!$data->checkDone()',
//                    'url' => "array('/shop_cart/items/delete', 'id' => \$data->id)",
//                ),
//            ),
//        ),
    ),
));
?>


