<?php
/* @var $this BrandsController */
/* @var $data UsedBrands */
?>
<div class="view" style="padding: 5px 15px;float: left;">

	<?php echo CHtml::ajaxLink(
		CHtml::encode($data->name),
		array('/used/modification/applicat', 'brand_id'=>$data->id),
		array(
			//'update'=>'#addition-modification',
			'complete'=>'function(data) {
			  	$("#addition-modification").append(data.responseText);
			}',
		),
		array('id'=>uniqid())
	); ?>
	<br>
	<?php //echo CHtml::link(CHtml::image("/uploads/brands/".$data->image, 'img', array("style"=>"width:25px;height:25px;")), array('/used/models/adminModels', 'UsedModels[brand_id]'=>$data->id)); ?>

</div>