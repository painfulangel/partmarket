<?php
	if (($data->primaryKey == 10) && is_object($order) && ($order->left_pay < 3000)) {

	} else {

    $link = array('/webPayments/webPayments'.$data->system_name.'/'.($order ? (isset($prepay) ? 'prepayOrder' : 'payOrder') : 'create'));
	
	if ($order) {
		$link['order'] = $order->primaryKey;
	} else {
		$link['sum'] = ($this->temp == NULL ? 0 : $this->temp);
	}

	//echo intval($data->primaryKey == 10).' - '.intval(is_object($order)).' - '.intval($order->left_pay < 3000);
?>
<div class="span3 b_type_paym" style="text-align: center; min-height: 100px;">
    <div><h3><?php echo CHtml::link($data->name, $link) ?></h3></div>
    <div><?php echo $data->description ?></div>
    <div><?php echo CHtml::link(Yii::t('webPayments', 'To Pay'), $link, array('class' => 'btn btn-primary')) ?></div>
</div>
<?php
	}
?>