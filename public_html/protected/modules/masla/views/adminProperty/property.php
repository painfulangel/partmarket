<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('masla', 'Oil property') => array('/masla/adminProperty'), $model->name));

$this->pageTitle = Yii::t('masla', 'Oil property');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('masla', 'Oil catalog'),
        'url' => array('/masla/admin/'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('masla', 'Oil property'),
        'url' => array('/masla/adminProperty/'),
        'active' => true,
    ),
);
?>
<h1><?php echo $model->name; ?></h1>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('masla', 'Creating oil property value'), array('create', 'id_property' => $model->primaryKey), array('class' => 'btn')); ?>
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
        	'value' => 'is_null($data->value_number) ? $data->value : $data->value_number'
        ),
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'popular',
            'filter' => array('0' => Yii::t('masla', 'No'), '1' => Yii::t('masla', 'Yes')),
            'checkedButtonLabel' => Yii::t('masla', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('masla', 'Activate'),
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
        	'buttons' => array(
        		/*'delete' => array(
        			'visible' => '$data->isUsed()',		
        		),*/		
        	),
        ),
    ),
))
?>