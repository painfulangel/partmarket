<?php
	$this->breadcrumbs = array(
	    Yii::t('tires', 'Tires catalog') => array('/tires/default/index'),
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
			<?php if ($thumb = $model->getThumb(400)) { ?>
			<a class="fancybox" href="<?php echo $model->getImage(); ?>" title="<?php echo htmlspecialchars($model->name); ?>"><img src="<?php echo $thumb; ?>"></a>
			<?php } else { ?>
			<img src="/images/nophoto.png">
			<?php
			} ?>
		</div>
		<div class="text span3">
			<h1><?php echo $default; ?></h1>
			<h5><?php echo $model->name; ?></h5>
			<div><?php echo $model->description; ?></div>
		</div>
		<div class="block span6">
			<div class="price">
				<?php echo CHtml::link(Yii::t('tires', 'Look prices and periods'), (Yii::app()->config->get('Site.SearchType') == 1 ? array('/detailSearchNew/default/search', 'article' => $model->article) : array('/detailSearch/default/search', 'search_phrase' => $model->article)), array('class' => 'btn btn-success', 'target' => '_blank')); ?>
			</div>
		</div>
	</div>
	<div class="chars span12">
	<?php if (is_object($model->typeObject)) { ?>
	<div class="char"><span class="property"><?php echo Yii::t('tires', 'Type'); ?>:</span><span><?php echo $model->typeObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->producerObject)) { ?>
	<div class="char"><span class="property"><?php echo Yii::t('tires', 'Producer'); ?>:</span><span><?php echo $model->producerObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->widthObject)) { ?>
	<div class="char"><span class="property"><?php echo Yii::t('tires', 'Width'); ?>:</span><span><?php echo $model->widthObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->heightObject)) { ?>
	<div class="char"><span class="property"><?php echo Yii::t('tires', 'Height'); ?>:</span><span><?php echo $model->heightObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->diameterObject)) { ?>
	<div class="char"><span class="property"><?php echo Yii::t('tires', 'Diameter'); ?>:</span><span><?php echo $model->diameterObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->seasonalityObject)) { ?>
	<div class="char"><span class="property"><?php echo Yii::t('tires', 'Seasonality'); ?>:</span><span><?php echo $model->seasonalityObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->speedIndexObject)) { ?>
	<div class="char"><span class="property"><?php echo Yii::t('tires', 'Speed index'); ?>:</span><span><?php echo $model->speedIndexObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->shippObject)) { ?>
	<div class="char"><span class="property"><?php echo Yii::t('tires', 'Shipp'); ?>:</span><span><?php echo $model->shippObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->loadIndexObject)) { ?>
	<div class="char"><span class="property"><?php echo Yii::t('tires', 'Load index'); ?>:</span><span><?php echo $model->loadIndexObject->value; ?></span></div>
	<?php } ?>
	<?php if (is_object($model->axisObject)) { ?>
	<div class="char"><span class="property"><?php echo Yii::t('tires', 'Axis'); ?>:</span><span><?php echo $model->axisObject->value; ?></span></div>
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