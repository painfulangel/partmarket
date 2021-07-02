<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('tires', 'Tires property') => array('/tires/adminTiresProperty/admin'), $model->name));

$this->pageTitle = Yii::t('tires', 'Tires property');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('tires', 'Tires catalog'),
        'url' => array('/tires/adminTires/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('tires', 'Tires property'),
        'url' => array('/tires/adminTiresProperty/admin'),
        'active' => true,
    ),
);
?>
<h1><?php echo $model->name; ?></h1>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('tires', 'Create'), array('create', 'id_property' => $model->primaryKey), array('class' => 'btn')); ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'page-grid',
    'dataProvider' => $model2->search(),
    'filter' => $model2,
    'columns' => array(
        array(
            'name' => 'id',
            'headerHtmlOptions' => array(
                'width' => 50,
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'value',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'popular',
            'filter' => array('0' => Yii::t('katalogAccessories', 'No'), '1' => Yii::t('katalogAccessories', 'Yes')),
            'checkedButtonLabel' => Yii::t('katalogAccessories', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('katalogAccessories', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'name' => 'order',
            'class' => 'ext.OrderColumn.OrderColumn',
            'htmlOptions' => array('style' => 'min-width: 25px;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
        	'buttons' => array(
        		'delete' => array(
        			'visible' => '$data->isUsed()',		
        		),		
        	),
        ),
    ),
))
?>