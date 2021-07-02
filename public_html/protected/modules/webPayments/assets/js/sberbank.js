function paySberbank(id_order) {
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