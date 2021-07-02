<?php
$h1 = CommonExtender::FormatLocalizedString('GroupDetails').' '.$vehicle['name'].' '.$nodeName;

$this->metaDescription = $h1;
$this->metaKeywords = $h1;
$this->pageTitle = $h1;

echo '<h1>'.$h1.'</h1>';
?>	
	<div id="pagecontent">
		<div class="span4 gqroups">
<?php
	$renderer = new GuayaquilQuickGroupsList(new QuickGroupsExtender());
	$renderer->gLink = $gLink;
	$renderer->quickgroupid = $quickgroupid;
	
	echo $renderer->Draw($groups, $_GET['c'], $_GET['vid'], $_GET['ssd']);
?>
		</div>
		<div class="span8">
<?php
	$renderer = new GuayaquilQuickDetailsList(new QuickDetailsExtender());
	$renderer->detaillistrenderer = new GuayaquilDetailsList($renderer->extender);
	$renderer->detaillistrenderer->group_by_filter = 1;
	$renderer->drawtoolbar = false;
	echo $renderer->Draw($details, $_GET['c'], $_GET['vid'], $_GET['ssd']);
?>
		</div>
	</div>

	<script type="text/javascript">
	jQuery(document).ready(function(){
	    jQuery('.guayaquil_zoom').colorbox({
	        href: function () {
	                var url = jQuery(this).attr('full');
	                return url;
	        },
	        photo:true,
	        rel: "img_group",
	        opacity: 0.3,
	        title : function () {
	            var title = jQuery(this).attr('title');
	            var url = jQuery(this).attr('link');
	            return '<a href="' + url + '">' + title + '</a>';
	        },
	        current: 'Рис. {current} из {total}',
	        maxWidth : '98%',
	        maxHeight : '98%'
	    });
	});
	</script>