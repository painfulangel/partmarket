<?php
/* @var $this NodesController */
/* @var $model UsedNodes */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Manage Used Nodes');
$this->breadcrumbs=array(
	'Used Nodes'=>array('index'),
	'Manage',
);

/*Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#used-nodes-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");*/
?>

<h1><?php echo $this->pageTitle;?></h1>

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t(UsedModule::TRANSLATE_PATH, 'Add node'), array('create'), array('class' => 'btn')) ?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'used-nodes-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'sort',
		array(
			'header' => 'Сортировка',
			'name' => 'sort',
			'class' => 'ext.OrderColumn.OrderColumn',
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{update} {delete}',
		),
	),
)); ?>
