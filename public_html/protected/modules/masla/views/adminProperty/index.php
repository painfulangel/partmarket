<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('masla', 'Oil property')));

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
<h1><?php echo Yii::t('masla', 'Oil property'); ?></h1>
<?php /* ?><div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('masla', 'Create'), array('create'), array('class' => 'btn')); ?>
</div><?php */ ?>
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
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'filter',
    		'filter' => array('0' => Yii::t('masla', 'No'), '1' => Yii::t('masla', 'Yes')),
    		'checkedButtonLabel' => Yii::t('masla', 'Disable'),
    		'uncheckedButtonLabel' => Yii::t('masla', 'Enable'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'filter_closed',
    		'filter' => array('0' => Yii::t('masla', 'No'), '1' => Yii::t('masla', 'Yes')),
    		'checkedButtonLabel' => Yii::t('masla', 'Disable'),
    		'uncheckedButtonLabel' => Yii::t('masla', 'Enable'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'class'    => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view}',
        	'buttons'  => array(
        		'view' => array(
        			'url' => 'Yii::app()->createUrl("masla/adminProperty/property", array("id"=>$data->id))',
        		),
        	),
        ),
    ),
))
?>