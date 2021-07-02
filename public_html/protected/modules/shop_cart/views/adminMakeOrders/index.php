<?php
$this->breadcrumbs = array(
   Yii::t('shop_cart', 'My orders') ,
);
$this->pageTitle = Yii::t('shop_cart', 'My orders');
?>
<?php
$this->renderPartial('userControl.views.userProfile._cabinet_top', array('title' => Yii::t('shop_cart', 'My orders')));
?>
<?php
Yii::app()->clientScript->registerScript('search-order', "

$('.search-form-order form').submit(function(){
	$.fn.yiiGridView.update('orders-grid', {
		data: $(this).serialize()
	});
        $('#status'+$('.order_change_status').val()).click();
	return false;
});
");
?>

<div class="search-form-order">
    <?php
    $this->renderPartial('shop_cart.views.orders._search', array(
        'model' => $model,
    ));
    ?>
</div>



<?php
$tabs = array();

foreach ($orderStatus->getSearchList() as $k => $v) {
    $tabs[] = array(
        'label' => $v,
        'content' => '',
        'linkOptions' => array('id' => 'status' . $k, 'onclick' => '$(".order_change_status").val("' . $k . '");$.fn.yiiGridView.update(\'orders-grid\', {
		data: $(\'.search-form-order form\').serialize()
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
    'id' => 'orders-grid',
//    'itemsCssClass'=>'table-hover',
    'dataProvider' => $model->userSearch(),
    'filter' => $model,
    'afterAjaxUpdate' => "function() { 
        jQuery('#date_create_search').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true'})); 
    }",
    'template' => '{items} {pager}',
    'selectionChanged' => 'js:function(id) 
  {
    document.location = \'/shop_cart/orders/update?id=\'+$.fn.yiiGridView.getSelection(id);

  }',
    'pagerCssClass' => 'pagination pagination-centered',
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
//            'value' => 'CHtml::link($data->id,array(\'/shop_cart/orders/update\',\'id\'=>$data->id))',
//            'value' => 'CHtml::link($data->id,array(\'/shop_cart/orders/update\',\'id\'=>$data->id))',
            'type' => 'raw',
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
            'type' => 'raw',
            'name' => 'total_cost',
            'value' => 'Yii::app()->getModule(\'shop_cart\')->getPriceFormatFunction($data->total_cost)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'status',
            'filter' => $orderStatus->getList(),
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) use($orderStatus) {
        return $orderStatus->getName($data->status);
    }),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}  ',
            'buttons' => array(
                'delete' => array(
                    'visible' => '!$data->checkDone()',
                    'url' => "array('/shop_cart/orders/delete', 'id' => \$data->id)",
                ),
                'print' => array(
//                    'visible' => '!$data->checkDone()',
                    'label' => Yii::t('shop_cart', 'Bill'),
                    'imageUrl' => '/images/icons/print-list.png',
                    'url' => "array('/shop_cart/orders/orderBill', 'id' => \$data->id)",
                ),
                'update' => array(
                    'url' => "array('/shop_cart/orders/update', 'id' => \$data->id)",
                ),
            ),
            'htmlOptions' => array('style' => 'min-width:60px'),
        ),
    ),
));
?>