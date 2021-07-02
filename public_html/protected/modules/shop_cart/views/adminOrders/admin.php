<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('shop_cart', 'Orders')));

$this->pageTitle = Yii::t('shop_cart', 'Orders');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('Orders-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1><?php echo Yii::t('shop_cart', 'Orders') ?></h1>
<?php echo CHtml::link(Yii::t('shop_cart', 'Search form'), '#', array('class' => 'search-button btn')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'Orders-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => "function() { 
        jQuery('#date_create_search').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true'})); 
    }",
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'type' => 'raw',
            'value' => 'CHtml::link(\'<i class="icon-edit-order icon-id-order"></i>&nbsp;\'.$data->id, Yii::app()->createUrl(\'/shop_cart/adminOrders/update\',array(\'id\'=>$data->id)),array(\'target\'=>\'_blank\'))',
            'htmlOptions' => array(),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'user_id',
            'filter' => '',
            'type' => 'raw',
            'value' => 'CHtml::link(UserProfile::getUserOrderInfo($data->user_id),Yii::app()->createUrl(\'/userControl/adminUserProfile/view\',array(\'id\'=>$data->user_id)),array(\'target\'=>\'_blank\'))',
            'htmlOptions' => array(),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'create_date',
            'type' => 'raw',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'language' => 'ru',
                'attribute' => 'create_date',
                'htmlOptions' => array(
                    'id' => 'date_create_search'
                ),
                'options' => array(
                    'showAnim' => 'fold',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'showButtonPanel' => 'true',
                    'dateFormat' => 'dd.mm.yy',
                ),
                    ), true),
            'value' => 'date(\'d.m.Y H:i:s\',$data->create_date)',
            'htmlOptions' => array(),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'total_cost',
            'value' => 'Yii::app()->controller->module->getPriceFormatFunction($data->total_cost)',
            'htmlOptions' => array(),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'left_pay',
            'value' => 'Yii::app()->getModule(\'shop_cart\')->getPriceFormatFunction($data->left_pay)',
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("total_cost")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'status',
            'filter' => $orderStatus->getList(),
            'type' => 'raw',
            'htmlOptions' => array(),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'value' => function($data) use($orderStatus) { return $orderStatus->getUpdateBlock($data).$orderStatus->getDoneBlock($data).$orderStatus->getRefundBlock($data); }),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'payed_status',
            'filter' => $orderStatus->getPayedList(),
            'type' => 'raw',
            'value' => function($data) use($orderStatus) { return $orderStatus->getPayedName($data->payed_status); },
            'htmlOptions' => array(),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'credit',
            'value' => 'Yii::app()->getModule(\'shop_cart\')->getCreditInfo($data)',
            'htmlOptions' => array(
                'style' => 'text-align: center;',
                'aria-label'=>$model->getAttributeLabel("total_cost")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'prepay',
            'filter' => array('0' => Yii::t('webPayments', 'No'), '1' => Yii::t('webPayments', 'Yes')),
            'value' => '$data->prepay ? "'.Yii::t('webPayments', 'Yes').'" : "'.Yii::t('webPayments', 'No').'"',
            //'checkedButtonLabel' => Yii::t('webPayments', 'Deactivate'),
            //'uncheckedButtonLabel' => Yii::t('webPayments', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('class' => 'toggle'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'courier',
            'filter' => array('0' => Yii::t('webPayments', 'No'), '1' => Yii::t('webPayments', 'Yes')),
            'checkedButtonLabel' => Yii::t('webPayments', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('webPayments', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('class' => 'toggle'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'cancelled',
            'filter' => array('0' => Yii::t('webPayments', 'No'), '1' => Yii::t('webPayments', 'Yes')),
            'checkedButtonLabel' => Yii::t('webPayments', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('webPayments', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('class' => 'toggle'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{print} {bill} {waybill} {customerbill} {update} {delete} {restore}',
            'deleteConfirmation' => Yii::t('shop_cart', 'Are you sure you want to move your order to the basket?'),
            'buttons' => array(
                'print' => array(
                    'label' => Yii::t('shop_cart', 'Print order'),
                    'url' => 'array("print","id" => $data->id)',
                    'imageUrl' => '/images/icons/print.png',
                    'options' => array('class' => 'admin_order_buttons2  admin_order_buttons', 'target' => '_blank'),
                    'click' => 'function() {}',
                ),
                'update' => array(
                    'icon' => 'edit-order',
                ),
                'bill' => array(
                    'label' => Yii::t('shop_cart', 'Bill'),
//                    'url' => 'array("orderBill","id" => $data->id)',
                    'url' => 'array("bill","id" => $data->id)',
                    'icon' => 'bill',
                    'options' => array('class' => 'bill', 'target' => '_blank'),
                    'click' => 'function() {}',
                ),
                'waybill' => array(
                    'label' => Yii::t('shop_cart', 'Invoice'),
                    'url' => 'array("waybill","id" => $data->id)',
                    'icon' => 'waybill',
                    'options' => array('class' => 'waybill', 'target' => '_blank'),
                    'click' => 'function() {}',
                ),
                'customerbill' => array(
                    'label' => Yii::t('shop_cart', 'Invoice'),
                    'url' => 'array("customerBill","id" => $data->id)',
                    'icon' => 'waybill',
                    'options' => array('class' => 'customerbill', 'target' => '_blank'),
                    'click' => 'function() {}',
                ),
                'delete' => array(
                    'label' => Yii::t('shop_cart', 'Move to trash'),
                    'visible' => '!$data->checkDone()',
                ),
                'restore' => array(
                    'label' => Yii::t('shop_cart', 'Restore'),
                    'imageUrl' => '/images/icons/restore.png',
                    'url' => 'array("restore","id" => $data->id)',
                    'visible' => '$data->is_trash==1',
                    'options' => array('class' => 'admin_order_buttons5 admin_order_buttons'),
                    'click' => 'function() {
	if(!confirm("' . Yii::t('shop_cart', 'Are you sure you wish to restore the order?') . '")){
        return false;
        }
	var th=this;
	$.fn.yiiGridView.update(\'Orders-grid\', {
		type:\'POST\',
		url:$(this).attr(\'href\'),
                data:ShopCartCSRF,
		success:function(data) {
			$.fn.yiiGridView.update(\'Orders-grid\');
		}
	});
	return false;
}'
                ),
            ),
        ),
    ),
));

echo CHtml::link(Yii::t('shop_cart', 'Remove from basket'), '', array('class' => 'btn btn-primary', 'onclick' => 'ShopCartDeleteTrash("' . Yii::app()->createUrl('/shop_cart/adminOrders/deleteTrash') . '");return false;'));
?>
<script type="text/javascript">
    function credit_accepted(order_id) {
        $.post('/webPayments/webPaymentsCredit/success/', { order_id: order_id, <?php echo Yii::app()->request->csrfTokenName; ?>: '<?php echo Yii::app()->request->csrfToken; ?>' }, function(data) {
          if (typeof(data.success) != 'undefined') $('div.credit_' + order_id).html('<?php echo Yii::t('webPayments', 'Request is accepted'); ?>');
        }, "json");
    }

    function credit_denied(order_id) {
        $.post('/webPayments/webPaymentsCredit/fail/', { order_id: order_id, <?php echo Yii::app()->request->csrfTokenName; ?>: '<?php echo Yii::app()->request->csrfToken; ?>' }, function(data) {
          if (typeof(data.success) != 'undefined') $('div.credit_' + order_id).html('<?php echo Yii::t('webPayments', 'Request has been denied'); ?>');
        }, "json");
    }
</script>