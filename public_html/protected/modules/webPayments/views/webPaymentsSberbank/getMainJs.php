<?php
	$csrfName = Yii::app()->request->csrfTokenName;
	$csrfToken = Yii::app()->request->csrfToken;
?>
function paySberbank(id_order) {
	var self = $('input.btn-pay');
	
	self.attr('disabled', 'disabled');
	
	$.post("/webPayments/webPaymentsSberbank/confirmPayOrder/", { <?php echo $csrfName?>:'<?php echo $csrfToken ?>', id_order: id_order }, function( data ) {
		if (typeof(data.success) != 'undefined') {
			self.remove();
			alert(data.success);
		} else {
			self.removeAttr('disabled');
			if (typeof(data.error) != 'undefined') alert(data.error);
		}
	}, "json");
}

function prepaySberbank(id_order) {
	var self = $('input.btn-prepay');
	
	self.attr('disabled', 'disabled');
	
	$.post("/webPayments/webPaymentsSberbank/confirmPrepayOrder/", { <?php echo $csrfName?>:'<?php echo $csrfToken ?>', id_order: id_order }, function( data ) {
		if (typeof(data.success) != 'undefined') {
			self.remove();
			alert(data.success);
		} else {
			self.removeAttr('disabled');
			if (typeof(data.error) != 'undefined') alert(data.error);
		}
	}, "json");
}