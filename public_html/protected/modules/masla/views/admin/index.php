<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('masla', 'Oil catalog')));

$this->pageTitle = Yii::t('masla', 'Oil catalog');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('masla', 'Oil catalog'),
        'url' => array('/masla/admin/'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('masla', 'Oil property'),
        'url' => array('/masla/adminProperty/'),
        'active' => false,
    ),
);
?>
<h1><?php echo Yii::t('masla', 'Oil catalog'); ?></h1>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('masla', 'Create'), array('create'), array('class' => 'btn')); ?>
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
        /*array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'type',
        	'filter' => TiresPropertyValues::selectList(1),
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        	'value' => 'is_object($data->typeObject) ? $data->typeObject->value : ""'
        ),*/
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'article',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
        	'class' => 'bootstrap.widgets.TbDataColumn',
        	'name' => 'country',
        	'filter' => MaslaPropertyValues::selectList(1),
        	'htmlOptions' => array('style' => 'text-align: center;'),
        	'headerHtmlOptions' => array('style' => 'text-align: center;'),
        	'value' => 'is_object($data->countryObject) ? $data->countryObject->value : ""'
        ),
        array(
        	'class' => 'bootstrap.widgets.TbDataColumn',
        	'name' => 'producer',
        	'filter' => MaslaPropertyValues::selectList(2),
        	'htmlOptions' => array('style' => 'text-align: center;'),
        	'headerHtmlOptions' => array('style' => 'text-align: center;'),
        	'value' => 'is_object($data->producerObject) ? $data->producerObject->value : ""'
        ),
        array(
        	'class' => 'bootstrap.widgets.TbDataColumn',
        	'name' => 'engine_type',
        	'filter' => MaslaPropertyValues::selectList(7),
        	'htmlOptions' => array('style' => 'text-align: center;'),
        	'headerHtmlOptions' => array('style' => 'text-align: center;'),
        	'value' => 'is_object($data->engineTypeObject) ? $data->engineTypeObject->value : ""'
        ),
        array(
        	'class' => 'bootstrap.widgets.TbDataColumn',
        	'name' => 'fuel_type',
        	'filter' => MaslaPropertyValues::selectList(8),
        	'htmlOptions' => array('style' => 'text-align: center;'),
        	'headerHtmlOptions' => array('style' => 'text-align: center;'),
        	'value' => 'is_object($data->fuelTypeObject) ? $data->fuelTypeObject->value : ""'
        ),
        array(
        	'class' => 'bootstrap.widgets.TbDataColumn',
        	'name' => 'oil_type',
        	'filter' => MaslaPropertyValues::selectList(9),
        	'htmlOptions' => array('style' => 'text-align: center;'),
        	'headerHtmlOptions' => array('style' => 'text-align: center;'),
        	'value' => 'is_object($data->oilTypeObject) ? $data->oilTypeObject->value : ""'
        ),
        /*array(
            'class' => 'ext.AttachmentBehavior.TbEImageColumn',
            'name' => 'image',
            'sortable' => true,
            'filter' => array('1' => Yii::t('masla', 'Yes'), '2' => Yii::t('masla', 'No')),
            'noFileFound' => '/images/nofoto.png',
            'htmlOptions' => array('style' => 'max-width: 60px; max-height: 60px; margin: 5px;'),
            'headerHtmlOptions' => array('style' => 'text-align:center; vertical-align;'),
        ),*/
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'active_state',
    		'filter' => array('0' => Yii::t('masla', 'No'), '1' => Yii::t('masla', 'Yes')),
    		'checkedButtonLabel' => Yii::t('masla', 'Disable'),
    		'uncheckedButtonLabel' => Yii::t('masla', 'Enable'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view} {update} {delete}',
        	'buttons' => array(
        		'view' => array(
        			'url' => 'Yii::app()->createUrl("/masla/default/view", array("id" => $data["id"]))',	
        			'options' => array('target' => '_blank'),
        		),	
        	),
        ),
    ),
))
?>