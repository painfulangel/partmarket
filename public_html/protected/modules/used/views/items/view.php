<?php
/* @var $this ItemsController */
/* @var $model UsedItems */

$this->breadcrumbs=array(
	'Used Items'=>array('index'),
	$model->name,
);
?>

<h1><?php echo $model->name; ?></h1>
<!-- photo -->
<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<?php if($model->usedImages):?>
				<?php foreach ($model->usedImages as $image):?>
					<div class="span3">
						<div class="thumbnail">
							<img src="/uploads/items/<?php echo $model->id;?>/<?php echo $image->image;?>">
						</div>
					</div>
				<?php endforeach;?>
			<?php endif;?>
		</div>
	</div>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'Нет данных',
	'htmlOptions'=>array(
		'class'=>'table table-bordered table-condensed'
	),
	'attributes'=>array(
		array(
			'label'=>'Марка авто',
			'type'=>'raw',
			'value'=>$model->brand->name,
		),
		array(
			'label'=>'Модель авто',
			'type'=>'raw',
			'value'=>$model->model->name,
		),
		array(
			'label'=>'Модификация авто',
			'type'=>'raw',
			'value'=>$model->mod->name,
		),
		array(
			'label'=>'Узел',
			'type'=>'raw',
			'value'=>$model->node->name,
		),
		array(
			'label'=>'Агрегат',
			'type'=>'raw',
			'value'=>$model->unit->name,
		),
		'name',
		'vendor_code',
		'original_num',
		'replacement',
		array(
			'name'=>'type',
			'type'=>'raw',
			'value'=>$model->getType(),
		),
		array(
			'name'=>'state',
			'type'=>'raw',
			'value'=>$model->getState(),
		),
		'comment',
		'price',
		'delivery_time',
		'availability',
		array(
			'name'=>'created_at',
			'type'=>'raw',
			'value'=>date('d.m.Y',$model->created_at),
		),
		array(
			'name'=>'updated_at',
			'type'=>'raw',
			'value'=>date('d.m.Y',$model->updated_at),
		),
	),
)); ?>
