<?php
/* @var $this BrandsController */
/* @var $data UsedBrands */
?>

<div class="span4" style="margin-bottom: 10px;">
	<?php echo CHtml::link(CHtml::image("/uploads/brands/".$data->image, 'img', array("style"=>"width:25px;height:25px;margin-right:15px;")), array('/used/cars/brand', 'brand'=>$data->slug)); ?>

	<?php echo CHtml::link(CHtml::encode($data->name), array('/used/cars/brand', 'brand'=>$data->slug), array('style'=>'text-transform:uppercase;')); ?>
</div>
<?php $c = $index+1;?>
<?php if($c%3 == 0):?>
	</div>
	<div class="row-fluid clear">
<?php endif;?>
