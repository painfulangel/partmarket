<?php
	$url = Yii::app()->createUrl('/shop_cart/adminItems/save');
	$user_url = Yii::app()->createUrl('/shop_cart/items/save');

	$csrfName = Yii::app()->request->csrfTokenName;
	$csrfToken = Yii::app()->request->csrfToken;
?>
var ShopCartCSRF={ <?php echo $csrfName?>:'<?php echo $csrfToken ?>' };
function ShopCartSaveItem(id, front) {
	$.ajax({
        type: "POST",
        url: '<?php echo $url ?>',
        cache: false,
        data: {
			front: typeof(front) != 'undefined' ? front : 1,
			id: id,
			status: ($('#status_' + id).length == 1 ? $('#status_' + id).val() : ''),
			supplier: ($('#supplier_' + id).length == 1 ? $('#supplier_' + id).val() : ''),
			delivery: ($('#delivery_' + id).length == 1 ? $('#delivery_' + id).val() : ''),
			quantum: ($('#quantum_' + id).length == 1 ? $('#quantum_' + id).val() : ''),
			price: ($('#price_' + id).length == 1 ? $('#price_' + id).val() : ''),
			article: ($('#article_' + id).length == 1 ? $('#article_' + id).val() : ''),
			name: ($('#name_' + id).length == 1 ? $('#name_' + id).val() : ''),
			brand: ($('#brand_' + id).length == 1 ? $('#brand_' + id).val() : ''),
			<?php echo $csrfName ?>: '<?php echo $csrfToken ?>',
        },
        dataType: "json",
        timeout: 5000,
        success: function(data) {
            ShowWindow('<?php echo Yii::t('shop_cart', 'Change of goods') ?>', data.msg);
                $.fn.yiiGridView.update("orders-grid"); 
        },
        error: function() {
            alert('<?php echo Yii::t('shop_cart', 'An error occurred.') ?>');
        }
    });
}

function ShopCartUserSaveItem(id) {
    $.ajax({
        type: "POST",
        url: '<?php echo $user_url ?>',
        cache: false,
        data:
                {
                    id: id,
                    status: $('#status_' + id).val(),
                    supplier: $('#supplier_' + id).val(),
                    delivery: $('#delivery_' + id).val(),
                    quantum: $('#quantum_' + id).val(),
                    price: $('#price_' + id).val(),
                    article: $('#article_' + id).val(),
                    name: $('#name_' + id).val(),
                    brand: $('#brand_' + id).val(),
                    <?php echo $csrfName ?>: '<?php echo $csrfToken ?>',
                },
        dataType: "json",
        timeout: 5000,
        success: function(data) {
            ShowWindow('<?php echo Yii::t('shop_cart', 'Change of goods') ?>', data.msg);
                $.fn.yiiGridView.update("orders-grid"); 
        },
        error: function() {
            alert('<?php echo Yii::t('shop_cart', 'An error occurred.') ?>');
        }
    });
}

var ShopCartAddNewItemState = false;
var ShopCartMergeState = false;
var ShopCartMergeBlock = '';
function ShopCartAddNewItem() {
    if (ShopCartAddNewItemState) {
        ShopCartAddNewItemState = !ShopCartAddNewItemState;
        $('#quick_item_form').hide();
    } else {
        ShopCartAddNewItemState = !ShopCartAddNewItemState;
        $('#quick_item_form').show();
    }
}

function ShopCartMergeOrders() {
    if (ShopCartMergeState) {
        ShopCartMergeState = !ShopCartMergeState;
        $('#merge_form').hide();
    } else {
        ShopCartMergeState = !ShopCartMergeState;
        $('#merge_form').show();
    }
}

function ShopCartMergeAddField() {
    if (ShopCartMergeBlock == '')
        ShopCartMergeInitBlock();
    temp = document.createElement('div');
    temp.innerHTML = ShopCartMergeBlock;
    document.getElementById('merge_form_fields').appendChild(temp);
}

function ShopCartMergeInitBlock() {
    ShopCartMergeBlock = document.getElementById('merge_form_fields').innerHTML;
}

function ShopCartMergeRun() {
    document.getElementById('merge-form').submit();
    //   document.getElementById('merge_form_fields').innerHTML = ShopCartMergeBlock;
}

