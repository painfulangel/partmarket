<?php
class CatalogExtender extends CommonExtender {
	public static function FormatMyLink($type, $dataItem, $catalog, $renderer) {
		$item = new self();
		return $item->FormatLink($type, $dataItem, $catalog, $renderer);
	}
	
	function FormatLink($type, $dataItem, $catalog, $renderer) {
		$link = 'catalog.php?c='.$dataItem['code'].'&ssd='.$dataItem['ssd'];

		if (CommonExtender::isFeatureSupported($dataItem, 'wizardsearch2'))
			$link .= '&spi2=t';

		return $link;
	}
}