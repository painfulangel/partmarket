<?php
/* @var $this ItemsController */
/* @var $data UsedItems */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('brand_id')); ?>:</b>
	<?php echo CHtml::encode($data->brand_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('model_id')); ?>:</b>
	<?php echo CHtml::encode($data->model_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mod_id')); ?>:</b>
	<?php echo CHtml::encode($data->mod_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('node_id')); ?>:</b>
	<?php echo CHtml::encode($data->node_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('unit_id')); ?>:</b>
	<?php echo CHtml::encode($data->unit_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('vendor_code')); ?>:</b>
	<?php echo CHtml::encode($data->vendor_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('original_num')); ?>:</b>
	<?php echo CHtml::encode($data->original_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('replacement')); ?>:</b>
	<?php echo CHtml::encode($data->replacement); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state')); ?>:</b>
	<?php echo CHtml::encode($data->state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delivery_time')); ?>:</b>
	<?php echo CHtml::encode($data->delivery_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('availability')); ?>:</b>
	<?php echo CHtml::encode($data->availability); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
	<?php echo CHtml::encode($data->updated_at); ?>
	<br />

	*/ ?>

</div>