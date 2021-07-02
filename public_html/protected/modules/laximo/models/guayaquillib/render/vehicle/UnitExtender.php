<?php
class UnitExtender extends CommonExtender
{
	function FormatLink($type, $dataItem, $catalog, $renderer)
	{
		if ($type == 'filter')
			$link = 'unitfilter.php?c='.$catalog.'&vid='.$renderer->vehicle_id.'&uid='.$dataItem['unitid']. '&cid='.$renderer->categoryid.'&ssd='.$dataItem['ssd'].'&f='.urlencode($dataItem['filter']);
		else
			$link = 'unit.php?c='.$catalog.'&vid='.$renderer->vehicle_id.'&uid='.$dataItem['unitid'].'&cid='.$renderer->categoryid.'&ssd='.$dataItem['ssd'];

		return $link;
		//return 'unit.php?c='.$catalog.'&uid='.$dataItem['unitid'].'&ssd='.$dataItem['ssd'];
	}
}