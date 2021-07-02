<h1>
<?php echo CommonExtender::LocalizeString('SearchByVIN');?></h1>
<?php
$renderer = new GuayaquilVinSearchForm(new VinSearchExtender());
echo $renderer->Draw(array_key_exists('c', $_GET) ? $_GET['c'] : '', $cataloginfo, @$formvin);

echo '<br><br>';