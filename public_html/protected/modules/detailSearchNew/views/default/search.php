<?php
	$this->pageTitle = Yii::t('detailSearch', 'Search of spare parts');
	
	if ($brand != '') {
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.scrollTo.min.js');
		
		$this->breadcrumbs = array(Yii::t('detailSearch', 'Search of spare parts') => array('/detailSearchNew/default/search', 'article' => $article), $brand);
	} else {
		$this->breadcrumbs = array(Yii::t('detailSearch', 'Search of spare parts'));
	}

	$this->widget('ext.jquery_fancybox.FancyboxWidget');
?>
	<h1 class="Filter_h1"></h1>
<?php
	if (isset($pdm)) {
		if ($pdm->meta_title) {
			$this->pageTitle = $pdm->meta_title;
			$this->metaTitle = $pdm->meta_title;
		}
		
		if ($pdm->meta_description) $this->metaDescription = $pdm->meta_description;
		if ($pdm->meta_keywords) $this->metaKeywords = $pdm->meta_keywords;
?>
	<div class="searchData">
<?php
		if ($pdm->image) {
?>
		<div class="searchImage"><img src="<?php echo $pdm->getThumb(); ?>"></div>
<?php
		}
		
		if ($pdm->content) {
?>
		<div class="searchContent"><?php echo $pdm->content; ?></div>
<?php
		}
?>
	</div>
<?php
	}
?>
<div id="searchData">
	<?php //echo Yii::t('detailSearch', 'Please, wait. There is a search.'); ?>
	<div class="search_the_best"></div>
	<?php if ($brand != '') { ?>
	<?php if (($count = count($clist)) > 1) { ?>
	<div class="currency_list">
	<?php echo Yii::t('detailSearchNew', 'Choose currency'); ?><select name="currency_list">
<?php
	$selected = is_object($based) ? $based->primaryKey : 0;
	
	for ($i = 0; $i < $count; $i ++) {
?>
	<option value="<?php echo $clist[$i]->primaryKey; ?>"<?php if ($clist[$i]->primaryKey == $selected) { ?> selected<?php } ?>><?php echo $clist[$i]->name; ?></option>
<?php
	}
?>
	</select>
	</div>
<?php } ?>
	<?php } ?>
	<div id="Filter_results"></div>
	<script type="text/javascript">
	    $(function () {
	        Filter_search_page('<?php echo $search_phrase; ?>', '<?php echo $brand ?>');
	    })</script>
</div>