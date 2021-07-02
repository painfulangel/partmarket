<div class="span4">
	<div class="name">
		<?php //echo $data->name; ?>
		<?php echo CHtml::link($data->name, array('/tires/default/view', 'id' => $data->id)); ?>
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
	<?php if (is_object($data->typeObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo Yii::t('tires', 'Type'); ?></span></div><div class="value"><?php echo $data->typeObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->producerObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo Yii::t('tires', 'Producer'); ?></span></div><div class="value"><?php echo $data->producerObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->widthObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo Yii::t('tires', 'Width'); ?></span></div><div class="value"><?php echo $data->widthObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->heightObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo Yii::t('tires', 'Height'); ?></span></div><div class="value"><?php echo $data->heightObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->diameterObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo Yii::t('tires', 'Diameter'); ?></span></div><div class="value"><?php echo $data->diameterObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->seasonalityObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo Yii::t('tires', 'Seasonality'); ?></span></div><div class="value"><?php echo $data->seasonalityObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->speedIndexObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo Yii::t('tires', 'Speed index'); ?></span></div><div class="value"><?php echo $data->speedIndexObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->shippObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo Yii::t('tires', 'Shipp'); ?></span></div><div class="value"><?php echo $data->shippObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->loadIndexObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo Yii::t('tires', 'Load index'); ?></span></div><div class="value"><?php echo $data->loadIndexObject->value; ?></div></div>
	<?php } ?>
	<?php if (is_object($data->axisObject)) { ?>
	<div class="char"><div class="lb"><span><?php echo Yii::t('tires', 'Axis'); ?></span></div><div class="value"><?php echo $data->axisObject->value; ?></div></div>
	<?php } ?>
	</div>
</div>