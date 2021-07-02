<?php
	$this->breadcrumbs = array(
	    Yii::t('masla', 'Oil catalog') => array('/masla/default/index'),
	    $model->name,
	);
	
	$default = '';
	if ($model->producerObject) $default = $model->producerObject->value.' ';
	$default .= $model->article;

	$meta_description = trim($model->meta_description);
	if ($meta_description == '') $meta_description = $default;
	
	$meta_keywords = trim($model->meta_keywords);
	if ($meta_keywords == '') $meta_keywords = $default;
	
	$meta_title = trim($model->meta_title);
	if ($meta_title == '') $meta_title = $default;

	$this->metaDescription = $meta_description;
	$this->metaKeywords = $meta_keywords;
	$this->pageTitle = $meta_title;
	
	$this->widget('ext.jquery_fancybox.FancyboxWidget');
?>
<div class="span12">
	<div class="span12" style="margin: 20px 0px;">
		<div class="image span3">
<?php
			$image = false;
			
			$volumes = $model->volumes;
			if (is_array($volumes) && ($count = count($volumes))) {
?>
			<div class="volume">
				<div><?php echo Yii::t('masla', 'Volume'); ?></div>
<?php
				for ($i = 0; $i < $count; $i ++) {
?>
				<a<?php if ($i == 0) { ?> class="active"<?php } ?> rel="img<?php echo $i; ?>"><?php echo $volumes[$i]->volume.Yii::t('masla', 'l'); ?></a>
<?php
				}
?>
			</div>
<?php
				for ($i = 0; $i < $count; $i ++) {
					if ($thumb = $volumes[$i]->getThumb(400)) {
						$image = true;
?>
			<a class="fancybox<?php if ($i != 0) { ?> ntvs<?php } ?>" href="<?php echo $volumes[$i]->getImage(); ?>" title="<?php echo htmlspecialchars($model->name); ?>" rel="img<?php echo $i; ?>"><img src="<?php echo $thumb; ?>"></a>
<?php
					}
				}
			}
			
			if (!$image) {
?>
			<img src="/images/nophoto.png" class="nophoto">
<?php
			}
?>
		</div>
		<div class="text span6">
			<h1><?php echo $default; ?></h1>
			<h5><?php echo $model->name; ?></h5>
			<div><?php echo $model->description; ?></div>
<?php
			if (is_array($model->files) && ($count = count($model->files))) {
?>
			<div class="file_list">
<?php
				for ($i = 0; $i < $count; $i ++) {
					$file = $model->files[$i];
?>
				<div><a href="<?php echo $file->getAttachment(); ?>" target="_blank"><?php echo $file->name; ?></a></div>
<?php
				}
?>
			</div>
<?php
			}
