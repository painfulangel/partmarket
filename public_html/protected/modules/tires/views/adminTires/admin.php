<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('tires', 'Tires catalog')));

$this->pageTitle = Yii::t('tires', 'Tires catalog');

$this->admin_subheader = array(
    array(
        'name' => Yii::t('tires', 'Tires catalog'),
        'url' => array('/tires/adminTires/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('tires', 'Tires property'),
        'url' => array('/tires/adminTiresProperty/admin'),
        'active' => false,
    ),
);
?>
<h1><?php echo Yii::t('tires', 'Tires catalog'); ?></h1>
<?php /**/ ?>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('tires', 'Create'), array('create'), array('class' => 'btn')); ?>
</div>
<?php
/**/
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
            'name' => 'type',
        	'filter' => TiresPropertyValues::selectList(1),
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        	'value' => 'is_object($data->typeObject) ? $data->typeObject->value : ""'
        ),
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
            'name' => 'producer',
        	'filter' => TiresPropertyValues::selectList(2),
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        	'value' => 'is_object($data->producerObject) ? $data->producerObject->value : ""'
        ),
        /*array(
            'name' => 'slug',
            'headerHtmlOptions' => array(
                'width' => 200,
            ),
        ),
		array(
            'name' => 'order',
            'class' => 'ext.OrderColumn.OrderColumn',
            'htmlOptions' => array('style' => 'min-width: 25px;'),
        ),*/
        array(
            'class' => 'ext.AttachmentBehavior.TbEImageColumn',
            'name' => 'image',
            'sortable' => true,
            'filter' => array('1' => Yii::t('tires', 'Yes'), '2' => Yii::t('tires', 'No')),
            'noFileFound' => '/images/nofoto.png',
            'htmlOptions' => array('style' => 'max-width: 60px; max-height: 60px; margin: 5px;'),
            'headerHtmlOptions' => array('style' => 'text-align:center; vertical-align;'),
        ),
    	array(
    		'class' => 'ext.jtogglecolumn.JToggleColumn',
    		'name' => 'active_state',
    		'filter' => array('0' => Yii::t('tires', 'No'), '1' => Yii::t('tires', 'Yes')),
    		'checkedButtonLabel' => Yii::t('tires', 'Disable'),
    		'uncheckedButtonLabel' => Yii::t('tires', 'Enable'),
    		'headerHtmlOptions' => array('style' => 'text-align: center;'),
    		'htmlOptions' => array('style' => 'text-align: center;'),
    	),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view} {update} {delete}',
        	'buttons' => array(
        		'view' => array(
        			'url' => 'Yii::app()->createUrl("/tires/default/view", array("id" => $data["id"]))',	
        			'options' => array('target' => '_blank'),
        		),	
        	),
        ),
    ),
))
?>