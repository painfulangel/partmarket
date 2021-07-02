<?php
	$this->breadcrumbs = array(
		Yii::t('webPayments', 'Payment method') => array('/webPayments/webPayments/pay', 'order' => $order->primaryKey),
		$model->name,
	);
	
	$this->pageTitle = $model->name;
?>
<h1><?php echo $model->name; ?></h1>
<ol>
	<li><?php echo Yii::t('webPayments', 'Enter to Sberbank.Online.'); ?></li>
	<li><?php echo Yii::t('webPayments', 'Choose "Transfer customer to Sberbank".'); ?></li>
	<li><?php echo Yii::t('webPayments', 'Enter the card number: {number}.', array('{number}' => $model->system_login)); ?></li>
	<li><?php echo Yii::t('webPayments', 'Enter the amount of the order: {sum}', array('{sum}' => Yii::app()->getModule('shop_cart')->getPriceFormatFunction($order->left_pay))); ?></li>
	<li><?php echo Yii::t('webPayments', 'Enter the order number in the free field: {order}.', array('{order}' => $order->primaryKey)); ?></li>
	<li><?php echo Yii::t('webPayments', 'Continue the payment, check the data:').' '.$model->system_extra_parametr/*$order->getUser()->getFullName()*/; ?></li>
	<li><?php echo Yii::t('webPayments', 'After that, click the button ').'"'.Yii::t('webPayments', 'I paid the order').'"'; ?></li>
</ol>
<?php
	echo CHtml::button(Yii::t('webPayments', 'I paid the order'), array('class' => 'btn btn-success btn-pay', 'onclick' => 'paySberbank('.$order->primaryKey.'); return false;'));
?>