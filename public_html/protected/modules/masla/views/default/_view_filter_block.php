<?php
	$closed = 0;
	$count = count($properties);
	for ($i = 0; $i < $count; $i ++) {
		if ($properties[$i]->primaryKey == $p['id']) {
			$closed = $properties[$i]->filter_closed;
			
			break;
		}
	}
?>
<div class="tires-filter-block">
	<h5 rel="<?php echo $p['id']; ?>"><?php echo $p['name']; ?><span class="ch<?php if ($closed) { ?> ch2<?php } ?>"></span></h5>
	<div class="tires-filter-content" rel="<?php echo $p['id']; ?>"<?php if ($closed) { ?> style="display: none;"<?php } ?>>
	
	<div class="switcher_wrap">
		<div class="item_switch show_popular act_switch" rel="<?php echo $p['id']; ?>">
			<span><?php echo Yii::t('masla', 'Popular'); ?></span>
		</div>
		<div class="item_switch show_all" rel="<?php echo $p['id']; ?>">
			<span><?php echo Yii::t('masla', 'All'); ?></span>
		</div>
	</div>
<?php
	$this->widget('bootstrap.widgets.TbListView', array(
		'dataProvider' => $p['dp'],
		'itemView' => '_view_filter_row',
		'template' => '{items} {pager}',
		'id' => 'tires-filter-row',
		'htmlOptions' => array('class' => 'span12'),
	));
?>
	</div>
</div>