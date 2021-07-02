<h1><?php echo $settings->index_h1; ?></h1>
<?php
	$this->metaDescription = $settings->index_description;
	$this->metaKeywords = $settings->index_keywords;
	$this->metaTitle = $settings->index_title;

	$this->widget('bootstrap.widgets.TbListView', array(
		'dataProvider' => $dataProvider,
		'itemView' => '_view',
		'template' => '{items} {pager}',
		'id' => 'katalogseobrands',
		'htmlOptions' => array('class' => 'row-fluid katalogseobrands'),
		'viewData' => array('url' => $settings->url),
	));

	echo Chtml::button(Yii::t('katalogSeoBrands', 'Show all brands'), array('class' => 'btn btnToggle'));
?>
<?php echo $settings->index_text; ?>
<script type="text/javascript">
	$(function() {
		$('.btnToggle').click(function() {
			if ($('#katalogseobrands').find('.hide').length) {
				$(this).val('<?php echo Yii::t('katalogSeoBrands', 'Hide all brands'); ?>');

				$('#katalogseobrands').find('.hide').removeClass('hide').addClass('show');
			} else {
				$(this).val('<?php echo Yii::t('katalogSeoBrands', 'Show all brands'); ?>');

				$('#katalogseobrands').find('.show').removeClass('show').addClass('hide');
			}
		});
	});
</script>
<style>
	.btnToggle {
		margin: 10px 0px;
	}

	@media (min-width: 1200px) {
	  .row-fluid.katalogseobrands [class*="span"]:first-child {
	    margin-left: 2.564102564102564% !important;
	  }
	}
</style>