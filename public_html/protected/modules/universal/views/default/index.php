<?php
	//For slider
	Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
	Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl().
			'/jui/css/base/jquery-ui.css'
	);
	//For slider
	
	$this->widget('ext.jquery_fancybox.FancyboxWidget');

	$this->metaDescription = $razdel->meta_description ? $razdel->meta_description : $razdel->name;
	$this->metaKeywords = $razdel->meta_keywords ? $razdel->meta_keywords : $razdel->name;
	$this->pageTitle = $razdel->meta_title ? $razdel->meta_title : $razdel->name;
	
	$this->breadcrumbs = array($razdel->name);
?>
<h1><?php echo $razdel->name; ?></h1>
<div class="span12 tires-block">
	<div class="span3 tires-filter">
		<form method="get">
<?php
			foreach ($filter_chars as $char) {
				$name = 'chars'.$char->primaryKey;
?>
			<div class="tires-filter-block">
				<h5 rel="<?php echo $char->primaryKey; ?>"><?php echo $char->name; ?></h5>
				<div class="tires-filter-content" rel="<?php echo $char->primaryKey; ?>">
<?php
				switch ($char->type) {
					case 1:
?>
					<input type="text" name="<?php echo $name; ?>" value="<?php if (array_key_exists($name, $filter_values)) echo $filter_values[$name]; ?>">
<?php
					break;
					case 2:
					case 4:
						$values = $char->getValues();
						
						if ($char->filter_view == 1) {
?>
					<select name="<?php echo $name; ?>">
						<option value="0"><?php echo Yii::t('universal', 'Choose'); ?></option>
<?php
							foreach ($values as $key => $value) {
								$selected = array_key_exists($name, $filter_values) && ($filter_values[$name] == $key) ? ' selected' : '';
								
								echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
							}
?>
					</select>
<?php
						} else {
							foreach ($values as $key => $value) {
								$checked = array_key_exists($name, $filter_values) && in_array($key, $filter_values[$name]) ? ' checked' : '';
?>
					<div class="tires-filter-checkbox span6" rel="<?php echo $char->primaryKey; ?>">
						<input type="checkbox" name="<?php echo $name; ?>[]" id="chars<?php echo $key; ?>" value="<?php echo $key; ?>"<?php echo $checked; ?>>
						<label for="chars<?php echo $key; ?>"><?php echo $value; ?></label>
					</div>
<?php
							}
						}
					break;
					case 5:
						$minLoadIndex = $char->min;
						$maxLoadIndex = $char->max;
						
						$load_index_min = 'chars'.$char->primaryKey.'min';
						$load_index_max = 'chars'.$char->primaryKey.'max';
						
						$amountMin = 'amount'.$char->primaryKey.'min';
						$amountMax = 'amount'.$char->primaryKey.'max';
?>
					<p>
					  <input type="text" id="<?php echo $amountMin; ?>" style="width: 50px;" readonly> - <input type="text" id="<?php echo $amountMax; ?>" style="width: 50px;" readonly>
					  <input type="hidden" name="<?php echo $load_index_min; ?>">
					  <input type="hidden" name="<?php echo $load_index_max; ?>">
					</p>
					<div id="slider-range"></div>
					<script type="text/javascript">
					  $(function() {
					    $("#slider-range").slider({
					      range: true,
					      min: <?php echo $minLoadIndex; ?>,
					      max: <?php echo $maxLoadIndex; ?>,
					      values: [<?php echo array_key_exists($load_index_min, $filter_values) ? $filter_values[$load_index_min] : $minLoadIndex; ?>, <?php echo array_key_exists($load_index_max, $filter_values) ? $filter_values[$load_index_max] : $maxLoadIndex; ?>],
					      slide: function(event, ui) {
					      	$('#<?php echo $amountMin; ?>').val(ui.values[0]);
					      	$(':hidden[name=<?php echo $load_index_min; ?>]').val(ui.values[0]);
					      	
					  	    $('#<?php echo $amountMax; ?>').val(ui.values[1]);
					      	$(':hidden[name=<?php echo $load_index_max; ?>]').val(ui.values[1]);
					      }
					    });
					    
					    $("#<?php echo $amountMin; ?>").val($("#slider-range").slider("values", 0));
					    $("#<?php echo $amountMax; ?>").val($("#slider-range").slider("values", 1));
					  });
					  </script>
<?php
					break;
					case 6:
?>
					<div class="span12" id="tires-filter-row">
						<div class="items">
							<div class="tires-filter-checkbox span6" rel="8">
								<input name="<?php echo $name; ?>" id="chars<?php echo $char->primaryKey; ?>1" value="1" type="radio"<?php if (array_key_exists($name, $filter_values) && ($filter_values[$name] == 1)) echo ' checked'; ?>> <label for="chars<?php echo $char->primaryKey; ?>1"><?php echo Yii::t('universal', 'Yes'); ?></label>
							</div>
							<div class="tires-filter-checkbox span6" rel="8">
								<input name="<?php echo $name; ?>" id="chars<?php echo $char->primaryKey; ?>2" value="2" type="radio"<?php if (array_key_exists($name, $filter_values) && ($filter_values[$name] == 2)) echo ' checked'; ?>> <label for="chars<?php echo $char->primaryKey; ?>2"><?php echo Yii::t('universal', 'No'); ?></label>
							</div>
						</div>
					</div>
<?php
					break;
				}
				//echo $char->name.'<br>';
?>
				</div>
			</div>
<?php
			}
			
			echo CHtml::link(Yii::t('universal', 'All characteristics'), array('filter', 'alias' => $razdel->alias), array('class' => 'all'));
			
			echo CHtml::submitButton(Yii::t('universal', 'Show'), array('class' => 'btn btn-primary'));
?>
		</form>
	</div>
<?php
	$this->widget('bootstrap.widgets.TbListView', array(
		'dataProvider' => $dataProvider,
		'viewData' => array('chars' => $chars, 'razdel' => $razdel),
		'itemView' => '_view',
		'template' => '{items} {pager}',
		'id' => 'universal',
		'htmlOptions' => array('class' => 'span9'),
	));
?>
</div>
<div style="clear: both;"></div>
<script type="text/javascript">
$(function() {
	$("#universal a.fancybox").fancybox();
});
</script>