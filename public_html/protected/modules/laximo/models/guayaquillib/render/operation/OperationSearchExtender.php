<?php
if (!class_exists('OperationSearchExtender')) {
	class OperationSearchExtender extends CommonExtender
	{
		function FormatLink($type, $dataItem, $catalog, $renderer)
		{
			return 'vehicles.php?ft=execCustomOperation&c=' . $catalog;
		}
	}
}