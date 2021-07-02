<?php
echo '<h1>' . CommonExtender::LocalizeString('SearchByCustom').' ' . $operation['description'] . '</h1>';

$renderer = new GuayaquilOperationSearchForm(new OperationSearchExtender());
echo $renderer->Draw(array_key_exists('c', $_GET) ? $_GET['c'] : '', $operation, @$_GET['data']);

echo '<br><br>';