<?php
	$csrfName = Yii::app()->request->csrfTokenName;
	$csrfToken = Yii::app()->request->csrfToken;
?>
function payOrderCourier(id_order) {
	var self = $('input.btn-pay');
	
	self.attr('disabled', 'disabled');
	
	$.post("/webPayments/webPaymentsCourier/confirmPayOrder/", { <?php echo $csrfName?>:'<?php echo $csrfToken ?>', id_order: id_order }, function( data ) {
		if (typeof(data.success) != 'undefined') {
			self.remove();
			window.location.href = "<?php echo $this->createUrl('/shop_cart/orders/index'); ?>";
		} else {
			self.removeAttr('disabled');
			if (typeof(data.error) != 'undefined') alert(data.error);
		}
	}, "json");
}