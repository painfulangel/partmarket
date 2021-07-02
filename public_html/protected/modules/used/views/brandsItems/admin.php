<?php
/* @var $this BrandsItemsController */
/* @var $model UsedBrandsItems */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Manage Brands Items');
$this->breadcrumbs=array(
	'Used Brands Items'=>array('index'),
	'Manage',
);
?>

<h1><?php echo $this->pageTitle;?></h1>

<div class="btn-toolbar">
	<?= CHtml::link(Yii::t(UsedModule::TRANSLATE_PATH, 'Add brand item'), array('create'), array('class' => 'btn')) ?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'used-brands-items-grid',
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
			'name' => 'name',
			'htmlOptions' => array('style' => 'text-align: center;'),
			'headerHtmlOptions' => array('style' => 'text-align: center;'),
		),
		array(
			'name' => 'image',
			'type'=>'html',
			'value'=>'(!empty($data->image))?CHtml::image("/uploads/brands/".$data->image,"",array("style"=>"width:25px;height:25px;")):"no image"',
		),
		'sort',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{update} {delete}',
		),
	),
)); ?>