?>
		</div>
		<div class="block span3">
			<div class="price">
				<?php echo CHtml::link(Yii::t('masla', 'Look prices and periods'), (Yii::app()->config->get('Site.SearchType') == 1 ? array('/detailSearchNew/default/search', 'article' => $model->article) : array('/detailSearch/default/search', 'search_phrase' => $model->article)), array('class' => 'btn btn-success', 'target' => '_blank')); ?>
			</div>
		</div>
	</div>
	<div class="chars span12">
	<h4><?php echo Yii::t('masla', 'Main characteristics'); ?></h4>
	<?php if ($model->country && is_object($model->countryObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['country']; ?>:</span><span><?php echo $model->countryObject->value; ?></span></div>
	<?php } ?>
	<?php if ($model->producer && is_object($model->producerObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['producer']; ?>:</span><span><?php echo $model->producerObject->value; ?></span></div>
	<?php } ?>
	<?php if ($model->specif) { ?>
	<div class="char"><span class="property"><?php echo $labels['specif']; ?>:</span><span><?php echo $model->specif; ?></span></div>
	<?php } ?>
	
<?php
	if ($count = count($model->producers)) {
?>
	<div class="char"><span class="property"><?php echo $labels['producer_text']; ?>:</span><span>
<?php
		for ($i = 0; $i < $count; $i ++) {
			$ps[] = CHtml::link($model->producers[$i]->name, array('/masla/default/index', 'p' => $model->producers[$i]->primaryKey));//'<a href="">'.$model->producers[$i]->name.'</a>';
		}
		
		echo implode(', ', $ps);
?>
	</span></div>
<?php
	}
?>
	
	<?php if ($model->scope && is_object($model->scopeObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['scope']; ?>:</span><span><?php echo $model->scopeObject->value; ?></span></div>
	<?php } ?>
	<?php if ($model->sae && is_object($model->saeObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['sae']; ?>:</span><span><?php echo $model->saeObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->engineTypeObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['engine_type']; ?>:</span><span><?php echo $model->engineTypeObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->oilTypeObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['oil_type']; ?>:</span><span><?php echo $model->oilTypeObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->fuelTypeObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['fuel_type']; ?>:</span><span><?php echo $model->fuelTypeObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->apiObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['api']; ?>:</span><span><?php echo $model->apiObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->ilsacObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['ilsac']; ?>:</span><span><?php echo $model->ilsacObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->isoObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['iso']; ?>:</span><span><?php echo $model->isoObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->aceaObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['acea']; ?>:</span><span><?php echo $model->aceaObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->jasoObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['jaso']; ?>:</span><span><?php echo $model->jasoObject->value; ?></span></div>
	<?php } ?>
	
	<h4><?php echo Yii::t('masla', 'Physical and chemical properties'); ?></h4>
	<?php if (is_object($model->densityObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['density']; ?>:</span><span><?php echo $model->densityObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->tempHardenObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['temp_harden']; ?>:</span><span><?php echo $model->tempHardenObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->colorObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['color']; ?>:</span><span><?php echo $model->colorObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->indexViscosityObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['index_viscosity']; ?>:</span><span><?php echo $model->indexViscosityObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->viscosityFortyObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['viscosity_forty']; ?>:</span><span><?php echo $model->viscosityFortyObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->viscosityHundredObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['viscosity_hundred']; ?>:</span><span><?php echo $model->viscosityHundredObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->tempFlashObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['temp_flash']; ?>:</span><span><?php echo $model->tempFlashObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->alkaliNumberObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['alkali_number']; ?>:</span><span><?php echo $model->alkaliNumberObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->tempLossFluidityObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['temp_loss_fluidity']; ?>:</span><span><?php echo $model->tempLossFluidityObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->tempBoilingObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['temp_boiling']; ?>:</span><span><?php echo $model->tempBoilingObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->sulphateAshObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['sulphate_ash']; ?>:</span><span><?php echo $model->sulphateAshObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->totalAcidNumberObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['total_acid_number']; ?>:</span><span><?php echo $model->totalAcidNumberObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->viscositySeemingObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['viscosity_seeming']; ?>:</span><span><?php echo $model->viscositySeemingObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->evaporabilityObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['evaporability']; ?>:</span><span><?php echo $model->evaporabilityObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->sulfurObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['sulfur']; ?>:</span><span><?php echo $model->sulfurObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->zincObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['zinc']; ?>:</span><span><?php echo $model->zincObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->phosphorusObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['phosphorus']; ?>:</span><span><?php echo $model->phosphorusObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->molybdenumObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['molybdenum']; ?>:</span><span><?php echo $model->molybdenumObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->boronObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['boron']; ?>:</span><span><?php echo $model->boronObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->magnesiumObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['magnesium']; ?>:</span><span><?php echo $model->magnesiumObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->calciumObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['calcium']; ?>:</span><span><?php echo $model->calciumObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->siliconObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['silicon']; ?>:</span><span><?php echo $model->siliconObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->sodiumObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['sodium']; ?>:</span><span><?php echo $model->sodiumObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->viscositySeemingObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['viscosity_seeming_35']; ?>:</span><span><?php echo $model->viscositySeemingObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->phObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['ph']; ?>:</span><span><?php echo $model->phObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->bariumObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['barium']; ?>:</span><span><?php echo $model->bariumObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->aluminumObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['aluminum']; ?>:</span><span><?php echo $model->aluminumObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->ironObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['iron']; ?>:</span><span><?php echo $model->ironObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->potassiumObject)) { ?>
	<div class="char"><span class="property"><?php echo $labels['potassium']; ?>:</span><span><?php echo $model->potassiumObject->value; ?></span></div>
	<?php } ?>
	</div>
</div>
<div style="clear: both;"></div>
<script type="text/javascript">
$(function() {
	$("a.fancybox").fancybox();
});
</script>
<style type="text/css">
	.chars .char {
		display: table-row;
	}
	
	.chars .char .property {
		font-weight: bold;
		padding: 0 15px 6px 0;
	}
	
	.chars .char span {
		display: table-cell;
	}
</style>