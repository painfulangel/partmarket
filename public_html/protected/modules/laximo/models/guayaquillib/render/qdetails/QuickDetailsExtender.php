<?php
class QuickDetailsExtender extends CommonExtender
{
	function FormatLink($type, $dataItem, $catalog, $renderer)
	{
		if ($type == 'vehicle')
			$link = 'vehicle.php?c='.$catalog.'&vid='.$renderer->vehicleid. '&ssd='.$renderer->ssd;
			elseif ($type == 'category')
			$link = 'vehicle.php?c='.$catalog.'&vid='.$renderer->vehicleid.'&cid='.$dataItem['categoryid'].'&ssd='.$dataItem['ssd'];
			elseif ($type == 'unit')
			{
				$coi = array();
				foreach ($dataItem->Detail as $detail)
				{
					if ((string)$detail['match']) {
						$i = (string)$detail['codeonimage'];
						$coi[$i] = $i;
					}
				}

				$link = 'unit.php?c='.$catalog.'&vid='.$renderer->vehicleid.'&uid='.$dataItem['unitid']. '&cid='.$renderer->currentunit['categoryid'].'&ssd='.$dataItem['ssd'].'&coi='.implode(',', $coi);
			}
			elseif ($type == 'detail') {
				$link = Config::get('redirectUrl');
				$link = str_replace('$oem$', urlencode($dataItem['oem']), $link);
			}

			return $link;
	}
}