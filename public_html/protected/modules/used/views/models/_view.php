<?php
/* @var $this ModelsController */
/* @var $data UsedModels */
?>

<div class="view span2">

	<?php echo CHtml::link(CHtml::encode($data->name), array('/used/modification/adminMod', 'UsedMod[model_id]'=>$data->id)); ?>
	<br>
	<?php echo CHtml::link(CHtml::image($data->getImageUrl(), 'img', array("style"=>"width:160px;height:120px;")), array('/used/modification/adminMod', 'UsedMod[model_id]'=>$data->id)); ?>

</div>