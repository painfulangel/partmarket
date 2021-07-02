<?php
class VinSearchExtender extends CommonExtender
{
	function FormatLink($type, $dataItem, $catalog, $renderer)
	{
		return 'vehicles.php?ft=findByVIN&c='.$catalog.'&vin=$vin$&ssd=';
	}
}