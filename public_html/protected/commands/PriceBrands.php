<?php
class PriceBrands extends CConsoleCommand {
	public function run($args) {
		//Получить из прайсов бренды
		$command = Yii::app()->db->createCommand();
/*
		//1. Новая задача обработки прайсов
		$crontab = $command
	    ->select('hash')
	    ->from('new_brands_crontab_date')
	    ->where('date_end IS NULL')
	    ->queryRow();

	    if (!is_array($crontab) || !array_key_exists('hash', $crontab)) {
	    	$hash = md5(time()*rand());

	    	$command->insert('new_brands_crontab_date', array(
			    'date_start' => time(),
			    'hash' => $hash,
			));
	    } else {
	    	$hash = $crontab['hash'];
	    }
*/
	    //1. Бренды, которые уже есть в базе
	    $bs = array();

	    $brands = Brands::model()->findAll(array('select' => 'name, synonym'));
	    $count = count($brands);
	    for ($i = 0; $i < $count; $i ++) {
	    	$bs = array_merge($bs, array_map('mb_strtoupper', array_map('trim', array_diff(explode(',', $brands[$i]->synonym), array('')))));
	    	$bs[] = mb_strtoupper(trim($brands[$i]->name));
	    }

	    $brands = BrandsNew::model()->findAll(array('select' => 'name'));
	    $count = count($brands);
	    for ($i = 0; $i < $count; $i ++) {
	    	$bs[] = mb_strtoupper(trim($brands[$i]->name));
	    }

	    //2. Бренды, отмеченные как некорректные
	    $brands = BrandsIncorrect::model()->findAll(array('select' => 'name'));
	    $count = count($brands);
	    for ($i = 0; $i < $count; $i ++) {
	    	$bs[] = mb_strtoupper(trim($brands[$i]->name));
	    }

	    //3. Прайс, который ещё не обработан
	    $ids = array(0);

	    $crontabs = $command->select('price_id')
	    ->from('new_brands_crontab')
	    ->queryAll();

	    $count = count($crontabs);
	    for ($i = 0; $i < $count; $i ++) {
	    	$ids[] = $crontabs[$i]['price_id'];
	    }

		$price = Prices::model()->find(array('select' => 'id', 'condition' => 'id NOT IN('.implode(', ', $ids).')', 'order' => 'id ASC'));

		if (is_object($price)) {
			$data = PricesData::model()->findAll(array('condition' => 'price_id = '.$price->primaryKey, 'select' => 'brand, price_id'));

			$count = count($data);
			for ($i = 0; $i < $count; $i ++) {
				$brand = mb_strtoupper(trim($data[$i]->brand));

				if (!in_array($brand, $bs)) {
					$bn = new BrandsNew();
					$bn->name = $brand;
					$bn->price_id = $data[$i]->price_id;
					if ($bn->save()) {
						$bs[] = $brand;
					}
				}
			}

			$command->insert('new_brands_crontab', array(
			    'date_start' => time(),
			    'price_id' => $price->primaryKey,
			));
		}
	}
}
?>