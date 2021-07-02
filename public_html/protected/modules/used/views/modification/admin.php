<?php
/* @var $this ModificationController */
/* @var $model UsedMod */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Manage Used Modifications');
$this->breadcrumbs=array(
	'Used Mods'=>array('index'),
	'Manage',
);

?>


<h1><?php echo $this->pageTitle;?></h1>

<?php echo CHtml::link(Yii::t(UsedModule::TRANSLATE_PATH,'Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<div class="btn-toolbar">
    <?= CHtml::link(Yii::t(UsedModule::TRANSLATE_PATH, 'Add modification'), array('create'), array('class' => 'btn')) ?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'used-mod-grid',
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
            'header' => 'Модель',
			'name'=>'model.name',
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
