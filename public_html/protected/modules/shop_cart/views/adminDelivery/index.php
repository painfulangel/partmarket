<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('delivery', 'Delivery')));

$this->pageTitle = Yii::t('delivery', 'Delivery');
?>
<h1><?php echo Yii::t('delivery', 'Delivery') ?></h1>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('delivery', 'Create'), array('create'), array('class' => 'btn')) ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'page-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'headerHtmlOptions' => array(
                'width' => 50,
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price',
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'transport',
            'filter' => array('0' => Yii::t('delivery', 'No'), '1' => Yii::t('delivery', 'Yes')),
            'checkedButtonLabel' => Yii::t('delivery', 'Disable'),
            'uncheckedButtonLabel' => Yii::t('delivery', 'Enable'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('class' => 'toggle'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'active',
            'filter' => array('0' => Yii::t('delivery', 'No'), '1' => Yii::t('delivery', 'Yes')),
            'checkedButtonLabel' => Yii::t('delivery', 'Disable'),
            'uncheckedButtonLabel' => Yii::t('delivery', 'Enable'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        /*array(
            'name' => 'order',
            'class' => 'ext.OrderColumn.OrderColumn',
            'htmlOptions' => array('style' => 'min-width: 25px;'),
        ),*/
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
        ),
    ),
))
?>