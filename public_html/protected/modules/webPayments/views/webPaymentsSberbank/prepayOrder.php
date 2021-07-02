<?php
$this->breadcrumbs = array(
    Yii::t('shop_cart', 'Prepayment of order №{number}', array('{number}' => $order->primaryKey)) => array('/webPayments/webPayments/prepay', 'order' => $order->primaryKey),
    $model->name,
);

$this->pageTitle = $model->name;

$sum = $order->getPrePaySum();
?>
<h1><?php echo $model->name; ?></h1>
<ol>
	<li><?php echo Yii::t('webPayments', 'Enter to Sberbank.Online.'); ?></li>
	<li><?php echo Yii::t('webPayments', 'Choose "Transfer customer to Sberbank".'); ?></li>
	<li><?php echo Yii::t('webPayments', 'Enter the card number: {number}.', array('{number}' => $model->system_login)); ?></li>
	<li><?php echo Yii::t('webPayments', 'Enter the amount of the order: {sum}', array('{sum}' => Yii::app()->getModule('shop_cart')->getPriceFormatFunction($sum))); ?></li>
	<li><?php echo Yii::t('webPayments', 'Enter the order number in the free field: {order}.', array('{order}' => $order->primaryKey)); ?></li>
	<li><?php echo Yii::t('webPayments', 'Continue the payment, check the data:').' '.$order->getUser()->getFullName(); ?></li>
	<li><?php echo Yii::t('webPayments', 'After that, click the button ').'"'.Yii::t('webPayments', 'I\'ve made prepayment').'"'; ?></li>
</ol>
<?php
    echo CHtml::button(Yii::t('webPayments', 'I\'ve made prepayment'), array('class' => 'btn btn-success btn-prepay', 'onclick' => 'prepaySberbank('.$order->primaryKey.'); return false;'));
?>