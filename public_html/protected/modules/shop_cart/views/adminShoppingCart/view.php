<?php
$this->pageTitle = Yii::t('shop_cart', 'Basket');
$this->breadcrumbs = array(Yii::t('shop_cart', 'Basket'));

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('cart-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->admin_header = array(
    array(
        'name' => Yii::t('shop_cart', 'Basket'),
        'url' => array('/shop_cart/adminShoppingCart/view'),
        'active' => true,
    ),
);
?>
<h1><?= Yii::t('shop_cart', 'Basket') ?></h1>
<?php
if (!isset($confirmation))
    $confirmation = '';
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
$cart_url = $this->createUrl('/shop_cart/shoppingCart/cart');
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'cart-grid',
    'dataProvider' => $model->search(),
    'afterAjaxUpdate' => 'function(){$("#shopping-cart").load("' . $cart_url . '");}',
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
            'value' => '$data->brand',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'article',
            'value' => '$data->article',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'value' => '$data->name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price',
            'value' => 'Yii::app()->controller->module->getPriceFormatFunction($data->price)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'quantum_all',
            'value' => '$data->quantum_all',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'quantum',
            'value' => 'CHtml::textField(\'amount_\' . $data->product_id, $data->quantum, array(\'max\'=>"$data->quantum_all" ,\'product_id\'=>$data->product_id,\'class\' => " amounts amount_$data->product_id",\'style\'=>\'width: 40px;\' ))',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price_total',
            'value' => 'Yii::app()->controller->module->getPriceFormatFunction($data->price*$data->quantum)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'delivery',
            'value' => '$data->delivery',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view} {update} {delete}',
            'buttons' => array(
                'view' => array(
                    'url' => '$data->go_link',
                    'visible' => '!empty($data->go_link)',
                ),
                'delete' => array(
                    'click' =>
                    "function() {
	$confirmation
	var th = this,
		afterDelete = function(){};
	jQuery('#cart-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#cart-grid').yiiGridView('update');
			afterDelete(th, true, data);
		},
		error: function(XHR) {
			return afterDelete(th, false, XHR);
		}
	});
	return false;
}
",
                ),
                'update' => array(
                    'label' => Yii::t('shop_cart', 'To keep the number'),
                    'url' => '$data->product_id',
                    'options' => array('class' => 'cart_update_quantum'),
                    'click' => 'function(){ShoppingCartUpdateQuantum($(this).attr("href"));return false;}',
                ),
            ),
            'htmlOptions' => array('style' => 'width: 90px;'),
        ),
    ),
));
?>
<div class="form-actions">
    <?= CHtml::link(Yii::t('shop_cart', 'Checkout'), array('adminMakeOrders/initStep'), array('class' => 'btn btn-primary')) ?>
    <?= CHtml::link(Yii::t('shop_cart', 'To apply changes'), '', array('class' => 'btn btn-primary', 'id' => 'save-new-prices')) ?>
    <img src="/images/loading.gif" style='display: none' id = "ajax-busy"/>
</div>



<?php
$url = $this->createUrl('updateAll');
$csrfName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;

$text_js_name = Yii::t('shop_cart', 'Basket');
$text_js_error = Yii::t('shop_cart', 'An error has occurred. Perhaps You are not logged in.');
$text_js_success = Yii::t('shop_cart', 'The data was successfully saved');

Yii::app()->clientScript->registerScript('/crosses/admin/crossSave', <<<EOP
    /*
    Collects data from /cross/admin/crossTable and sends it to server to update prices
     */
    $('#save-new-prices').click(function(){
        var price = new Array, name;
        var amounts = {};
        $('.amounts').each(function(){
            price.push("amounts[" + $(this).attr('product_id') + "]=" + $(this).attr('value'));
            amounts[$(this).attr('product_id')] = $(this).attr('value')
        });
        
        $('#save-new-prices').addClass('disabled');
        $.ajax({
            type: "POST",
            url: "$url",
            cache: false,
            data:
            {
                quantums : amounts,
                
                $csrfName : "$csrfToken",
            },
            dataType: "json",
            timeout: 5000,
            beforeSend : function(){
                $('#ajax-busy').show();
            },
            complete : function(){
                $('#ajax-busy').hide();
            },
            success: function (data) {
                ShowWindow('$text_js_name','$text_js_success');
                $('#save-new-prices').removeClass('disabled');
            },
            error: function() {
                alert('$text_js_error');
            }
        });
        return false;
    });
EOP
);
?>



