<?php
/**
 * Created by PhpStorm.
 * User: foreach
 * Date: 02.04.19
 * Time: 21:11
 */

class LocalSearch extends DDetailSearchModelClass
{
    public $crosses;
    public $search_brand;

    public function getBrands($article)
    {
        /**
         * Получить кроссы с брендами и синонимами
         * На выходе полчаем массивы
         * 123 => array(
         *  'article' => '123'
         *  'brand' => 'brend1'
         *  'garanty' => '0'
         * )
         */
        $crossesWithBrandsAndAliases = $this->getCrossesWithBrandsAndAliases($article, $this->search_brand);

        //echo CVarDumper::dump($crossesWithBrandsAndAliases,10,true);exit;

        /**
         * Извлекаем ключи кроссов которые являются артикулами
         */
        $crosses = array_keys($crossesWithBrandsAndAliases);

        /**
         * Извлечь из кроссов артикул который передали в поисковом запросе
         */
        if (is_array($article)) {
            foreach ($article as $v) {
                array_unshift($crosses, $v);
            }
        } else {
            array_unshift($crosses, $article);
        }
        //Бренды
        $brands = array();
        /**
         * Получить бренды и синонимы активные и не скрытые из локальной базы данных
         */
        $data = Brands::model()->cache(3600)->findAll(array('condition' => 'active_state = 1 AND hide = 0', 'select' => 'name, synonym'));

        /**
         * Наполнить массив брендов и экстрактом синонимов
         * в нижнем регистре
         * array
                (
                0 => 'mersedes'
                1 => 'mersedes - benz'
                2 => 'мерседес'
                3 => 'mercedes'
                4 => 'acdelco'
                5 => 'sangsin'
                6 => 'sangsin brake'
                7 => 'monroe'
                8 => 'vag'
                9 => 'vag china'
                10 => 'vw'
                11 => 'audi'
                12 => '555'
                )
         */
        $brands = $this->fillBrandsArray($data, $brands);

        /**
         * В этой переменной хранятся только артикулы кроссов
         * Полученные из
         */
        $slice = array_unique($crosses);

        $sliceIn = array_map(function($var){ return "'".$var."'"; }, $slice);
        $whereIn = implode(',', $sliceIn);

        $sql = "SELECT DISTINCT brand
                FROM prices_data
                JOIN prices ON prices_data.price_id=prices.id 
                JOIN stores  ON prices.store_id=stores.id 
                WHERE prices.active_state='1' 
                AND article IN ({$whereIn});";

        $resArray = Yii::app()->db->createCommand($sql)->queryColumn();

        $resArray = array_map(function($var){ return trim($var);}, $resArray);

        return $resArray;
        //return array($resArray[57]);
    }

