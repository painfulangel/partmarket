<?php
$this->metaDescription = $h1;
$this->metaKeywords = $h1;
$this->pageTitle = $h1;
		
echo '<h1>'.$h1.'</h1>';

if ($error != '') {
	echo $error;
} else {
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
	//Pictures
	foreach ($pictures as $group) {
		$has_link = ((string)$group['link']) == 'true';
		
		if ($has_link) {
			$link = $renderer->FormatLink('quickgroup', $group, $_GET['c']);
		} else {
			$link = $gLink.'&node='.$group['quickgroupid'];
		}
		
		$block = '<a href="'.$link.'">'.$group['name'].'</a>';
		
		echo '<div class="span3 qgroupsBlock"><div>'.$block.'</div></div>';
	}
?>
	</div>
</div>
<?php
}