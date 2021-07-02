<div class="span4">
	<div class="name">
		<?php //echo $data->name; ?>
		<?php echo CHtml::link($data->name, array('/masla/default/view', 'id' => $data->id)); ?>
	</div>
	<div class="block">
		<div class="image">
<?php
			$image = false;
			
			$volumes = $data->volumes;
			if (is_array($volumes) && count($volumes)) {
				if ($thumb = $volumes[0]->getThumb()) {
					$image = true;
?>
			<a class="fancybox" href="<?php echo $volumes[0]->getImage(); ?>" title="<?php echo htmlspecialchars($data->name); ?>"><img src="<?php echo $thumb; ?>"></a>
<?php
				}
			}
			
			if (!$image) {
?>
			<img src="/images/nophoto.png" class="nophoto">
<?php
			}
?>
		</div>
		<div class="price">
			<?php echo CHtml::link(Yii::t('masla', 'Learn price'), (Yii::app()->config->get('Site.SearchType') == 1 ? array('/detailSearchNew/default/search', 'article' => $data->article) : array('/detailSearch/default/search', 'search_phrase' => $data->article)), array('class' => 'btn btn-inverse', 'target' => '_blank')); ?>
		</div>
	</div>
	<div class="chars">
<?php
	if (is_array($volumes) && ($count = count($volumes))) {
		$vs = array();
		for ($i = 0; $i < $count; $i ++) {
			$vs[] = $volumes[$i]->volume.Yii::t('masla', 'l');
		}
?>
	<div class="char"><div class="lb"><span><?php echo Yii::t('masla', 'Volume'); ?></span></div><div class="value"><?php echo implode('/', $vs); ?></div></div>
<?php
	}
?>
	<?php if (is_object($data->countryObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo $labels['country']; ?></span></div><div class="value"><?php echo $data->countryObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->producerObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo $labels['producer']; ?></span></div><div class="value"><?php echo $data->producerObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->scopeObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo $labels['scope']; ?></span></div><div class="value"><?php echo $data->scopeObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->saeObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo $labels['sae']; ?></span></div><div class="value"><?php echo $data->saeObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->engineTypeObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo $labels['engine_type']; ?></span></div><div class="value"><?php echo $data->engineTypeObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->oilTypeObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo $labels['oil_type']; ?></span></div><div class="value"><?php echo $data->oilTypeObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->apiObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo $labels['api']; ?></span></div><div class="value"><?php echo $data->apiObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->ilsacObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo $labels['ilsac']; ?></span></div><div class="value"><?php echo $data->ilsacObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->isoObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo $labels['iso']; ?></span></div><div class="value"><?php echo $data->isoObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->aceaObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo $labels['acea']; ?></span></div><div class="value"><?php echo $data->aceaObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->jasoObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo $labels['jaso']; ?></span></div><div class="value"><?php echo $data->jasoObject->value; ?></div></div>
	<?php } ?>
	</div>
</div>