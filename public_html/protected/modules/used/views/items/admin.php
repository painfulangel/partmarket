<?php
/* @var $this ItemsController */
/* @var $model UsedItems */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Manage Used Items');
$this->breadcrumbs=array(
	'Used Items'=>array('index'),
	'Manage',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#used-items-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo $this->pageTitle;?></h1>


<div class="btn-toolbar">
    <?= CHtml::link(Yii::t(UsedModule::TRANSLATE_PATH, 'Create item'), array('create'), array('class' => 'btn')) ?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'used-items-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'id',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;', 'width' => 50,),
        ),
		array(
            'class' => 'bootstrap.widgets.TbDataColumn',
			'name'=>'brand_id',
			'value'=>'$data->brand->name',
			'filter' => CHtml::listData(UsedBrands::model()->findAll(), 'id','name'),
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
		array(
            'class' => 'bootstrap.widgets.TbDataColumn',
			'name'=>'model_id',
			'value'=>'$data->model->name',
			'filter' => CHtml::listData(UsedModels::model()->findAll(), 'id','name'),
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
		array(
            'class' => 'bootstrap.widgets.TbDataColumn',
			'name'=>'mod_id',
			'value'=>'$data->mod->name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
		array(
            'class' => 'bootstrap.widgets.TbDataColumn',
			'name'=>'node_id',
			'value'=>'$data->node->name',
			'filter' => CHtml::dropDownList('UsedItems[node_id]','', CHtml::listData(UsedNodes::model()->findAll(), 'id','name'),
				array(
					'ajax' => array(
						'type'=>'POST', //request type
						'url'=>CController::createUrl('/used/units/deplist'), //url to call.
						'update'=>'#UsedItems_unit_id', //selector to update
						'data'=>array(
							'node_id'=>'js:this.value',
							Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
						)
						//leave out the data key to pass all form values through
					),
					'empty'=>'')),//CHtml::listData(UsedNodes::model()->findAll(), 'id','name'),
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
		array(
            'class' => 'bootstrap.widgets.TbDataColumn',
			'name'=>'unit_id',
			'value'=>'$data->unit->name',
			'filter' => CHtml::dropDownList('UsedItems[unit_id]','', array()),
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
		array(
			'class' => 'bootstrap.widgets.TbDataColumn',
			'name'=>'brand_item_id',
			'value'=>'$data->brandItem->name',
			'htmlOptions' => array('style' => 'text-align: center;'),
			'headerHtmlOptions' => array('style' => 'text-align: center;'),
		),
		'name',
		'vendor_code',
		'original_num',
		//'replacement',
		array(
            'class' => 'bootstrap.widgets.TbDataColumn',
			'name'=>'type',
			'filter' => $model->getTypes(),
			'value'=>'$data->getType()',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),	
		//'comment',
		'price',
		//'delivery_time',
		'availability',
		//'created_at',
		//'updated_at',
		array(
				'class' => 'bootstrap.widgets.TbDataColumn',
				'name' => 'state',
				'filter' => $model->getStates(),
				'value'=>'$data->getState()',
				'headerHtmlOptions' => array('style' => 'text-align: center;'),
				'htmlOptions' => array('style' => 'text-align: center;'),
			),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{update} {delete}',
		),
	),
)); ?>
