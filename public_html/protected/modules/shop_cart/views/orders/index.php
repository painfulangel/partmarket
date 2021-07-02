<?php
	$this->breadcrumbs = array(
		Yii::t('shop_cart', 'My orders'),
	);
	$this->pageTitle = Yii::t('shop_cart', 'My orders');
	
	$this->renderPartial('userControl.views.userProfile._cabinet_top', array('title' => Yii::t('shop_cart', 'My orders')));
	
	Yii::app()->clientScript->registerScript('search-order', "
$('.search-form-order form').submit(function(){
	$.fn.yiiGridView.update('orders-grid', {
		data: $(this).serialize()
	});
        $('#status'+$('.order_change_status').val()).click();
	return false;
});");
	
	if ($neworder) {
?>
<div class="ordersuccess span6">
<?php
	if ($checkOrder) {
		echo Yii::t('shop_cart', 'We have accepted your order No.{number} for processing.', array('{number}' => $neworder)).
			 '<br>'.
			 Yii::t('shop_cart', 'After reviewing it, we will contact you to clarify the terms of delivery of the order.');
	} else {
		echo Yii::t('shop_cart', 'Thank you for your order №{number}.', array('{number}' => $neworder)).
			 '<br>'.
			 Yii::t('shop_cart', 'You can pay it <a href="{link}">here</a>.', array('{link}' => Yii::app()->createAbsoluteUrl('/webPayments/webPayments/pay', array('order' => $neworder))));
	}
?>
</div>
<div style="clear: both;"></div>
<?php
	}
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
?>
<div class="order-warning" style="color: red; font-weight: bold; margin-bottom: 20px;">
<?php echo 'Для начала работы с Вашим заказом он должен быть оплачен полностью или частично. '; ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs',
    'tabs' =>
    $tabs,
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'orders-grid',
//    'itemsCssClass'=>'table-hover',
    'dataProvider' => $model->userSearch(),
    'filter' => $model,
    'afterAjaxUpdate' => "function() { 
	jQuery('#date_create_search').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true'})); 
}",
    'template' => '{items} {pager}',
    /*'selectionChanged' => 'js:function(id) {
    document.location = \'/shop_cart/orders/update?id=\'+$.fn.yiiGridView.getSelection(id);
}',*/
    'pagerCssClass' => 'pagination pagination-centered',
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
//            'value' => 'CHtml::link($data->id,array(\'/shop_cart/orders/update\',\'id\'=>$data->id))',
//            'value' => 'CHtml::link($data->id,array(\'/shop_cart/orders/update\',\'id\'=>$data->id))',
            'type' => 'raw',
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("id")
            ),
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
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("create_date")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'total_cost',
            'value' => 'Yii::app()->getModule(\'shop_cart\')->getPriceFormatFunction($data->total_cost)',
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("total_cost")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'left_pay',
            'value' => 'Yii::app()->getModule(\'shop_cart\')->getPriceFormatFunction($data->left_pay)',
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("total_cost")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'status',
            'filter' => $orderStatus->getList(),
            'type' => 'raw',
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("status")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) use($orderStatus) {
                return $orderStatus->getName($data->status);
            }),
        array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name' => 'pay_order',
			'filter' => false,
			'type' => 'raw',
			'htmlOptions' => array('style' => 'text-align: center;'),
			'headerHtmlOptions' => array('style' => 'text-align: center;'),
			'value' => function($data) use($orderStatus) {
	            if ($data->payed_status == 2) {
	            	return $orderStatus->getPayedName($data->payed_status);
	            } else if ($data->courier) {
	                return Yii::t('webPayments', 'Pay order in cash to the courier');
	            } else if ($data->canPayed()) {
	            	if ($data->confirmed == 1) {
	            	    $buttons = '';
	            	    
	            	    if ($data->isPrePayOrder() && !$data->prepay) $buttons .= CHtml::button(Yii::t('shop_cart', 'Prepayment'), array('class' => 'btn btn-success', 'onclick' => 'window.location.href="'.Yii::app()->createUrl('webPayments/webPayments/prepay', array('order' => $data->primaryKey)).'"; return false;')).'<br><br>';
	            	    
	            		$buttons .= CHtml::button(Yii::t('shop_cart', 'Pay'), array('class' => 'btn btn-success', 'onclick' => 'window.location.href="'.Yii::app()->createUrl('webPayments/webPayments/pay', array('order' => $data->primaryKey)).'"; return false;'));
	            	    
	            		return $buttons;
	            	} else 
	            		return Yii::t('shop_cart', 'Order is checking.');
	            }
	            
	            return '';
        	}),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}  ',
            //'viewButtonIcon'=>'fa fa-eye mobile-btn-icon',
            'updateButtonIcon'=>'fa fa-pencil mobile-btn-icon',
            //'deleteButtonIcon'=>'fa fa-trash mobile-btn-icon',
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