<?php
class AllCatalogs extends CConsoleCommand {
	public function run($args) {
define('PLAN_TASK', 1);
		$items = array();

		//Если индексация включена
		$settings = KatalogSeoBrandsSettings::model()->find();

		if (is_object($settings) && $settings->index_active) {
			$data = KatalogSeoBrandsItems::model()->findAll(array('select' => 'id, brand, article'));
			$count = count($data);
			for ($i = 0; $i < $count; $i ++) {
				$items[$data[$i]->brand.'_'.$data[$i]->article] = $data[$i]->id;
			}

			//Активные склады
			$active_store = array();
			$s = KatalogSeoBrandsStores::model()->findAll();
			foreach ($s as $store) {
				$active_store[] = $store->store_id;
			}

			//file_put_contents(Yii::getPathOfAlias('webroot').'/commands/allcatalogs.txt', '1 - '.count($active_store)."\n", FILE_APPEND);

			if (count($active_store)) {
				//!!! Все бренды
				$brands = array();

				$data = KatalogSeoBrandsBrands::model()->findAll();
				$count = count($data);
				for ($i = 0; $i < $count; $i ++) {
					$brands[$data[$i]->brand] = $data[$i]->primaryKey;
				}
				//!!! Все бренды

				$price = Prices::model()->find(array('condition' => 'store_id IN('.implode(', ', $active_store).') AND processed = 0'));

				//file_put_contents(Yii::getPathOfAlias('webroot').'/commands/allcatalogs.txt', '2 - '.intval(is_object($price))."\n", FILE_APPEND);

				if (is_object($price)) {
					//if ($price->count_position == 0) {
						$price->count_position = PricesData::model()->countByAttributes(array('price_id'=> $price->id));
						$price->save();
					//}

					$portion = $settings->cron_count;
					$offset = $price->start;

					$data = PricesData::model()->findAll(array('select' => 'price_id, brand, article, name, price', 'condition' => 'price_id = '.$price->id, 'limit' => $portion, 'offset' => $offset));
					$count = count($data);

					//file_put_contents(Yii::getPathOfAlias('webroot').'/commands/allcatalogs.txt', '3 - '.$price->id.' - '.$count."\n", FILE_APPEND);

					for ($i = 0; $i < $count; $i ++) {
						$d = $data[$i];

						$brand = mb_strtoupper(trim($d->brand));
						$article = mb_strtoupper(trim($d->article));

						$brand_id = 0;

						if (array_key_exists($brand, $brands)) {
							$brand_id = $brands[$brand];
						} else {
							$b = new KatalogSeoBrandsBrands();
							$b->brand = $brand;
							if ($b->save()) {
								$brands[$brand] = $b->primaryKey;
								$brand_id = $brands[$brand];
							} else {
								ob_start();
								echo '<pre>'; print_r($b->getErrors()); echo '</pre>';
								file_put_contents(Yii::getPathOfAlias('webroot').'/commands/allcatalogs.txt', ob_get_clean()."\n", FILE_APPEND);
							}
						}

						if ($brand_id == 0) continue;

						//Формируем цену с учётом ценовой политики
						$cost = Yii::app()->getModule('prices')->getPriceFunction(array('price' => $d->price, 
																						'price_currency' => $price->currency, 
																						'price_price_group' => $settings->pricegroup,
																						'brand' => $brand));

						if (array_key_exists($brand.'_'.$article, $items)) {
							//Обновить цену
							KatalogSeoBrandsItems::model()->updateByPk($items[$brand.'_'.$article], array('price' => $cost));
						} else {
							$item = new KatalogSeoBrandsItems();
							$item->price_id = $d->price_id;
							$item->brand = $brand;
							$item->brand_id = $brand_id;
							$item->article = $article;
							$item->name = $d->name;
							$item->price = $cost;
							if ($item->save()) {
								$items[$brand.'_'.$article] = $item->primaryKey;
							}
						}
					}

					$offset += $portion;

					if ($offset >= $price->count_position) {
						$price->processed = 1;
					} else {
						$price->start = $offset;
					}

					$price->save();
				}
			}
		}
	}
}
?>