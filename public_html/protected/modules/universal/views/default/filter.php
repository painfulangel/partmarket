<?php
	//For slider
	Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
	Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl().
			'/jui/css/base/jquery-ui.css'
	);
	//For slider
	
	$this->breadcrumbs = array(
		$razdel->name => array('/universal/default/index', 'alias' => $razdel->alias),
		Yii::t('universal', 'All characteristics'),
	);
	
	$this->pageTitle = Yii::t('universal', 'All characteristics');
?>
		<form method="get" action="<?php echo $this->createUrl('/universal/default/index', array('alias' => $razdel->alias)); ?>">
<?php
			$chars1 = array();
			$chars2 = array();
			$i = 0;
			
			foreach ($filter_chars as $char) {
				$name = 'chars'.$char->primaryKey;
				
				ob_start();
?>
			<div class="tires-filter-block full-filter-block">
				<h5 rel="<?php echo $char->primaryKey; ?>"><?php echo $char->name; ?></h5>
				<div class="tires-filter-content" rel="<?php echo $char->primaryKey; ?>">
<?php
				switch ($char->type) {
					case 1:
?>
					<input type="text" name="<?php echo $name; ?>">
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
								echo '<option value="'.$key.'">'.$value.'</option>';
							}
?>
					</select>
<?php
						} else {
							foreach ($values as $key => $value) {
?>
					<div class="tires-filter-checkbox span6" rel="<?php echo $char->primaryKey; ?>">
						<input type="checkbox" name="<?php echo $name; ?>[]" id="chars<?php echo $key; ?>" value="<?php echo $key; ?>">
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
					      values: [<?php echo $minLoadIndex; ?>, <?php echo $maxLoadIndex; ?>],
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
								<input name="<?php echo $name; ?>" id="chars<?php echo $char->primaryKey; ?>1" value="1" type="radio"> <label for="chars<?php echo $char->primaryKey; ?>1"><?php echo Yii::t('universal', 'Yes'); ?></label>
							</div>
							<div class="tires-filter-checkbox span6" rel="8">
								<input name="<?php echo $name; ?>" id="chars<?php echo $char->primaryKey; ?>2" value="2" type="radio"> <label for="chars<?php echo $char->primaryKey; ?>2"><?php echo Yii::t('universal', 'No'); ?></label>
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
				if ($i % 2 == 0)
					$chars1[] = ob_get_clean();
				else
					$chars2[] = ob_get_clean();
				
				$i ++;
			}
?>
			<div class="filter-column span6"><?php echo implode('', $chars1); ?></div>
			<div class="filter-column span6"><?php echo implode('', $chars2); ?></div>
<?php
			
			echo CHtml::submitButton(Yii::t('universal', 'Show'), array('class' => 'btn btn-primary'));
?>
		</form>
		<div style="clear: both;"></div>