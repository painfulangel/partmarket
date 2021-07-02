<?php
$this->pageTitle = Yii::t('webPayments', 'Payment method');
$this->breadcrumbs = array(
    Yii::t('webPayments', 'Payment method'),
);
?>
<h1><?php echo Yii::t('webPayments', 'Payment method'); ?></h1>
<div>
    <?php if ($order) echo Yii::app()->config->get('Site.PaymentsText'); ?>
    <?php if ($sum) { ?>
    <br><?php echo Yii::t('shop_cart', 'Remaining amount'); ?>: <?php echo $sum; ?>
    <?php } ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView'     => '_view',
    'template'     => '{items}',
	'emptyText'    => '',
	'viewData'     => array('order' => $order),
));
?>
<div class="clear"></div>