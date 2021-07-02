<?php
/* @var $this ItemSetsController */
/* @var $model UsedItemSets */

$this->breadcrumbs=array(
	'Used Item Sets'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List UsedItemSets', 'url'=>array('index')),
	array('label'=>'Create UsedItemSets', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#used-item-sets-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Used Item Sets</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'used-item-sets-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'item_id',
		'name',
		'vendor_code',
		'original_num',
		'replacement',
		/*
		'type',
		'state',
		'comment',
		'price',
		'delivery_time',
		'availability',
		'created_at',
		'updated_at',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
