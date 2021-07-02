<?php
/* @var $this UnitsController */
/* @var $model UsedUnits */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Manage Used Units');
$this->breadcrumbs=array(
	'Used Units'=>array('index'),
	'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#used-units-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo $this->pageTitle;?></h1>


<?php echo CHtml::link(Yii::t(UsedModule::TRANSLATE_PATH,'Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t(UsedModule::TRANSLATE_PATH, 'Add unit'), array('create'), array('class' => 'btn')) ?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'used-units-grid',
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
            'header' => 'Узел',
			'name'=>'node_id',
			'value'=>'$data->node->name',
            'htmlOptions' => array('style' => 'text-align: center;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
			'sortable'=>false,
        ),
		'name',
		'sort',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{update} {delete}',
		),
	),
)); ?>
