<h3><?php echo $model->name; ?></h3>
<?php if (!empty($model->image)) { ?>
	<?php echo CHtml::image('/'.$model->getImage('big'), $model->name, array('class' => 'brand-image')) ?>
<?php } ?>
<div>
	<?php echo $model->description; ?>
</div>
<style type="text/css">
	.brand-image {
		float: left;
		margin-right: 20px;
		max-height: 150px;
	}
</style>