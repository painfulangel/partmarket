<?php
class CategoryExtender extends CommonExtender
{
	function FormatLink($type, $dataItem, $catalog, $renderer)
	{
		$ssd = (string)$dataItem['ssd']; // Получаем SSD категории
		if ($type == 'quickgroup')
			$link = 'qgroups.php?c='.$catalog.'&vid='.$renderer->vehicleid.'&ssd='.$renderer->ssd;
			else
				$link = 'vehicle.php?&c='.$catalog.'&vid='.$renderer->vehicleid.'&cid='.$dataItem['categoryid'].'&ssd='.($ssd ? $ssd : $renderer->ssd);

				return $link;
	}
}