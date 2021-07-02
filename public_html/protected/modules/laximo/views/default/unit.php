<?php
$h1 = CommonExtender::FormatLocalizedString('UnitName', (string)$unit['name']);

$this->metaDescription = $h1;
$this->metaKeywords = $h1;
$this->pageTitle = $h1;

echo '<h1>'.$h1.'</h1>';

$renderer = new GuayaquilUnit(new DetailExtender());
$renderer->detaillistrenderer = new GuayaquilDetailsList($renderer->extender);
$renderer->detaillistrenderer->columns = array('Toggle'=>1, 'PNC'=>3, 'OEM'=>2, 'Name'=>3, 'Cart'=>1, 'Price'=>3, 'Note'=>2, 'Tooltip'=>1);
echo $renderer->Draw($_GET['c'], $unit, $imagemap, $details, NULL, NULL);

$pnc = array();
if (array_key_exists('coi', $_GET))
	$pnc = explode(',', $_GET['coi']);

	if (array_key_exists('oem', $_GET) && $_GET['oem']) {
		$oem = $_GET['oem'];
		foreach ($details as $detail) {
			if ((string)$detail['oem'] == $oem) {
				$pnc[] = (string)$detail['codeonimage'];
			}
		}
	}
	if (count($pnc)) {?>
<script type="text/javascript">
	<?php
        foreach ($pnc as $code)
            echo 'jQuery(\'.g_highlight[name='.$code.']\').addClass(\'g_highlight_lock\');';
	?>
</script>
<?php }