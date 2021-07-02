<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('brands', 'New brands')));

$this->pageTitle = Yii::t('brands', 'New brands');
?>
<h1><?php echo Yii::t('brands', 'New brands'); ?></h1>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('brands', 'Check as incorrect'), null, array('class' => 'btn', 'onclick' => 'markAsIncorrect();')); ?>
    <?php echo CHtml::link(Yii::t('brands', 'List of incorrect brands'), array('/brands/adminnew/adminIncorrect/'), array('class' => 'btn')); ?>
    <?php echo CHtml::link(Yii::t('brands', 'Add brand'), null, array('class' => 'btn', 'onclick' => 'addBrand();')); ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'brands-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'value' => '"<input type=\"checkbox\" class=\"brandsnew\" id=\"b$data->id\" value=\"$data->id\">"',
            'type' => 'raw',
            'filter' => false,
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price_id',
            'value' => 'is_object($data->price) ? CHtml::link($data->price->name." (ID = ".$data->price_id.")", array("/prices/admin/priceTable/", "id" => $data->price_id), array("target" => "_blank")) : ""',
            'type' => 'raw',
            'filter' => BrandsNew::selectPrices(),
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'warehouse',
            'value' => 'is_object($data->price) && is_object($data->price->store) ? $data->price->store->name : ""',
            'filter' => false,
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{delete}',
            'htmlOptions' => array('style' => 'width: 90px;'),
        ),
    ),
));
?>
<script>
    function markAsIncorrect() {
        var ids = new Array();
        $('input.brandsnew:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length == 0) {
            alert("<?php echo Yii::t('brands', 'Check one brand at least'); ?>");
        } else {
            $.post("<?php echo Yii::app()->createUrl('/brands/adminnew/check/'); ?>", { ids: ids, <?php echo Yii::app()->request->csrfTokenName; ?>:"<?php echo Yii::app()->request->csrfToken; ?>" }, function( data ) {
                $.fn.yiiGridView.update('brands-grid');
            });
        }

        return false;
    }

    function addBrand() {
        var ids = new Array();
        $('input.brandsnew:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length == 0) {
            alert("<?php echo Yii::t('brands', 'Check one brand at least'); ?>");
        } else {
            $.post("<?php echo Yii::app()->createUrl('/brands/adminnew/addBrand/'); ?>", { ids: ids, <?php echo Yii::app()->request->csrfTokenName; ?>:"<?php echo Yii::app()->request->csrfToken; ?>" }, function( data ) {
                $.fn.yiiGridView.update('brands-grid');
            });
        }

        return false;
    }
</script>
<style>
    table.items.table input[type=checkbox] {
        margin-top: -3px;
    }
</style>