<?php
class QuickGroupsExtender extends CommonExtender
{
	function FormatLink($type, $dataItem, $catalog, $renderer)
	{
		if ($type == 'vehicle')
			$link = 'vehicle.php?c='.$catalog.'&vid='.$renderer->vehicleid. '&ssd='.$renderer->ssd;
			else
				$link = 'qdetails.php?c='.$catalog.'&gid='.$dataItem['quickgroupid']. '&vid='.$renderer->vehicleid. '&ssd='.$renderer->ssd;

				return $link;
	}
}