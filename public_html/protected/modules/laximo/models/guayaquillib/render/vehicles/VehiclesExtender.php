<?php
class VehiclesExtender extends CommonExtender
{
	function FormatLink($type, $dataItem, $catalog, $renderer)
	{
		if (!$catalog)
			$catalog = $dataItem['catalog'];
			$link = ($renderer->qg == 1 ? 'qgroups' : 'vehicle').'.php?c='.$catalog.'&vid='.$dataItem['vehicleid'].'&ssd='.$dataItem['ssd'].($renderer->qg == -1 ? '&checkQG': ''). '&path_data='.urlencode(base64_encode(substr($dataItem['vehicle_info'], 0, 300)));

			return $link;
			//return 'vehicle.php?c='.$catalog.'&vid='.$dataItem['vehicleid'].'&ssd='.$dataItem['ssd'];
	}
}