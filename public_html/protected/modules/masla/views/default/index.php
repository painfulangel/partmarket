<?php
	//For load index
	Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
	Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl().
			'/jui/css/base/jquery-ui.css'
	);
	//For load index
	
	$this->widget('ext.jquery_fancybox.FancyboxWidget');
	
	if (is_object($p)) {
		$this->breadcrumbs = array(
			Yii::t('masla', 'Oil catalog') => array('/masla/default/index'),
			Yii::t('masla', 'Oil catalog').' '.Yii::t('masla', 'for').' '.$p->name,
		);
	} else {
		$this->breadcrumbs = array(
			Yii::t('masla', 'Oil catalog'),
		);
	}
	
	$this->pageTitle = Yii::t('masla', 'Oil catalog');
	$this->metaTitle = Yii::t('masla', 'Oil catalog');
?>
<h1><?php echo Yii::t('masla', 'Oil catalog').(is_object($p) ? ' '.Yii::t('masla', 'for').' '.$p->name : ''); ?></h1>
<div class="span12 tires-block">
	<div class="span3 tires-filter">
		<form method="get">
<?php
			if (is_object($p)) {
?>
			<input type="hidden" name="p" value="<?php echo $p->primaryKey; ?>">
<?php
			}
			
			$count = count($ps);
			for ($i = 0; $i < $count; $i ++) {
				echo $this->renderPartial('_view_filter_block', array('p' => $ps[$i], 'properties' => $properties));
			}
			
			echo CHtml::submitButton(Yii::t('masla', 'Show'), array('class' => 'btn btn-primary'));
?>
		</form>
	</div>
<?php
	$this->widget('bootstrap.widgets.TbListView', array(
		'dataProvider' => $dataProvider,
		'viewData' => array('labels' => $labels),
		'itemView' => '_view',
		'template' => '{items} {pager}',
		'id' => 'masla',
		'htmlOptions' => array('class' => 'span9'),
	));
?>
</div>
<div style="clear: both;"></div>
<script type="text/javascript">
$(function() {
	$("#masla a.fancybox").fancybox();

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