<?php
    $title = Yii::t('shop_cart', 'Prepayment of order №{number}', array('{number}' => $order->primaryKey));

    $this->pageTitle = Yii::t('webPayments', $title);
    $this->breadcrumbs = array(
        Yii::t('webPayments', $title),
    );
?>
<h1><?php echo $title; ?></h1>
<div>
	<?php echo Yii::t('shop_cart', 'Prepayment sum is').' '.Yii::app()->getModule('prices')->getPriceFormatFunction($order->getPrePaySum()); ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView'     => '_view',
    'template'     => '{items}',
	'emptyText'    => '',
	'viewData'     => array('order' => $order->primaryKey, 'prepay' => true),
));
?>
<div class="clear"></div>