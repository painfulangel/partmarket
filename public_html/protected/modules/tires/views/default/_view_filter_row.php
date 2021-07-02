<?php
	$filter = Yii::app()->request->getQuery('filter', array());
	$checked = array_key_exists($data->id_property, $filter) && is_array($filter[$data->id_property]) && in_array($data->primaryKey, $filter[$data->id_property]);
?>
<div class="tires-filter-checkbox span6<?php if ($data->popular == 0 && !$checked) { ?> filter-hidden check-hidden<?php } ?>" rel="<?php echo $data->id_property; ?>">
	<input type="checkbox" name="filter[<?php echo $data->id_property; ?>][]" id="popular_brand_<?php echo $data->primaryKey; ?>" value="<?php echo $data->primaryKey; ?>"<?php if ($checked) { ?> checked<?php } ?>>
	<label for="popular_brand_<?php echo $data->primaryKey; ?>"><?php echo $data->value; ?></label>
</div>