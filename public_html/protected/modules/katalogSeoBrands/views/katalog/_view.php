<div class="span2<?php if (!$data->main) { ?> hide<?php } ?>">
	<div class="name">
		<?php echo CHtml::link($data->brand, '/'.$url.'/'.str_replace('/', '__', $data->brand).'/'); ?>
	</div>
</div>