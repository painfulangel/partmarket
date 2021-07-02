<?php
class DetailExtender extends CommonExtender
{
	function FormatLink($type, $dataItem, $catalog, $renderer)
	{
		if ((string)$dataItem['filter'])
			$link = 'detailfilter.php?c='.$catalog.'&vid='.$_GET['vid'].'&uid='.$_GET['uid']. '&cid='.$_GET['cid'].'&did='.$dataItem['detailid'].'&ssd='.$dataItem['ssd'].'&f='.urlencode($dataItem['filter']);
			else {
				$link = Config::get('redirectUrl');
				$link = str_replace('$oem$', urlencode($dataItem['oem']), $link);
			}

			return $link;
	}
}