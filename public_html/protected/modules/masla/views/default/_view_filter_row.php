<?php
	$filter = Yii::app()->request->getQuery('filter', array());
	$checked = array_key_exists($data->id_chars, $filter) && is_array($filter[$data->id_chars]) && in_array($data->primaryKey, $filter[$data->id_chars]);
?>
<div class="masla-filter-checkbox span6<?php if ($data->popular == 0 && !$checked) { ?> filter-hidden check-hidden<?php } ?>" rel="<?php echo $data->id_chars; ?>">
	<input type="checkbox" name="filter[<?php echo $data->id_chars; ?>][]" id="popular_brand_<?php echo $data->primaryKey; ?>" value="<?php echo $data->primaryKey; ?>"<?php if ($checked) { ?> checked<?php } ?>>
	<label for="popular_brand_<?php echo $data->primaryKey; ?>"><?php echo $data->value; ?></label>
</div>