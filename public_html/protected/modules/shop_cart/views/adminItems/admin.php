<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('shop_cart', 'Goods')));

$this->pageTitle = Yii::t('shop_cart', 'Goods');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('Items-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1><?= Yii::t('shop_cart', 'Goods') ?></h1>

<?php echo CHtml::link(Yii::t('shop_cart', 'Search form'), '#', array('class' => 'search-button btn')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'Items-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => "function() { 
        jQuery('#date_create_search').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true'})); 
    }",
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'order_id',
            'type' => 'raw',
            'value' => 'CHtml::link($data->order_id,Yii::app()->createUrl(\'/shop_cart/adminOrders/update\',array(\'id\'=>$data->order_id)),array(\'target\'=>\'_blank\'))',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'user_id',
            'filter' => '',
            'type' => 'raw',
            'value' => 'CHtml::link(UserProfile::getUserOrderInfo($data->user_id),Yii::app()->createUrl(\'/userControl/adminUserProfile/view\',array(\'id\'=>$data->user_id)),array(\'target\'=>\'_blank\'))',
            'htmlOptions' => array('style' => 'text-align: center;'),
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
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'article',
            'type' => 'raw',
            'value' => '$data->article',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'type' => 'raw',
            'value' => '$data->name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price',
            'type' => 'raw',
            'value' => 'Yii::app()->controller->module->getPriceFormatFunction($data->price)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'quantum',
            'type' => 'raw',
            'value' => '$data->quantum',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price_total',
            'sortable' => true,
            'value' => 'Yii::app()->controller->module->getPriceFormatFunction($data->price_total)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        'description',
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'status',
            'filter' => $itemStatus->getList(),
            'type' => 'raw',
            'value' => function($data) use($itemStatus) {
        return $itemStatus->getUpdateBlock($data);
    },
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'get_status',
            'filter' => array('0' =>Yii::t('shop_cart', 'No') , '1' =>Yii::t('shop_cart',  'Yes')),
            'checkedButtonLabel' => Yii::t('shop_cart', 'Not ordered'),
            'uncheckedButtonLabel' => Yii::t('shop_cart', 'Ordered'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        /*
          'currency',
          'price_echo',
          'quantum',
          'delivery',
          'article',
          'article_order',
          'supplier_inn',
          'supplier',
          'store',
          'name',
          'payed_status',
          'ic_status',
          'status',
          'create_date',
         */
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}',
            'buttons' => array(
               
                'update' => array(
                    'icon' => 'edit-item',
                ),
            ),
        ),
    ),
));
?>