    /**
     * @param $article
     * @param array $params
     * @return array|void
     */
    public function getData($article, $params = array())
    {
        //Получить кроссы с брендами и синонимами
        $crossesWithBrandsAndAliases = $this->getCrossesWithBrandsAndAliases($article, $this->search_brand);

        //echo CVarDumper::dump(count($crossesWithBrandsAndAliases),10,true);exit;

        //Ограничить соличество кроссов всего 1000
        if (count($crossesWithBrandsAndAliases) > 1000){
            $crossesWithBrandsAndAliases = array_slice($crossesWithBrandsAndAliases, 0, 1000, true);
        }

        /**
         * Получить артикулы кроссов в массив
         */
        if (empty($crossesWithBrandsAndAliases)) {
            $crosses = array('0');
        } else {
            $crosses = array_merge(array(0), array_keys($crossesWithBrandsAndAliases));
        }

        //echo CVarDumper::dump(count(array_unique($crosses)),10,true);exit;

        if (is_array($article)) {
            foreach ($article as $v) {
                array_unshift($crosses, $v);
            }
        } else {
            array_unshift($crosses, $article);
        }

        //Бренды
        $brands = array();

        /**
         * Получить бренды и синонимы активные и не скрытые из базы данных
         */
        $data = Brands::model()->cache(3600)->findAll(array('condition' => 'active_state = 1 AND hide = 0', 'select' => 'name, synonym'));

        /**
         * Наполнить массив брендов и экстраком синонимов
         */
        $brands = $this->fillBrandsArray($data, $brands);

        //echo CVarDumper::dump($brands,10,true);exit;

        $db = Yii::app()->db;
        $criteria = (Yii::app()->language == Yii::app()->params['default_language'] ? ' `t_price`.`language`=\'0\' ' : ' `t_price`.`language`=\''.Yii::app()->language.'\' ');

        $slice = array_unique($crosses);

        //echo CVarDumper::dump($slice,10,true);exit;

        $sliceIn = array_map(function($var){ return "'".$var."'"; }, $slice);
        $whereIn = implode(',', $sliceIn);

        $sql = "SELECT COUNT(*) 
                FROM `prices_data` `t` 
                JOIN `prices` `t_price`  ON `t`.`price_id`=`t_price`.`id` 
                JOIN `stores` `t_store`  ON t_price.store_id=`t_store`.`id` 
                WHERE `t_price`.`active_state`='1' 
                AND (`t_price`.`language`='' OR {$criteria})  
                AND `t`.`article` IN ({$whereIn});";
        //echo CVarDumper::dump($sql,10,true);exit;

        $total = $db->cache(3600)->createCommand($sql)->queryScalar();
        //echo CVarDumper::dump($total,10,true);exit;
        $start = 0;
        $buffer = 500;

        while ($start < $total) {

            /**
             * Оптимизировать нужно. Запрашивать нужно у сфинкса
             */
            $sql2 = "SELECT `t`.`id` as `id`, `t`.`name` as `name`, `t`.`brand` as `brand`, `t`.`price` as `price`, `t`.`quantum` as `quantum`, `t`.`article` as `article`, `t`.`original_article` as `original_article`, `t`.`delivery` as `delivery`, `t`.`weight` as `weight`, `t_price`.`id` as `price_id`, `t_price`.`name` as `price_name`, `t_price`.`delivery` as `price_delivery`, `t_price`.`price_group_{$params['price_group_id']}` as `price_price_group`,`t_price`.`price_group_1` as `price_price_group_1`,`t_price`.`price_group_2` as `price_price_group_2`,`t_price`.`price_group_3` as `price_price_group_3`,`t_price`.`price_group_4` as `price_price_group_4`, `t_price`.`supplier_inn` as `price_supplier_inn`, `t_price`.`supplier` as `price_supplier`, `t_price`.`currency` as `price_currency`, `t_store`.`id` AS `store_id`, `t_store`.`name` as `store_name`, `t_store`.`description` as `store_description`, `t_store`.`top` as `store_top`, `t_store`.`highlight` as `store_highlight`, `t_store`.`count_state` as `store_count_state` 
                      FROM `prices_data` `t` 
                      JOIN `prices` `t_price`  ON `t`.`price_id`=`t_price`.`id` 
                      JOIN `stores` `t_store`  ON t_price.store_id=`t_store`.`id` 
                      WHERE `t_price`.`active_state`='1' 
                      AND (`t_price`.`language`='' OR {$criteria})  
                      AND `t`.`article` IN ({$whereIn}) LIMIT {$start},{$buffer}";


            $data = $db->cache(3600)->createCommand($sql2)->queryAll();

            //echo CVarDumper::dump($crossesWithBrandsAndAliases,10,true);exit;

            /**
             * @todo пересмотреть что зедсь можно оптимизировать
             */
            foreach ($data as $value) {
                $continue = false;

                if ($this->search_brand != '') {
                    $art = mb_strtoupper(trim($value['article']));
                    //true false
                    $analog = is_array($article) ? !in_array($art, $article) : $art != $article;

                    if ($analog) {
                        $brand = mb_strtoupper(trim($value['brand']));
                        //Если артикул есть в кроссах и
                        //Бренд есть в кроссах или
                        //Ключ aliases есть в кроссе и
                        //бренд не попадает в массив алиасов
                        //Пропускаем операцию
                        if (array_key_exists($art, $crossesWithBrandsAndAliases) && (!in_array($brand, $crossesWithBrandsAndAliases[$article]['brand']) || (array_key_exists('aliases', $crossesWithBrandsAndAliases[$article]) && !in_array($brand, $crossesWithBrandsAndAliases[$article]['aliases'])))) {
                            $continue = true;
                        }
                    }
                }

                if ($continue == false) {
                    //сформировать доставку
                    $delivery = empty($value['delivery']) ? $value['price_delivery'] : (is_numeric($value['delivery']) ? ($value['delivery'] + (is_numeric($value['price_delivery']) ? $value['price_delivery'] : 0)) : $value['delivery']);

                    //$delivery = empty($value['delivery']) ? $value['price_delivery'] : $value['delivery'];
                    if ($delivery == 0 || empty($delivery)){
                        $delivery = Yii::t('detailSearch', 'Available');
                    }//Yii::app()->getModule('detailSearch')->zerosDeliveryValue;

                    //Стоимость
                    $price_purchase = Yii::app()->getModule('currencies')->getPrice($value['price'], $value['price_currency']);
                    $price_purchase_echo = Yii::app()->getModule('prices')->getPriceFormatFunction($price_purchase);
                    //Цена
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

        parent::getData($article, $params);

        return $this->data;
    }

    public function getDetail($article, $brand, $params = array())
    {
        //Получить кроссы с брендами и синонимами
        $crossesWithBrandsAndAliases = $this->getCrosses($article, $brand);

        //echo CVarDumper::dump(count($crossesWithBrandsAndAliases),10,true);
        //echo CVarDumper::dump($crossesWithBrandsAndAliases,10,true);
        //exit;

        //Ограничить соличество кроссов всего 1000
        /*if (count($crossesWithBrandsAndAliases) > 1000){
            $crossesWithBrandsAndAliases = array_slice($crossesWithBrandsAndAliases, 0, 1000, true);
        }*/

        /**
         * Получить артикулы кроссов в массив
         */
        if (empty($crossesWithBrandsAndAliases)) {
            $crosses = array('0');
        } else {
            $crosses = array_merge(array(0), array_keys($crossesWithBrandsAndAliases));
        }

        //echo CVarDumper::dump(count(array_unique($crosses)),10,true);exit;

        if (is_array($article)) {
            foreach ($article as $v) {
                array_unshift($crosses, $v);
            }
        } else {
            array_unshift($crosses, $article);
        }

        //Бренды
        $brands = array();

        /**
         * Получить бренды и синонимы активные и не скрытые из базы данных
         */
        $data = Brands::model()->cache(3600)->findAll(array('condition' => 'active_state = 1 AND hide = 0', 'select' => 'name, synonym'));

        /**
         * Наполнить массив брендов и экстраком синонимов
         */
        $brands = $this->fillBrandsArray($data, $brands);

        //echo CVarDumper::dump($brands,10,true);exit;

        $db = Yii::app()->db;
        $criteria = (Yii::app()->language == Yii::app()->params['default_language'] ? ' `t_price`.`language`=\'0\' ' : ' `t_price`.`language`=\''.Yii::app()->language.'\' ');

        $slice = array_unique($crosses);

        //echo CVarDumper::dump($slice,10,true);exit;

        $sliceIn = array_map(function($var){ return "'".$var."'"; }, $slice);
        $whereIn = implode(',', $sliceIn);

        $sql = "SELECT COUNT(*) 
                FROM `prices_data` `t` 
                JOIN `prices` `t_price`  ON `t`.`price_id`=`t_price`.`id` 
                JOIN `stores` `t_store`  ON t_price.store_id=`t_store`.`id` 
                WHERE `t_price`.`active_state`='1' 
                AND (`t_price`.`language`='' OR {$criteria})  
                AND `t`.`article` IN ({$whereIn}) and TRIM(`t`.`brand`)='{$brand}';";
        //var_dump($sql);exit;

        $total = $db->cache(3600)->createCommand($sql)->queryScalar();
        //echo CVarDumper::dump($total,10,true);exit;
        $start = 0;
        $buffer = 500;

        //while ($start < $total) {

            /**
             * Оптимизировать нужно. Запрашивать нужно у сфинкса
             */
            $sql2 = "SELECT `t`.`id` as `id`, `t`.`name` as `name`, `t`.`brand` as `brand`, `t`.`price` as `price`, `t`.`quantum` as `quantum`, `t`.`article` as `article`, `t`.`original_article` as `original_article`, `t`.`delivery` as `delivery`, `t`.`weight` as `weight`, `t_price`.`id` as `price_id`, `t_price`.`name` as `price_name`, `t_price`.`delivery` as `price_delivery`, `t_price`.`price_group_{$params['price_group_id']}` as `price_price_group`,`t_price`.`price_group_1` as `price_price_group_1`,`t_price`.`price_group_2` as `price_price_group_2`,`t_price`.`price_group_3` as `price_price_group_3`,`t_price`.`price_group_4` as `price_price_group_4`, `t_price`.`supplier_inn` as `price_supplier_inn`, `t_price`.`supplier` as `price_supplier`, `t_price`.`currency` as `price_currency`, `t_store`.`id` AS `store_id`, `t_store`.`name` as `store_name`, `t_store`.`description` as `store_description`, `t_store`.`top` as `store_top`, `t_store`.`highlight` as `store_highlight`, `t_store`.`count_state` as `store_count_state` 
                      FROM `prices_data` `t` 
                      JOIN `prices` `t_price`  ON `t`.`price_id`=`t_price`.`id` 
                      JOIN `stores` `t_store`  ON t_price.store_id=`t_store`.`id` 
                      WHERE `t_price`.`active_state`='1' 
                      AND (`t_price`.`language`='' OR {$criteria})  
                      AND `t`.`article` IN ({$whereIn}) and TRIM(`t`.`brand`)='{$brand}';";


            $data = $db->cache(3600)->createCommand($sql2)->queryAll();

            //echo CVarDumper::dump($crossesWithBrandsAndAliases,10,true);exit;

            /**
             * @todo пересмотреть что зедсь можно оптимизировать
             */
            foreach ($data as $value) {
                $continue = false;

                if ($this->search_brand != '') {
                    $art = mb_strtoupper(trim($value['article']));
                    //true false
                    $analog = is_array($article) ? !in_array($art, $article) : $art != $article;

                    if ($analog) {
                        $brand = mb_strtoupper(trim($value['brand']));
                        //Если артикул есть в кроссах и
                        //Бренд есть в кроссах или
                        //Ключ aliases есть в кроссе и
                        //бренд не попадает в массив алиасов
                        //Пропускаем операцию
                        if (array_key_exists($art, $crossesWithBrandsAndAliases) && (!in_array($brand, $crossesWithBrandsAndAliases[$article]['brand']) || (array_key_exists('aliases', $crossesWithBrandsAndAliases[$article]) && !in_array($brand, $crossesWithBrandsAndAliases[$article]['aliases'])))) {
                            $continue = true;
                        }
                    }
                }

                if ($continue == false) {
                    //сформировать доставку
                    $delivery = empty($value['delivery']) ? $value['price_delivery'] : (is_numeric($value['delivery']) ? ($value['delivery'] + (is_numeric($value['price_delivery']) ? $value['price_delivery'] : 0)) : $value['delivery']);

                    //$delivery = empty($value['delivery']) ? $value['price_delivery'] : $value['delivery'];
                    if ($delivery == 0 || empty($delivery)){
                        $delivery = Yii::t('detailSearch', 'Available');
                    }//Yii::app()->getModule('detailSearch')->zerosDeliveryValue;

                    //Стоимость
                    $price_purchase = Yii::app()->getModule('currencies')->getPrice($value['price'], $value['price_currency']);
                    $price_purchase_echo = Yii::app()->getModule('prices')->getPriceFormatFunction($price_purchase);
                    //Цена
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
        //}

        //exit;

        parent::getData($article, $params);

        return $this->data;
    }

    /**
     * Получить кроссы из локального источника и кроссы из удаленного api
     * @param $article
     * @param string $brand
     * @return array
     */
    protected function getCrossesWithBrandsAndAliases($article, $brand = '')
    {
        $temp_articul = $article;

        $brand = trim(mb_strtolower($brand));

        $db = Yii::app()->db;

        if (is_array($article)) {

            if(count($article) > 1){
                $whereIn = implode(',', $article);

                $whereIn = str_replace(', ', '", "', $whereIn);
            }else{
                $whereIn = implode(',', $article);
                $whereIn = "'".$whereIn."'";
            }


            $andBrand = ($brand != '' ? "AND t.origion_brand='$brand'" : '');


            $sql = "SELECT `origion_article` AS `article`, `origion_brand` AS `brand`, `t_base`.`garanty` AS `garanty` 
                    FROM `crosses_data` `t` 
                    JOIN `crosses_base` `t_base` ON t.base_id = t_base.id 
                    WHERE t_base.active_state = '1' 
                    AND `partsid` IN((
                        SELECT `partsid` 
                        FROM `crosses_data` `t` 
                        JOIN `crosses_base` `t_base` ON t.base_id = t_base.id 
                        WHERE t_base.active_state='1' 
                        AND t.origion_article IN ({$whereIn}) {$andBrand}
                    ))";


        } else {
            $andBrand = ($brand != '' ? "AND t.origion_brand='$brand'" : '');

            $sql = "SELECT `origion_article` AS `article`, `origion_brand` AS `brand`, `t_base`.`garanty` AS `garanty` 
                    FROM `crosses_data` `t` 
                    JOIN `crosses_base` `t_base` ON t.base_id = t_base.id 
                    WHERE t_base.active_state = '1' 
                    AND `partsid` IN((
                        SELECT `partsid` 
                        FROM `crosses_data` `t` 
                        JOIN `crosses_base` `t_base` ON t.base_id = t_base.id 
                        WHERE t_base.active_state='1' 
                        AND t.origion_article='{$article}' {$andBrand}
                    ))";
        }

        //echo CVarDumper::dump($sql,10,true);exit;

        $data = $db->cache(3600)->createCommand($sql)->queryAll();

        //echo CVarDumper::dump($data,10,true);exit;

        $this->crosses = array();

        if($brand !== ''){
            foreach ($data as $row) {
                /**
                 * Сформировать массив
                 * array(
                 *  'article' => '123'
                 *  'brand' => 'brend1'
                 *  'garanty' => '0'
                 * )
                 */
                $this->crosses[strval($row['article'])] = array(
                    'article' => $row['article'],
                    'brand'   => array($row['brand'])
                );
            }
        }else{
            foreach ($data as $row) {
                /**
                 * Сформировать массив артикулов
                 */
                $this->crosses[strval($row['article'])] = $row['article'];
            }
        }

        /**
         * получить кроссы с удаленного api
         */
        if (is_array($article)) {
            foreach ($article as $art) {
                $this->getCrossesFromUrl($art, $brand);
            }
        } else {
            $this->getCrossesFromUrl($article, $brand);
        }

        //echo CVarDumper::dump($this->crosses,10,true);exit;
        return $this->crosses;
    }


    protected function getCrosses($article)
    {
        $temp_articul = $article;

        $db = Yii::app()->db;

        if (is_array($article)) {

            $whereIn = implode(',', $article);

            $sql = "SELECT `origion_article` AS `article`, `origion_brand` AS `brand`, `t_base`.`garanty` AS `garanty` 
                    FROM `crosses_data` `t` 
                    JOIN `crosses_base` `t_base` ON t.base_id = t_base.id 
                    WHERE t_base.active_state = '1' 
                    AND `partsid` IN((
                        SELECT `partsid` 
                        FROM `crosses_data` `t` 
                        JOIN `crosses_base` `t_base` ON t.base_id = t_base.id 
                        WHERE t_base.active_state='1' 
                        AND t.origion_article IN({$whereIn})
                    ))";


        } else {
            $sql = "SELECT `origion_article` AS `article`, `origion_brand` AS `brand`, `t_base`.`garanty` AS `garanty` 
                    FROM `crosses_data` `t` 
                    JOIN `crosses_base` `t_base` ON t.base_id = t_base.id 
                    WHERE t_base.active_state = '1' 
                    AND `partsid` IN((
                        SELECT `partsid` 
                        FROM `crosses_data` `t` 
                        JOIN `crosses_base` `t_base` ON t.base_id = t_base.id 
                        WHERE t_base.active_state='1' 
                        AND t.origion_article='{$article}'
                    ))";
        }

        $data = $db->cache(3600)->createCommand($sql)->queryAll();

        //echo CVarDumper::dump($data,10,true);exit;

        $this->crosses = array();


            foreach ($data as $row) {
                /**
                 * Сформировать массив артикулов
                 */
                $this->crosses[strval($row['article'])] = $row['article'];
            }


        /**
         * получить кроссы с удаленного api
         */
        if (is_array($article)) {
            foreach ($article as $art) {
                $this->getCrossesFromUrl($art, null);
            }
        } else {
            $this->getCrossesFromUrl($article, null);
        }

        //echo CVarDumper::dump($this->crosses,10,true);exit;
        return $this->crosses;
    }

    /**
     * Получить кроссы с удаленного api
     * @param $article
     * @param $brand
     */
    protected function getCrossesFromUrl($article, $brand)
    {
        $crosses = array();

        $module = Yii::app()->getModule('search');

        $url = $module->cross_server_name.'/api/?act=articles&auth_key='.$module->cross_pass.'&article='.$article.($brand != '' ? '&brand='.$brand : '').'&brands=on&alias=on';
        //echo CVarDumper::dump($url,10,true);exit;
        //Кешируем массив $data
        if (!$crosses = Yii::app()->dbCache->get(md5($url))){

            //Получаем данные с внутреннего апи сервера
            $data = @file_get_contents($url);

            if (!empty($data)) {

                //echo CVarDumper::dump($data,10,true);exit;
                $array = $this->xmlToArray($data);

                foreach ($array as $item) {
                    //Если артикул уже присутствует в кроссах
                    if (array_key_exists($item['article'], $this->crosses)) {
                        continue;
                    } else {
                        $e = array(
                            'id' => $item['article'],
                            'article' => $item['article'],
                            'brand'   => isset($item['brand'])?array($item['brand']):array(),
                            'aliases' => array()
                        );
                    }

                    if(isset($item['aliases'])){
                        foreach ($item['aliases'] as $alias) {
                            if(!is_array($alias)){
                                $e['aliases'][] = mb_strtoupper(trim((string) $alias));
                            }else{
                                $e['aliases'] = $alias;
                            }
                        }
                    }

                    $crosses[$item['article']] = $e;
                }
            }

            Yii::app()->dbCache->set(md5($url), $crosses, 3600);
        }

        $this->crosses = $crosses;
    }

    /**
     * Заполнить массив брендов из локальной базы данных
     * @param $data
     * @param $brands
     * @return array
     */
    protected function fillBrandsArray($data, $brands)
    {
        foreach ($data as $item) {
            //если пустое имя, пропустить
            if (!$item->name){
                continue;
            }

            $name = mb_strtolower(trim($item->name));

            if (!in_array($name, $brands)) {
                $brands[] = $name;
            }

            $synonym = explode(',', $item->synonym);

            if ($synonym) {
                foreach ($synonym as $syn) {
                    //если пустое значение, пропустить
                    if(!$syn){
                        continue;
                    }

                    $name = mb_strtolower(trim($syn));

                    if (!in_array($name, $brands)) {
                        $brands[] = $name;
                    }
                }
            }
        }
        return $brands;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function xmlToArray($data)
    {
        $xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        return isset($array['article'])?$array['article']:$array;
    }
}