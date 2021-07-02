<?php
interface IGuayaquilExtender
{
	public function GetLocalizedString($name, $params = false, $renderer);

	public function AppendJavaScript($filename, $renderer);

	public function AppendCSS($filename, $renderer);

	public function FormatLink($type, $dataItem, $catalog, $renderer);

	public function Convert2uri($filename);
}
?>