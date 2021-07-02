<?php
	$this->breadcrumbs = array(
	    $razdel->name => array('/universal/default/index', 'alias' => $razdel->alias),
	    $model->name,
	);
	
	$default = $model->name.' '.$model->article;

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
			<div class="images span12">
			<?php $this->widget('ext.jquery_fancybox.FancyboxWidget', array('items' => $model->getImages())); ?>
			</div>
		</div>
		<div class="text span3">
			<h1><?php echo $default; ?></h1>
			<h5><?php echo $model->name; ?></h5>
			<div><?php echo $model->content; ?></div>
		</div>
		<div class="block span6">
			<div class="price">
				<?php echo CHtml::link(Yii::t('universal', 'Add to cart'), (Yii::app()->config->get('Site.SearchType') == 1 ? array('/detailSearchNew/default/search', 'article' => $model->article) : array('/detailSearch/default/search', 'search_phrase' => $model->article)), array('class' => 'btn btn-success', 'target' => '_blank')); ?>
			</div>
		</div>
	</div>
	<div class="chars span12">
<?php
	foreach ($chars as $char) {
		$value = '';
		
		$char_name = 'chars'.$char->primaryKey;
		
		if (isset($model->{$char_name})) {
			$value = $model->{$char_name};
			
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
		<div class="char"><span class="property"><?php echo $char->name; ?></span><span><?php echo $value; ?></span></div>
<?php
		}
	}
?>
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
	
	div.images {
		margin-top: 20px;
	}
	
	div.images a {
		text-decoration: none;
	}
	
	div.images img {
		border: 1px solid #ccc;
	    margin-right: 7px;
	    max-width: 50px;
	    padding: 3px;
	}
</style>