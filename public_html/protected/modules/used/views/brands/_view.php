<?php
/* @var $this BrandsController */
/* @var $data UsedBrands */
?>

<div class="view span1">

	<?php echo CHtml::link(CHtml::encode($data->name), array('/used/models/admin', 'UsedModels[brand_id]'=>$data->id)); ?>
	<br>
	<?php echo CHtml::link(CHtml::image("/uploads/brands/".$data->image, 'img', array("style"=>"width:25px;height:25px;")), array('/used/models/admin', 'UsedModels[brand_id]'=>$data->id)); ?>

</div>