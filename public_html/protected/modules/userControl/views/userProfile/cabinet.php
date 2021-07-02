<?php
$this->breadcrumbs = array(
    Yii::t('userControl', 'Personal account'),
);
$this->pageTitle = Yii::t('userControl', 'Personal account');

$this->renderPartial('userControl.views.userProfile._cabinet_top', array('title' => Yii::t('userControl', 'Personal account')));
?>
<div class="row-fluid">
    <div class="span5" id="inf4">
        <h4><img src="/images/theme/w1.png"><?php echo CHtml::link(Yii::t('userControl', 'Basket'), array('/shop_cart/shoppingCart/view')) ?>
        </h4>
        <div><?php echo Yii::t('userControl', 'Contents of your virtual basket. The period of storage of goods in a basket is limited - no more than 3 days') ?></div>  
    </div>
    <div class="span2"></div>
    <div class="span5" id="inf4">
        <h4><img src="/images/theme/w7.png"> <?php echo CHtml::link(Yii::t('userControl', 'Orders'), array('/shop_cart/orders/index')) ?><img src="/images/theme/w6.png"><?php echo CHtml::link(Yii::t('userControl', 'Positions'), array('/shop_cart/items/index')) ?></h4>
        <div><?php echo Yii::t('userControl', 'МMonitoring of orders, viewing of all orders, export of data / All positions of orders on one page.') ?></div>  
    </div>
</div>

<div class="row-fluid">
    <div class="span5" id="inf4">
        <h4><img src="/images/theme/w2.png"><?php echo CHtml::link(Yii::t('userControl', 'Cars'), array('/userControl/usersCars/index')) ?>
        </h4>
        <div><?php echo Yii::t('userControl', 'The list of cars in your Garage.') ?></div>  
    </div>
    <div class="span2"></div>
    <div class="span5" id="inf4">
        <h4><img src="/images/theme/w5.png"><?php echo CHtml::link(Yii::t('menu', 'Messages'), array('/userControl/userMessages/index')) ?></h4>
        <div><?php echo Yii::t('messages', 'Here you can write the letter to your manager and read the answer') ?></div>  
    </div>
    <?php /* ?><div class="span5" id="inf4">
        <h4><img src="/images/theme/w5.png"><?php echo CHtml::link(Yii::t('userControl', 'Inquiries on VIN'), array('/requests/requestVin/create')) ?></h4>
        <div><?php echo Yii::t('userControl', 'Inquiry of cost of auto spare parts in car parameters.') ?></div>  
    </div><?php */ ?>
</div>

<div class="row-fluid">
    <div class="span5" id="inf4">
        <h4><img src="/images/theme/w3.png"><?php echo CHtml::link(Yii::t('userControl', 'Settings'), array('/userControl/userProfile/settings')) ?></h4>
        <div><?php echo Yii::t('userControl', 'РEditing personal information, change of office of service, choice of a payment method and delivery.') ?></div>  
    </div>
    <div class="span2"></div>
    <div class="span5" id="inf4">
        <h4><img src="/images/theme/w4.png"><?php echo CHtml::link(Yii::t('userControl', 'Balance'), array('/userControl/userBalance/index')) ?></h4>
        <div><?php echo Yii::t('userControl', 'The state of your cash and Bank accounts') ?></div>  
    </div>
</div>
<?php
$this->breadcrumbs = array(
    Yii::t('userControl', 'Personal account'),
);
$this->pageTitle = Yii::t('userControl', 'Personal account');
?>
<br/><br/><br/>