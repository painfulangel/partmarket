<div class="tires-filter-checkbox span6<?php if ($data->popular == 0) { ?> filter-hidden check-hidden<?php } ?>" rel="<?php echo $data->id_property; ?>">
	<input type="checkbox" name="brand[]" id="popular_brand_<?php echo $data->primaryKey; ?>" value="<?php echo $data->primaryKey; ?>"><label for="popular_brand_<?php echo $data->primaryKey; ?>"><?php echo $data->value; ?></label>
</div>