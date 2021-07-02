<?php
class WizardExtender extends CommonExtender {
	function FormatLink($type, $dataItem, $catalog, $renderer) {
		if ($type == 'vehicles')
			return 'vehicles.php?ft=findByWizard2&c='.$catalog.'&ssd='.$renderer->wizard->row['ssd'];
		else
			return 'wizard2.php?c='.$catalog.'&ssd=$ssd$';
	}
}