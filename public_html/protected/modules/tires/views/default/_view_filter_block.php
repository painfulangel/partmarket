<?php
	$closed = 0;
	$count = count($properties);
	for ($i = 0; $i < $count; $i ++) {
		if ($properties[$i]->primaryKey == $id_property) {
			$closed = $properties[$i]->closed;
			
			break;
		}
	}
?>
<div class="tires-filter-block">
	<h5 rel="<?php echo $id_property; ?>"><?php echo $name_property; ?><span class="ch<?php if ($closed) { ?> ch2<?php } ?>"></span></h5>
	<div class="tires-filter-content" rel="<?php echo $id_property; ?>"<?php if ($closed) { ?> style="display: none;"<?php } ?>>
<?php
	if ($id_property == 9) {
		$load_index_min = Yii::app()->request->getQuery('load_index_min', 0);
		$load_index_max = Yii::app()->request->getQuery('load_index_max', 0);
?>
	<p>
	  <input type="text" id="amountMin" style="width: 50px;" readonly> - <input type="text" id="amountMax" style="width: 50px;" readonly>
	  <input type="hidden" name="load_index_min">
	  <input type="hidden" name="load_index_max">
	</p>
	<div id="slider-range"></div>
	<script type="text/javascript">
	  $(function() {
	    $("#slider-range").slider({
	      range: true,
	      min: <?php echo $minLoadIndex; ?>,
	      max: <?php echo $maxLoadIndex; ?>,
	      values: [<?php echo $load_index_min ? $load_index_min : $minLoadIndex; ?>, <?php echo $load_index_max ? $load_index_max : $maxLoadIndex; ?>],
	      slide: function(event, ui) {
	      	$('#amountMin').val(ui.values[0]);
	      	$(':hidden[name=load_index_min]').val(ui.values[0]);
	      	
	  	    $('#amountMax').val(ui.values[1]);
	      	$(':hidden[name=load_index_max]').val(ui.values[1]);
	      }
	    });
	    
	    $("#amountMin").val($("#slider-range").slider("values", 0));
	    $("#amountMax").val($("#slider-range").slider("values", 1));
	  });
	  </script>
<?php
	} else {
?>
	<div class="switcher_wrap">
		<div class="item_switch show_popular act_switch" rel="<?php echo $id_property; ?>">
			<span><?php echo Yii::t('tires', 'Popular'); ?></span>
		</div>
		<div class="item_switch show_all" rel="<?php echo $id_property; ?>">
			<span><?php echo Yii::t('tires', 'All'); ?></span>
		</div>
	</div>
<?php
	$this->widget('bootstrap.widgets.TbListView', array(
		'dataProvider' => $dataProvider,
		'itemView' => '_view_filter_row',
		'template' => '{items} {pager}',
		'id' => 'tires-filter-row',
		'htmlOptions' => array('class' => 'span12'),
	));

	}
?>
	</div>
</div>