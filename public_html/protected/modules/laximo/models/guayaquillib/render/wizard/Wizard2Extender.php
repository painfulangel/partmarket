<?php
class Wizard2Extender extends CommonExtender {
	function FormatLink($type, $dataItem, $catalog, $renderer) {
		if ($type == 'vehicles') 
			return 'vehicles.php?ft=findByWizard2&c='.$catalog.'&ssd='.$_GET['ssd'];
		else
			return 'wizard2.php?c='.$catalog.'&ssd=$ssd$';
	}
}