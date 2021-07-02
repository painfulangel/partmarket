<?php
	/* Update exchange rates */
	class ExchangeRates extends CConsoleCommand {
		public function run($args) {
			$basic = Currencies::model()->findByAttributes(array('basic' => 1));
			if (is_object($basic) && ($basicname = $basic->name)) {
				$others = array();
				
				$items = Currencies::model()->findAll(array('condition' => 'basic <> 1'));
				$count = count($items);
				for ($i = 0; $i < $count; $i ++) {
					$others[] = $items[$i]->name.$basicname;
				}
				
				if (count($others)) {
					$url = 'https://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+=+%22'.implode(',', $others).'%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=';
					
					$json = json_decode(file_get_contents($url));
					
					/*ob_start();
					echo '<pre>'; print_r($json); echo '</pre>';
					file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/info.txt', ob_get_clean());*/
					
					if (is_object($json) && property_exists($json, 'query') && property_exists($json->query, 'results') && property_exists($json->query->results, 'rate')) {
						$count = $json->query->count;
						
						for ($i = 0; $i < $count; $i ++) {
							$rate = $json->query->results->rate[$i];
							
							$name = str_replace($basicname, '', $rate->id);
							
							Currencies::model()->updateAll(array('exchange' => $rate->Rate), 'name=:name', array(':name' => $name));
						}
					}
				}
			}
		}
	}