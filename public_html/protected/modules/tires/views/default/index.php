<?php
	//For load index
	Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
	Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl().
			'/jui/css/base/jquery-ui.css'
	);
	//For load index
	
	$this->widget('ext.jquery_fancybox.FancyboxWidget');
	
	$this->breadcrumbs = array(
			Yii::t('tires', 'Tires catalog')
	);

	$this->pageTitle = Yii::t('tires', 'Tires catalog');
	$this->metaTitle = Yii::t('tires', 'Tires catalog');
?>
<h1><?php echo Yii::t('tires', 'Tires catalog'); ?></h1>
<div class="span12 tires-block">
	<div class="span3 tires-filter">
		<form method="get">
<?php
			echo $this->renderPartial('_view_filter_block', array('id_property' => 2, 'name_property' => Yii::t('tires', 'Brands'), 'dataProvider' => $dataProvider2, 'properties' => $properties));
			echo $this->renderPartial('_view_filter_block', array('id_property' => 6, 'name_property' => Yii::t('tires', 'Seasonality'), 'dataProvider' => $dataProvider6, 'properties' => $properties));
			echo $this->renderPartial('_view_filter_block', array('id_property' => 3, 'name_property' => Yii::t('tires', 'Width'), 'dataProvider' => $dataProvider3, 'properties' => $properties));
			echo $this->renderPartial('_view_filter_block', array('id_property' => 4, 'name_property' => Yii::t('tires', 'Height'), 'dataProvider' => $dataProvider4, 'properties' => $properties));
			echo $this->renderPartial('_view_filter_block', array('id_property' => 5, 'name_property' => Yii::t('tires', 'Diameter'), 'dataProvider' => $dataProvider5, 'properties' => $properties));
			echo $this->renderPartial('_view_filter_block', array('id_property' => 1, 'name_property' => Yii::t('tires', 'Type'), 'dataProvider' => $dataProvider1, 'properties' => $properties));
			echo $this->renderPartial('_view_filter_block', array('id_property' => 7, 'name_property' => Yii::t('tires', 'Speed index'), 'dataProvider' => $dataProvider7, 'properties' => $properties));
			echo $this->renderPartial('_view_filter_block', array('id_property' => 8, 'name_property' => Yii::t('tires', 'Shipp'), 'dataProvider' => $dataProvider8, 'properties' => $properties));
			echo $this->renderPartial('_view_filter_block', array('id_property' => 9, 'name_property' => Yii::t('tires', 'Load index'), 'dataProvider' => $dataProvider9, 'properties' => $properties, 'minLoadIndex' => $minLoadIndex, 'maxLoadIndex' => $maxLoadIndex));
			echo $this->renderPartial('_view_filter_block', array('id_property' => 10, 'name_property' => Yii::t('tires', 'Axis'), 'dataProvider' => $dataProvider10, 'properties' => $properties));
			
			echo CHtml::submitButton(Yii::t('tires', 'Show'), array('class' => 'btn btn-primary'));
?>
		</form>
	</div>
<?php
	$this->widget('bootstrap.widgets.TbListView', array(
			'dataProvider' => $dataProvider,
			'itemView' => '_view',
			'template' => '{items} {pager}',
			'id' => 'tires',
			'htmlOptions' => array('class' => 'span9'),
	));
?>
</div>
<div style="clear: both;"></div>
<script type="text/javascript">
$(function() {
	$("#tires a.fancybox").fancybox();

	$('.item_switch').click(function() {
		$('.act_switch[rel=' + $(this).attr('rel') + ']').removeClass('act_switch');

		if ($(this).hasClass('show_popular')) {
			$('.filter-hidden[rel=' + $(this).attr('rel') + ']').addClass('check-hidden');
		} else {
			$('.filter-hidden[rel=' + $(this).attr('rel') + ']').removeClass('check-hidden');
		}

		$(this).addClass('act_switch');
	});

	$('h5').click(function() {
		if ($(this).find('span').hasClass('ch2')) {
			$(this).find('span').removeClass('ch2');

			$('.tires-filter-content[rel=' + $(this).attr('rel') + ']').slideDown();
		} else {
			$(this).find('span').addClass('ch2');

			$('.tires-filter-content[rel=' + $(this).attr('rel') + ']').slideUp();
		}
	});
});
</script>
<style>
	h5 {
		cursor: pointer;
	}
	
	span.ch {
		float: right;
		width: 10px;
		height: 6px;
		background-image: url("/images/ch1.png");
	}
	
	span.ch2 {
		background-image: url("/images/ch2.png");
	}
</style>