<?php
echo '<h1>'.CommonExtender::LocalizeString('SearchByFrame').'</h1>';

$renderer = new GuayaquilFrameSearchForm(new FrameSearchExtender());
echo $renderer->Draw(array_key_exists('c', $_GET) ? $_GET['c'] : '', $cataloginfo, @$formframe, @$formframeno);

echo '<br><br>';