<?php
class LocalSearchModel extends DDetailSearchModelClass {
    public function getData($articul, $params = array()) {

        $crossesWithBrandsAndAliases = Yii::app()->getModule('crosses')->getCrossesWithBrandsAndAliases($articul, $this->search_brand);

    	if (count($crossesWithBrandsAndAliases) > 1000) $crossesWithBrandsAndAliases = array_slice($crossesWithBrandsAndAliases, 0, 1000, true);

    	if (empty($crossesWithBrandsAndAliases)) {
    		$crosses = array('0');
    	} else {
    		$crosses = array_merge(array(0), array_keys($crossesWithBrandsAndAliases));
    	}

    	if (is_array($articul)) {
    		foreach ($articul as $v) {
    			array_unshift($crosses, $v);
    		}
    	} else {
    		array_unshift($crosses, $articul);
    	}

        //Бренды
        $brands = array();

        $data = Brands::model()->findAll(array('condition' => 'active_state = 1 AND hide = 0', 'select' => 'name, synonym'));

        $count = count($data);
        for ($i = 0; $i < $count; $i ++) {
            $name = mb_strtolower(trim($data[$i]->name));

            if (!in_array($name, $brands)) $brands[] = $name;

            $synonym = explode(',', $data[$i]->synonym);

            if ($count2 = count($synonym)) {
                for ($j = 0; $j < $count2; $j ++) {
                    $name = mb_strtolower(trim($synonym[$j]));

                    if (!in_array($name, $brands)) $brands[] = $name;
                }
            }
        }
        //Бренды

        //echo '<pre>'; print_r($brands); echo '</pre>';

        $db = Yii::app()->db;
        $criteria = (Yii::app()->language == Yii::app()->params['default_language'] ? ' `t_price`.`language`=\'0\' ' : ' `t_price`.`language`=\''.Yii::app()->language.'\' ');

        $slice = array_values($crosses);

        /*$where = array();
        $count2 = count($slice);
        for ($j = 0; $j < $count2; $j ++) {
        	$where[] = '`t`.`article`=\''.$slice[$j].'\'';
        }
        */
        /*$sql = 'SELECT COUNT(*) '
               .'FROM `prices_data` `t` JOIN `prices` `t_price`  ON `t`.`price_id`=`t_price`.`id` JOIN `stores` `t_store`  ON t_price.store_id=`t_store`.`id` '
               ."WHERE `t_price`.`active_state`='1' AND (`t_price`.`language`='' OR $criteria)  AND (".implode(' OR ', $where).') ';*/

        $sliceIn = array_map(function($var){ return '"'.$var.'"'; }, $slice);
        $whereIn = implode(',', $sliceIn);

        $sql = 'SELECT COUNT(*) '
            .'FROM `prices_data` `t` JOIN `prices` `t_price`  ON `t`.`price_id`=`t_price`.`id` JOIN `stores` `t_store`  ON t_price.store_id=`t_store`.`id` '
            ."WHERE `t_price`.`active_state`='1' AND (`t_price`.`language`='' OR $criteria)  AND `t`.`article` IN ($whereIn);";


        $total = $data = $db->createCommand($sql)->queryScalar();
        $start = 0;
        $buffer = 500;

        while ($start < $total) {
            /*$sql = 'SELECT `t`.`id` as `id`, `t`.`name` as `name`, `t`.`brand` as `brand`, `t`.`price` as `price`, `t`.`quantum` as `quantum`, `t`.`article` as `article`, `t`.`original_article` as `original_article`, `t`.`delivery` as `delivery`, `t`.`weight` as `weight`,'
                   ."`t_price`.`id` as `price_id`, `t_price`.`name` as `price_name`, `t_price`.`delivery` as `price_delivery`, `t_price`.`price_group_$params[price_group_id]` as `price_price_group`,`t_price`.`price_group_1` as `price_price_group_1`,`t_price`.`price_group_2` as `price_price_group_2`,`t_price`.`price_group_3` as `price_price_group_3`,`t_price`.`price_group_4` as `price_price_group_4`, `t_price`.`supplier_inn` as `price_supplier_inn`, `t_price`.`supplier` as `price_supplier`, `t_price`.`currency` as `price_currency`, "
                   .'`t_store`.`id` AS `store_id`, `t_store`.`name` as `store_name`, `t_store`.`description` as `store_description`, `t_store`.`top` as `store_top`, `t_store`.`highlight` as `store_highlight`, `t_store`.`count_state` as `store_count_state` '
                   .'FROM `prices_data` `t` JOIN `prices` `t_price`  ON `t`.`price_id`=`t_price`.`id` JOIN `stores` `t_store`  ON t_price.store_id=`t_store`.`id` '
                   ."WHERE `t_price`.`active_state`='1' AND (`t_price`.`language`='' OR $criteria)  AND (".implode(' OR ', $where).')  LIMIT '."$start,$buffer";*/

            $sql2 = 'SELECT `t`.`id` as `id`, `t`.`multiply`, `t`.`name` as `name`, `t`.`brand` as `brand`, `t`.`price` as `price`, `t`.`quantum` as `quantum`, `t`.`article` as `article`, `t`.`original_article` as `original_article`, `t`.`delivery` as `delivery`, `t`.`weight` as `weight`,'
                ."`t_price`.`id` as `price_id`, `t_price`.`name` as `price_name`, `t_price`.`delivery` as `price_delivery`, `t_price`.`price_group_$params[price_group_id]` as `price_price_group`,`t_price`.`price_group_1` as `price_price_group_1`,`t_price`.`price_group_2` as `price_price_group_2`,`t_price`.`price_group_3` as `price_price_group_3`,`t_price`.`price_group_4` as `price_price_group_4`, `t_price`.`supplier_inn` as `price_supplier_inn`, `t_price`.`supplier` as `price_supplier`, `t_price`.`currency` as `price_currency`, "
                .'`t_store`.`id` AS `store_id`, `t_store`.`name` as `store_name`, `t_store`.`description` as `store_description`, `t_store`.`top` as `store_top`, `t_store`.`highlight` as `store_highlight`, `t_store`.`count_state` as `store_count_state` '
                .'FROM `prices_data` `t` JOIN `prices` `t_price`  ON `t`.`price_id`=`t_price`.`id` JOIN `stores` `t_store`  ON t_price.store_id=`t_store`.`id` '
                ."WHERE `t_price`.`active_state`='1' AND (`t_price`.`language`='' OR $criteria)  AND `t`.`article` IN (".$whereIn.')  LIMIT '."$start,$buffer";


            $data = $db->createCommand($sql2)->queryAll();

            foreach ($data as $value) {
            	$continue = false;

            	if ($this->search_brand != '') {
            		$article = mb_strtoupper(trim($value['article']));

            		$analog = is_array($articul) ? !in_array($article, $articul) : $article != $articul;

	            	if ($analog) {
						$brand = mb_strtoupper(trim($value['brand']));

						if (array_key_exists($article, $crossesWithBrandsAndAliases) && (!in_array($brand, $crossesWithBrandsAndAliases[$article]['brand']) || (array_key_exists('aliases', $crossesWithBrandsAndAliases[$article]) && !in_array($brand, $crossesWithBrandsAndAliases[$article]['aliases'])))) {
							$continue = true;
						}
	            	}
            	}

            	if ($continue == false) {
	                $delivery = empty($value['delivery']) ? $value['price_delivery'] : (is_numeric($value['delivery']) ? ($value['delivery'] + (is_numeric($value['price_delivery']) ? $value['price_delivery'] : 0)) : $value['delivery']);

					//$delivery = empty($value['delivery']) ? $value['price_delivery'] : $value['delivery'];
	                if ($delivery == 0 || empty($delivery))
	                    $delivery = Yii::t('detailSearch', 'Available');//Yii::app()->getModule('detailSearch')->zerosDeliveryValue;

	                $price_purchase = Yii::app()->getModule('currencies')->getPrice($value['price'], $value['price_currency']);
	                $price_purchase_echo = Yii::app()->getModule('prices')->getPriceFormatFunction($price_purchase);

	                $price = Yii::app()->getModule('prices')->getPriceFunction($value);
	                $price_echo = Yii::app()->getModule('prices')->getPriceFormatFunction($price);

	                $reliable = Reliability::model()->getReliability($value['price_supplier_inn']);
	                $all_prices = array();
	                for ($pi = 1; $pi <= 4; $pi++) {
	                    $temp_value = $value;
	                    $temp_value['price_price_group'] = $value['price_price_group_'.$pi];
	                    $temp_price = Yii::app()->getModule('prices')->getPriceFunction($temp_value);
	                    $all_prices[] = array(
	                        'price_group' => $pi,
	                        'price' => $temp_price,
	                        'price_echo' => Yii::app()->getModule('prices')->getPriceFormatFunction($temp_price),
	                    );
	                }
	                $this->data[] = array(
	                    'price_group_1' => $value['price_price_group_1'],
	                    'price_group_2' => $value['price_price_group_2'],
	                    'price_group_3' => $value['price_price_group_3'],
	                    'price_group_4' => $value['price_price_group_4'],
	                    'supplier_price' => $value['price'] * Yii::app()->params['MultiKoefSuplierPrice'],
	                    'articul_order' => mb_strtoupper($value['original_article']),
	                    'supplier_inn' => $value['price_supplier_inn'],
	                    'supplier' => $value['price_supplier'],
	                	'multiply' => $value['multiply'],
	                	'store_id' => $value['store_id'],
	                    'store' => $value['store_name'],
	                	'store_description' => str_replace(array("\n", "\r", '"', "'"), array('', '', "\'", "\'"), $value['store_description']),
	                    'store_top' => $value['store_top'],
	                	'store_highlight' => $value['store_highlight'],

	                    'name' => $value['name'],
	                    'brand' => $value['brand'],
                        'brand_link' => intval(in_array(mb_strtolower(trim($value['brand'])), $brands)),
	                    'articul' => mb_strtoupper($value['article']),
	                    'dostavka' => $delivery,
	                    'kolichestvo' => $value['quantum'],

	                	'price_purchase' => $price_purchase,
	                	'price_purchase_echo' => $price_purchase_echo,
	                    'price_echo' => $price_echo,
	                    'price' => $price,

	                	'price_data_id' => $value['id'],
	                    'store_count_state' => $value['store_count_state'],
	                    'weight' => $value['weight'],
	                    'reliable' => $reliable,
	                    'all_prices' => $all_prices,
	                );

                    //echo mb_strtolower(trim($value['brand'])).'<br>';
            	}
            }

            $start+=$buffer;
        }

        //exit;

        parent::getData($articul, $params);
        return $this->data;
    }
}