<?php
/* @var $this BrandsController */
/* @var $model UsedBrands */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Manage Used Brands');
$this->breadcrumbs=array(
	Yii::t(UsedModule::TRANSLATE_PATH, 'Used Brands')=>array('admin'),
	Yii::t(UsedModule::TRANSLATE_PATH, 'Manage'),
);
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php echo CHtml::link(
	Yii::t(UsedModule::TRANSLATE_PATH, 'Create'),
	array('/used/brands/create'),
	array(
		'class' => 'btn btn-success',
	));?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'used-brands-grid',
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
                'class' => 'bootstrap.widgets.TbDataColumn',
                'name' => 'title',
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
                'class' => 'bootstrap.widgets.TbDataColumn',
                'name' => 'keywords',
                'htmlOptions' => array('style' => 'text-align: center;'),
                'headerHtmlOptions' => array('style' => 'text-align: center;'),
            ),
			array(
				'name' => 'image',
				'type'=>'html',
				'value'=>'(!empty($data->image))?CHtml::image("/uploads/brands/".$data->image,"",array("style"=>"width:25px;height:25px;")):"no image"',
			),
			array(
				'class' => 'ext.jtogglecolumn.JToggleColumn',
				'name' => 'status',
				'filter' => array('0' => Yii::t(UsedModule::TRANSLATE_PATH, 'No'), '1' => Yii::t(UsedModule::TRANSLATE_PATH, 'Yes')),
				'checkedButtonLabel' => Yii::t(UsedModule::TRANSLATE_PATH, 'Disable'),
				'uncheckedButtonLabel' => Yii::t(UsedModule::TRANSLATE_PATH, 'Enable'),
				'headerHtmlOptions' => array('style' => 'text-align: center;'),
				'htmlOptions' => array('style' => 'text-align: center;'),
			),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
				'template' => '{update} {delete}',
            ),
	),
)); ?>
