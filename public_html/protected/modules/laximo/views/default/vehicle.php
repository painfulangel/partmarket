<?php
$h1 = CommonExtender::FormatLocalizedString('CarName', $vehicle['name']);

$this->metaDescription = $h1;
$this->metaKeywords = $h1;
$this->pageTitle = $h1;

echo '<h1>'.$h1.'</h1>';

echo '<div id="pagecontent">';

$renderer = new GuayaquilCategoriesList(new CategoryExtender());
$renderer->vehicle_id = $_GET['vid'];
$renderer->categoryid = array_key_exists('cid', $_GET) ? $_GET['cid'] : -1;
echo $renderer->Draw($_GET['c'], $categories, $renderer->vehicle_id, $renderer->categoryid, $_GET['ssd'], $catalogInfo);

$renderer = new GuayaquilUnitsList(new UnitExtender());
$renderer->vehicle_id = $_GET['vid'];
$renderer->categoryid = array_key_exists('cid', $_GET) ? $_GET['cid'] : -1;
$renderer->imagesize = 250;
echo $renderer->Draw($_GET['c'], $units);

echo '</div>';