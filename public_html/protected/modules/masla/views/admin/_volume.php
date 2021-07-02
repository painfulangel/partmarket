<div class="volumes">
<?php
$volumes = $model->volumes;
if (is_array($volumes) && ($count = count($volumes))) {
		for ($i = 0; $i < $count; $i ++) {
?>
		<div class="block">
			<input type="hidden" name="volume_id[]" value="<?php echo $volumes[$i]->primaryKey; ?>">
			<input type="text" name="volume[<?php echo $volumes[$i]->primaryKey; ?>]" value="<?php echo $volumes[$i]->volume; ?>"> <?php echo Yii::t('masla', 'l'); ?>
			<div class="file">
				<div class="field"><input type="file" name="image[<?php echo $volumes[$i]->primaryKey; ?>]"></div>
<?php
			if ($thumb = $volumes[$i]->getThumb(100)) {
?>
				<div class="image">
					<img src="<?php echo $thumb; ?>">
					<div>
						<input type="checkbox" name="delete_image[]" value="<?php echo $volumes[$i]->primaryKey; ?>" id="delete_image_<?php echo $i; ?>">
						<label for="delete_image_<?php echo $i; ?>"><?php echo Yii::t('masla', 'Delete picture'); ?></label>
					</div>
				</div>
<?php
			}
?>
			</div>
			<input type="checkbox" name="delete_volume[]" value="<?php echo $volumes[$i]->primaryKey; ?>" id="delete_volume_<?php echo $i; ?>">
			<label for="delete_volume_<?php echo $i; ?>"><?php echo Yii::t('masla', 'Delete volume'); ?></label>
		</div>
<?php
		}
}
?>
</div>
<?php
$this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'button',
	'type' => 'info',
	'label' => Yii::t('masla', 'Add volume'),
	'htmlOptions' => array('onclick' => 'addVolume();'),
));
?>
<script type="text/javascript">
	function addVolume() {
		var html = '<div class="block">' + 
				   '<input type="text" name="volume[]"> <?php echo Yii::t('masla', 'l'); ?>' + 
				   '<div class="file">' + 
				   '<div class="field"><input type="file" name="image[]"></div>' + 
				   '</div>' + 
				   '</div>';
		
		$('div.volumes').append(html);
	}
</script>

<style type="text/css">
div.volumes {
	overflow: hidden;
}

div.block {
	margin: 0px 10px 20px 0px;
	border: 1px solid #ccc;
	border-radius: 3px;
	float: left;
	padding: 10px;
	width: 400px;
}

div.block .file {
	margin-top: 10px;
	overflow: hidden;
}

div.block .file .field {
	float: left;
}

div.block .file .image {
	float: left;
	min-width: 100px;
}

div.block .file .image img {
	max-width: 150px;
}

div.block label {
	display: inline;
}

div.block input[type="checkbox"] {
	margin-top: 0px !important;
}
</style>