<?php
	$csrfName = Yii::app()->request->csrfTokenName;
	$csrfToken = Yii::app()->request->csrfToken;
?>
function payOrderFromPersonalAccount(id_order) {
	var self = $('input.btn-pay');
	
	self.attr('disabled', 'disabled');
	
	$.post("/webPayments/webPaymentsPersonalAccount/confirmPayOrder/", { <?php echo $csrfName?>:'<?php echo $csrfToken ?>', id_order: id_order }, function( data ) {
		if (typeof(data.success) != 'undefined') {
			self.remove();
			window.location.href = "<?php echo $this->createUrl('/shop_cart/orders/index'); ?>";
		} else {
			self.removeAttr('disabled');
			if (typeof(data.error) != 'undefined') alert(data.error);
		}
	}, "json");
}

function prepayOrderFromPersonalAccount(id_order) {
	var self = $('input.btn-pay');
	
	self.attr('disabled', 'disabled');
	
	$.post("/webPayments/webPaymentsPersonalAccount/confirmPrepayOrder/", { <?php echo $csrfName?>:'<?php echo $csrfToken ?>', id_order: id_order }, function( data ) {
		if (typeof(data.success) != 'undefined') {
			self.remove();
			window.location.href = "<?php echo $this->createUrl('/shop_cart/orders/index'); ?>";
		} else {
			self.removeAttr('disabled');
			if (typeof(data.error) != 'undefined') alert(data.error);
		}
	}, "json");
}