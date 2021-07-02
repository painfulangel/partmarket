<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('delivery', 'Transport companies')));

$this->pageTitle = Yii::t('delivery', 'Transport companies');
?>
<h1><?php echo Yii::t('delivery', 'Transport companies') ?></h1>
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
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'price',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'active',
            'filter' => array('0' => Yii::t('delivery', 'No'), '1' => Yii::t('delivery', 'Yes')),
            'checkedButtonLabel' => Yii::t('delivery', 'Disable'),
            'uncheckedButtonLabel' => Yii::t('delivery', 'Enable'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
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