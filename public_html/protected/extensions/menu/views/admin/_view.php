<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('order')); ?>:</b>
	<?php echo CHtml::encode($data->order); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('menu_type')); ?>:</b>
	<?php echo CHtml::encode($data->menu_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('menu_value')); ?>:</b>
	<?php echo CHtml::encode($data->menu_value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('echo_position')); ?>:</b>
	<?php echo CHtml::encode($data->echo_position); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('visible')); ?>:</b>
	<?php echo CHtml::encode($data->visible); ?>
	<br />


</div>