<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('brands', 'New brands') => array('/brands/adminnew/admin/'), Yii::t('brands', 'List of incorrect brands')));

$this->pageTitle = Yii::t('brands', 'List of incorrect brands');
?>
<h1><?php echo Yii::t('brands', 'List of incorrect brands'); ?></h1>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('brands', 'Return in new brands list'), null, array('class' => 'btn', 'onclick' => 'returnInNewBrands();')); ?>
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
    ),
));
?>
<script>
    function returnInNewBrands() {
        var ids = new Array();
        $('input.brandsnew:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length == 0) {
            alert("<?php echo Yii::t('brands', 'Check one brand at least'); ?>");
        } else {
            $.post("<?php echo Yii::app()->createUrl('/brands/adminnew/returnInNewBrands/'); ?>", { ids: ids, <?php echo Yii::app()->request->csrfTokenName; ?>:"<?php echo Yii::app()->request->csrfToken; ?>" }, function( data ) {
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