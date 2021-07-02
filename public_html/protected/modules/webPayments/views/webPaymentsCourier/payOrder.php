<?php
    $this->breadcrumbs = array(
        Yii::t('webPayments', 'Payment method') => array('/webPayments/webPayments/pay', 'order' => $order->primaryKey),
        $model->name,
    );
    
    $this->pageTitle = $model->name;
?>
<h1><?php echo $model->name; ?></h1>
<div><?php echo Yii::t('webPayments', 'Attention! Custom positions are paid in advance.') ?></div><br>
<?php
    echo CHtml::button(Yii::t('webPayments', 'Choose'), array('class' => 'btn btn-success btn-pay', 'onclick' => 'payOrderCourier('.$order->primaryKey.');'));
?>