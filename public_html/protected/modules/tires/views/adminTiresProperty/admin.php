<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('tires', 'Tires property')));

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
<h1><?php echo Yii::t('tires', 'Tires property'); ?></h1>
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
    		'name' => 'closed',
    		'filter' => array('0' => Yii::t('tires', 'No'), '1' => Yii::t('tires', 'Yes')),
    		'checkedButtonLabel' => Yii::t('tires', 'Disable'),
    		'uncheckedButtonLabel' => Yii::t('tires', 'Enable'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'class'    => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view}',
        	'buttons'  => array(
        		'view' => array(
        			'url' => 'Yii::app()->createUrl("tires/adminTiresProperty/property", array("id"=>$data->id))',
        		),
        	),
        ),
    ),
))
?>