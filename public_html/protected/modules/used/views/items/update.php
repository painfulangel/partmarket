<?php
/* @var $this ItemsController */
/* @var $model UsedItems */
$this->pageTitle = 'Редактирование детали: '.$model->name;
$this->breadcrumbs=array(
	Yii::t(UsedModule::TRANSLATE_PATH, 'Used Items')=>array('/used/items/admin'),
	$model->name=>array('view','id'=>$model->id),
	Yii::t(UsedModule::TRANSLATE_PATH, 'Update'),
);

?>

<h1>
	<?php echo $this->pageTitle; ?>
	<small>
		<a class="btn btn-danger btn-small" href="/used/items/delete/id/<?php echo $model->id;?>">
			<i class="icon-remove icon-white"></i> Удалить
		</a>
	</small>
</h1>

<?php $this->renderPartial('_formUpdate', array(
	'model'=>$model,
	'mod'=>$mod,
	)); ?>