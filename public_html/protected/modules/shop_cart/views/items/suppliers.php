<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('shop_cart', 'Goods') => array('admin'), Yii::t('shop_cart', 'Orders to suppliers')));

$this->pageTitle = Yii::t('shop_cart', 'Orders to suppliers');



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

<h1><?= Yii::t('shop_cart', 'Orders to suppliers') ?></h1>

<div class="search-form" >
    <?php
    $this->renderPartial('_search_suppliers', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'Items-grid',
    'dataProvider' => $model->search(),
    //'filter' => $model,
    'afterAjaxUpdate' => "function() { 
        jQuery('#date_create_search').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true'})); 
    }",
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'brand',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'sortable' => false,
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'article',
            'type' => 'raw',
            'sortable' => false,
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price',
            'type' => 'raw',
            'sortable' => false,
            'value' => 'Yii::app()->controller->module->getPriceFormatFunction($data->price)',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'quantum',
            'type' => 'raw',
            'sortable' => false,
            'value' => '$data->quantum',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'supplier_checkbox',
            'type' => 'raw',
            'sortable' => false,
            'value' => 'CHtml::checkBox(\'ids[\'.$data->id.\']\', true, array(\'class\' => \'supplier-checkbox\'))',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
    ),
));
?>
<?php
$this->renderPartial('_form_suppliers', array(
    'model' => $model,
));
?>