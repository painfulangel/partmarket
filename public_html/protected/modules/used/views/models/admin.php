<?php
/* @var $this ModelsController */
/* @var $model UsedModels */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Manage Used Models');
$this->breadcrumbs=array(
	'Used Models'=>array('index'),
	'Manage',
);
?>
<div class="container">

	<h1><?php echo  Yii::t(UsedModule::TRANSLATE_PATH, 'Manage Used Models');?></h1>

	<?php echo CHtml::link(
		Yii::t(UsedModule::TRANSLATE_PATH, 'Add model'),
		array('create'),
		array(
			'class' => 'btn btn-success',
		));?>


		<?php $this->widget('bootstrap.widgets.TbGridView', array(
			'id'=>'used-models-grid',
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
					'header' => 'Бренд',
					'name'=>'brand.name',
					'htmlOptions' => array('style' => 'text-align: center;'),
					'headerHtmlOptions' => array('style' => 'text-align: center;'),
				),
				array(
					'class' => 'bootstrap.widgets.TbDataColumn',
					'name' => 'name',
					'htmlOptions' => array('style' => 'text-align: center;'),
					'headerHtmlOptions' => array('style' => 'text-align: center;'),
				),
				array(
					'class' => 'bootstrap.widgets.TbDataColumn',
					'name' => 'slug',
					'htmlOptions' => array('style' => 'text-align: center;'),
					'headerHtmlOptions' => array('style' => 'text-align: center;'),
				),
				array(
					'name' => 'image',
					'type'=>'html',
					'value'=>'(!empty($data->image))?CHtml::image("/uploads/models/".$data->image,"",array("style"=>"width:25px;height:25px;")):"no image"',
				),
				//'sort',

				array(
					'class'=>'bootstrap.widgets.TbButtonColumn',
					'template' => '{update} {delete}',
				),
			),
		)); ?>
</div>
