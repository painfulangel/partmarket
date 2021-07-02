<?php
/* @var $this ModelsController */
/* @var $model UsedModels */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Manage Used Models');
$this->breadcrumbs=array(
	Yii::t(UsedModule::TRANSLATE_PATH, 'Used catalog') => array('/used/admin'),
	$brand->name,
);
?>
<div class="container">
<h1><?php echo $brand->name; //echo  Yii::t(UsedModule::TRANSLATE_PATH, 'Manage Used Models');?></h1>
	<div class="row">
		<div class="span6">
			<?php $this->widget('zii.widgets.CListView', array(
				'id'=>'model-list-view',
				'dataProvider'=>$model->search(),
				'itemView'=>'_view',
			)); ?>
		</div>
		<div class="span7">
			<div class="btn-toolbar">
				<?php echo CHtml::ajaxLink(
					Yii::t(UsedModule::TRANSLATE_PATH, 'Add model'),
					array('/used/models/createMain', 'brand_id'=>$model->brand_id),
					array(
						'update'=>'#load-form',
					),
					array(
						'class' => 'btn btn-success',
					));?>
			</div>
			<div class="" id="load-form"></div>
		</div>
	</div>

	<div style="height: 60px;"></div>

</div>
