<?php
$h1 = CommonExtender::LocalizeString('Search by wizard').' - '.$cataloginfo['name'];

$this->metaDescription = $h1;
$this->metaKeywords = $h1;
$this->pageTitle = $h1;

echo '<h1>'.$h1.'</h1>';

if ($error) {
	echo $error;
} else {
	$renderer = new GuayaquilWizard(new Wizard2Extender());
	echo $renderer->Draw($c, $wizard);
}