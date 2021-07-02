<?php
$this->breadcrumbs = array(
    Yii::t('shop_cart', 'Prepayment of order №{number}', array('{number}' => $order->primaryKey)) => array('/webPayments/webPayments/prepay', 'order' => $order->primaryKey),
        $model->name,
    );
    
    $this->pageTitle = $model->name;
    
    //Баланс пользователя
    $user = Yii::app()->getModule('userControl')->getCurrentUserModel();
    $model_balance = new UserBalanceOperations('search');
    $model_balance->user_id = $user->uid;
    $balance = $model_balance->getBalance();
    
    $sum = $order->getPrePaySum();
?>
<h1><?php echo $model->name; ?></h1>
<div><?php echo Yii::t('userControl', 'Your balance') ?>: <b><?php echo Yii::app()->getModule('currencies')->getFormatPrice($balance); ?></b></div>
<div><?php echo Yii::t('shop_cart', 'Prepayment of order №{number}', array('{number}' => $order->primaryKey)); ?>: <b><?php echo Yii::app()->getModule('currencies')->getFormatPrice($sum);?></b></div><br>
<?php
    if ($sum > $balance) {
        echo '<div class="error">'.Yii::t('webPayments', 'On your balance there aren\'t enough money for orders payment.').'</div>
              <div class="m-20">';
        
        echo CHtml::button(Yii::t('webPayments', 'Choose other payment method'), array('class' => 'btn btn-success', 'onclick' => 'window.location.href=\''.Yii::app()->controller->createUrl('/webPayments/webPayments/prepay', array('order' => $order->primaryKey)).'\';'));
        
        echo '</div>';
    } else {
        echo CHtml::button(Yii::t('shop_cart', 'Prepayment'), array('class' => 'btn btn-success btn-pay', 'onclick' => 'prepayOrderFromPersonalAccount('.$order->primaryKey.');'));
    }
?>