function ShopCartMergeCheck() {
    var data = {
        merge_id: {},
        id: 0,
        YII_CSRF_TOKEN: "",
    };
    var price = new Array;
    i = 0;
    $('#merge_form input').each(function () {
        if ($(this).attr('name') == 'YII_CSRF_TOKEN')
            data.YII_CSRF_TOKEN = $(this).attr('value');
        else if ($(this).attr('name') == 'merge_id[]') {
            price.push("data.merge_id[" + i + "]=" + $(this).attr('value'));
            data.merge_id[i] = $(this).attr('value');
            i++;
        }
        else
            data.id = $(this).attr('value');
    });
    $.ajax({
        type: "POST",
        url: "/shop_cart/adminOrders/mergeOrdersCheck",
        cache: false,
        data: data,
        dataType: "json",
        timeout: 5000,
        success: function (data) {
            if (data.is_not_payed) {
                if (window.confirm("<?php echo Yii::t('shop_cart', 'Association of the order will remove the join orders. All positions will be transferred to the current order. Continue?') ?>")) {
                    if (data.is_not_one_owner) {
                        if (window.confirm("<?php echo Yii::t('shop_cart', 'One or more attachable orders from different clients, Continue?') ?>")) {
                            ShopCartMergeRun();
                        }
                    } else {
                        ShopCartMergeRun();
                    }
                }
            } else {
                alert('<?php echo Yii::t('shop_cart', 'Accession is not possible, there are paid orders.') ?>');
            }
        },
        error: function () {
            alert('<?php echo Yii::t('shop_cart', 'An error has occurred. Perhaps You are not logged in.') ?>');
        }
    });
}

function ShopCartPriceAllChange() {
    $('#Items_price_all').val($('#Items_quantum').val() * $('#Items_price').val());
}

function ShopChangePrintOrder() {
    $('.print_clear').each(function () {
        $(this).hide();
    });
    $('.preprint').each(function () {
        $(this).parent().append($(this).attr('value'));
        $(this).hide();
    });
    window.print();
}

function ShopCartDeleteTrash(url) {
    if (!confirm("<?php echo Yii::t('shop_cart', 'Are you sure you want to delete all items in a basket?') ?>")) {
        return false;
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: ShopCartCSRF,
        cache: false,
        success: function (data) {
            $.fn.yiiGridView.update('Orders-grid');
            ShowWindow('<?php echo Yii::t('shop_cart', 'Cleaning baskets') ?>', '<?php echo Yii::t('shop_cart', 'Data successfully removed') ?>');
        },
    });
}

function ShopCartGetSupplierOrder() {
    $('.supplier-checkbox ').each(function () {
        if ($(this).attr('checked')) {
            $('#items-supplier-form-elements').append($(this).clone());
        }
    });
    $('#items-supplier-form').submit();
    return false;
}

function ShopCartUpdateAll() {

    return false;
}

function ConfirmOrder(id_order) {
	var self = $('a.btn-confirm');
	
	self.attr('disabled', 'disabled');
	
	$.post("/shop_cart/adminOrders/confirm/", { <?php echo $csrfName?>:'<?php echo $csrfToken ?>', id_order: id_order }, function( data ) {
		if (typeof(data.success) != 'undefined') {
			self.remove();
			alert(data.success);
		} else {
			self.removeAttr('disabled');
			if (typeof(data.error) != 'undefined') alert(data.error);
		}
	}, "json");
}

$(function() {
	$('button.btn-cancel').click(function() {
		var id_order = $(this).attr('rel');
		var self = $(this);
		
		self.attr('disabled', 'disabled');
		
		$.post("/shop_cart/orders/cancelOrder/", { <?php echo $csrfName?>:'<?php echo $csrfToken ?>', id_order: id_order }, function( data ) {
			if (typeof(data.success) != 'undefined') {
				self.remove();
				alert(data.success);
			} else {
				self.removeAttr('disabled');
				if (typeof(data.error) != 'undefined') alert(data.error);
			}
		}, "json");
	});

    if ($('iframe[name=_footer_iframe]').length == 0) {
        $('body').append('<iframe name="_footer_iframe" style="display: none;"></iframe>');
    }
});