<div class="span4">
	<div class="name">
		<?php //echo $data->name; ?>
		<?php echo CHtml::link($data->name, array('/universal/default/view', 'alias' => $razdel->alias, 'id' => $data->id)); ?>
	</div>
	<div class="block">
		<div class="image">
			<?php if ($thumb = $data->getThumb()) { ?>
			<a class="fancybox" href="<?php echo $data->getImage(); ?>" title="<?php echo htmlspecialchars($data->name); ?>"><img src="<?php echo $thumb; ?>"></a>
			<?php } else { ?>
			<img src="/images/nophoto.png" class="nophoto">
			<?php
			} ?>
		</div>
		<div class="price">
			<?php echo CHtml::link(Yii::t('tires', 'Learn price'), (Yii::app()->config->get('Site.SearchType') == 1 ? array('/detailSearchNew/default/search', 'article' => $data->article) : array('/detailSearch/default/search', 'search_phrase' => $data->article)), array('class' => 'btn btn-inverse', 'target' => '_blank')); ?>
		</div>
	</div>
	<div class="chars">
<?php
	foreach ($chars as $char) {
		$value = '';
		
		$char_name = 'chars'.$char->primaryKey;
		
		if (isset($data->{$char_name})) {
			$value = $data->{$char_name};
			
			if ($value) {
				switch ($char->type) {
					case 2:
					case 4:
						$value = $char->getListValue($value);
					break;
					case 6:
						$value = Yii::t('universal', 'Yes');
					break;
				}
			}
		}
		
		if ($value) {
?>
		<div class="char"><div class="lb"><span><?php echo $char->name; ?></span></div><div class="value"><?php echo $value; ?></div></div>
<?php
		}
	}
?>
	</div>
</div>