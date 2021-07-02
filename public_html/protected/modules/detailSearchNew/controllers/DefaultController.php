<?php
class DefaultController extends Controller {
    public function actionJsMainScript() {
        $this->renderPartial('js_main');
    }
    
	// /art/A0000101285
    public function actionSearch($article = '') {
    	if (Yii::app()->config->get('Site.SearchType') != 1) {
    		$this->redirect(array('/detailSearch/default/search', 'search_phrase' => $article));
    	}
    	
        $p = new CHtmlPurifier();
        $p->options = array('URI.AllowedSchemes' => array(
                'http' => true,
                'https' => true,
        ));
        $article = $p->purify($article);
        $article = str_replace('__', '/', $article);
        
        //Meta data
        $article_search = preg_replace('/[^a-zа-я\d]+/ui', '', mb_strtolower(trim($article)));
        
        $pdm = PricesDataMeta::model()->findByAttributes(array('article_search' => $article_search));
        //Meta data

        $this->render('search', array('search_phrase' => $article, 
        							  'brand'         => '',
                                      'pdm'           => $pdm));
    }
    
    public function actionSearchBrand($brand, $article) {
    	if (Yii::app()->config->get('Site.SearchType') != 1) {
    		$this->redirect(array('/detailSearch/default/search', 'search_phrase' => $article));
    	}
    	
    	$brand = str_replace('__', '/', $brand);
    	
    	Yii::import('application.modules.prices.models.PricesDataMeta');
    	
    	$p = new CHtmlPurifier();
    	$p->options = array('URI.AllowedSchemes' => array(
    			'http' => true,
    			'https' => true,
    	));
    	
    	$article = $p->purify($article);
    	
    	//Meta data
    	$article_search = preg_replace('/[^a-zа-я\d]+/ui', '', mb_strtolower(trim($article)));
    	
    	$pdm = PricesDataMeta::model()->findByAttributes(array('article_search' => $article_search));
    	
    	if (is_object($pdm) && $pdm->brand && $pdm->brand != strtoupper($brand)) {
    		$pdm = null;
    	}
    	//Meta data
    	
    	//Currency
    	$based = null;
    	$clist = array();
    	 
    	if (!Yii::app()->user->isGuest) {
    		$currencies = Yii::app()->getModule('currencies');
    	
    		$based = $currencies->getUserCurrency();
    	
    		$clist = Currencies::model()->findAll(array('condition' => 'visibility_state = 1', 'order' => 'basic DESC'));
    	}
    	//Currency
    	
    	$this->render('search', array('search_phrase' => $article, 
    								  'article'       => $article, 
    								  'brand'         => $brand, 
    								  'pdm'           => $pdm,
    								  'based'         => $based,
    								  'clist'         => $clist));
    }

    /**
     * Возвращает склады
     * {"time_start":"1554143577636","sklads_count":2,"sklads":["local","api_12"]}
     */
    public function actionGetSkladList() {
        set_time_limit(300);
        $timelimit = Yii::app()->config->get("Site.DetailSearchTimeout");
        $timelimit = preg_replace("/[^0-9]/", "", $timelimit);
        if (!empty($timelimit))
            set_time_limit($timelimit);
		//Yii::beginProfile('blockId');
        $db = Yii::app()->db;
        $time_start = $_GET['time_start'];

        $search_phrase = $_GET['search_phrase'];
        $search_phrase = str_replace(array("\s", ' '), '', $search_phrase);
        $search_phrases = explode(',', $search_phrase);
		//$search_phrases = array_flip($search_phrases);
        //В этом модуле это класс LocalMySearchModel
        //echo CVarDumper::dump(Yii::app()->controller->module,10,true);
        $temp = Yii::app()->controller->module->localMyPriceSearchClass;
        //echo CVarDumper::dump($temp,10,true);exit;
        $my = new $temp;//new LocalMySearchModel()
        $flag = false;

        /**
         * Здесь только проверяем наличие, не ищем детали
         * @todo Очень тяжелый запрос к api только лишь дя тог что бы получить количество
         */
        foreach ($search_phrases as $search_phrase) {
            //
            if ($my->checkMyAvailable($search_phrase)) {
                $flag = true;
            }
        }

        $sklad_list = array();

		//throw new CHttpException;

        if ($flag) {
            $sklad_list = array('local_my');
        } else {
            $activeName = Yii::app()->controller->module->activeName;

            $criteria = (Yii::app()->language == Yii::app()->params['default_language'] ? ' `language`=\'0\' ' : ' `language`=\''.Yii::app()->language.'\' ');
            /*if (Yii::app()->user->checkAccess('mainManager') || Yii::app()->user->checkAccess('admin')) {
                $criteria = 1;
            }*/

            /**
             * Делаем не понятные манипуляции с парсерами
             */
            $sql = "SELECT `id` FROM `parsers` WHERE `$activeName`='1' AND (`language`='' OR $criteria)";
            $data = $db->createCommand($sql)->queryAll();
            $sklad_list = array(0 => 'local');
			//$sklad_list = array();

            foreach ($data as $row) {
                $sklad_list[] = $row['id'];
            }
            /*$className = Yii::app()->controller->module->apiModelClass;
            $model = new $className;
            $tableName = $model->tableName();*/
            $activeName = Yii::app()->controller->module->activeName;
            $sql = "SELECT `id` FROM `parsers_api` WHERE `$activeName`='1' AND `admin_active_state`='1' AND (`language`='' OR $criteria) ";
            $data = $db->createCommand($sql)->queryAll();
            foreach ($data as $row) {
                $sklad_list[] = 'api_'.$row['id'];
            }
        }
		//Yii::endProfile('blockId');
        header('Content-type: application/json');
        echo CJSON::encode(array('time_start' => $time_start, 'sklads_count' => count($sklad_list), 'sklads' => $sklad_list));
    }

    public function actionGetBrandList() {
    	$search_sklad = $_GET['search_sklad'];
    	
    	$search_phrase = $_GET['search_phrase'];
    	$search_phrase = str_replace(array("\s", ' '), '', $search_phrase);
    	$search_phrases = explode(',', $search_phrase);
    	
    	$brands = array();
    	
    	if (strpos('!!'.$search_sklad, 'local')) {
    	    //1. Сначала в кроссах
	    	//$crosses = Yii::app()->getModule('crosses');
	    	
	    	$count = count($search_phrases);
	    	/*for ($i = 0; $i < $count; $i ++) {
	    		$article_search = $search_phrases[$i];
	    		
	    		$brands = array_merge($brands, $crosses->getBrands($article_search));
	    	}*/
	    	
	    	//2. Затем в БД
	    	$model = $this->loadModel($search_sklad);

            /**
             * @todo Дальше все запросы идут по api потому тормоза
             */
	    	//echo CVarDumper::dump($model,10,true);exit;
	    	
	    	for ($i = 0; $i < $count; $i ++) {
	    	    $article_search = trim(mb_strtoupper($search_phrases[$i]));

                /**
                 * Здесь так же обращение идет к api внешних ресурсов
                 */
    	    	$res = $model->getData($article_search, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => $search_sklad));
    	    	
    	    	foreach ($res as $key => $value) {
    	    	    $brand = mb_strtoupper(trim($value['brand']));
    	    	    
    	    	    $article = trim(mb_strtoupper($value['articul']));
    	    	    
    	    	    //if ($article != $article_search) continue;
    	    	    
    	    	    if (!array_key_exists($brand, $brands)) {
    	    	        $brands[$brand] = array('brand'      => $brand,
                        	    	            'brand_link' => str_replace('/', '__', str_replace('%2F', '/', $brand)),
                        	    	            'name'    	 => (string) $value['name'],
                        	    	            'article'    => $article);
    	    	    }
    	    	}
	    	}

	    	ksort($brands);

	    	
	    	$brands = array_values($brands);

    	} else if (strpos('!!'.$search_sklad, 'api')) {
    		$model = $this->loadModel($search_sklad);
    		//ParserApiSearchModel
    		$brands = $model->getBrandData($search_phrases);
    		
    		//echo '<pre>'; print_r($brands); echo '</pre>'; exit;
    	}

        //Синонимы брендов
        if ($count = count($brands)) {
            //if (array_key_exists('i', $_GET)) {
                $draft = array();
                $syns = array();

                $bs = Brands::model()->findAll(array('condition' => 'active_state = 1 AND synonym != ""', 'select' => 'name, synonym'));
                $count2 = count($bs);
                for ($i = 0; $i < $count2; $i ++) {
                    $syns[mb_strtolower(trim($bs[$i]->name))] = array_map('mb_strtolower', array_map('trim', explode(',', $bs[$i]->synonym)));
                }

                for ($i = 0; $i < $count; $i ++) {
                    $brand = mb_strtolower(trim($brands[$i]['brand']));

                    if (!array_key_exists($brand, $syns)) {
                        //Бренд не является главным, проверяем среди синонимов
                        foreach ($syns as $main => $sns) {
                            if (in_array($brand, $sns)) {
                                //Бренд нашёлся среди синонимов
                                //Нужен главный бренд
                                $is = false;

                                for ($j = 0; $j < $count; $j ++) {
                                    $brand2 = mb_strtolower(trim($brands[$j]['brand']));

                                    if ($brand2 == $main) $is = true;
                                }

                                //echo $brand.' - '.$main.' - '.intval($is).'<br>';

                                if ($is) {
                                    //Главный бренд уже есть, удаляем текущий
                                } else {
                                    //Главного бренда нет, переименовываем текущий
                                    $brands[$i]['brand'] = mb_strtoupper($main);
                                    $brands[$i]['brand_link'] = str_replace('/', '__', str_replace('%2F', '/', $brands[$i]['brand']));
                                    $draft[] = $brands[$i];
                                }
                            }
                        }
                    } else {
                        //Главный бренд
                        $draft[] = $brands[$i];
                    }
                }

                $brands = $draft;

                //echo '<pre>'; print_r($brands); print_r($syns); echo '</pre>'; exit;
            //}
        }
        //Синонимы брендов
        //echo CVarDumper::dump($brands,10,true);
        //Исключаем бренды, которые необходимо скрыть
        $hidden = $this->getHiddenBrands();

        if (count($hidden)) {
            $clear = array();

            $count = count($brands);
            for ($i = 0; $i < $count; $i ++) {
                $brand = mb_strtolower(trim($brands[$i]['brand']));

                if (!in_array($brand, $hidden)) {
                    $clear[] = $brands[$i];
                }
            }

            $brands = $clear;
        }
    	//Исключаем бренды, которые необходимо скрыть

    	header('Content-type: application/json');
    	echo CJSON::encode(array('brands' => $brands));
    }

    private function getHiddenBrands() {
        $hidden = array();

        $hide = Brands::model()->findAll(array('condition' => 'active_state = 1 AND hide = 1', 'select' => 'name, synonym'));

        $count = count($hide);
        for ($i = 0; $i < $count; $i ++) {
            $name = mb_strtolower(trim($hide[$i]->name));

            if (!in_array($name, $hidden)) $hidden[] = $name;

            $synonym = array_map('mb_strtolower', array_map('trim', explode(',', $hide[$i]->synonym)));

            $count2 = count($synonym);
            for ($j = 0; $j < $count2; $j ++) {
                $name = mb_strtolower(trim($synonym[$j]));

                if (!in_array($name, $hidden)) $hidden[] = $name;
            }
        }

        return $hidden;
    }
    
    public function actionGetProductList() {
        set_time_limit(300);
        $timelimit = Yii::app()->config->get("Site.DetailSearchTimeout");
        $timelimit = preg_replace("/[^0-9]/", "", $timelimit);
        if (!empty($timelimit))
            set_time_limit($timelimit);
        $time_start = $_GET['time_start'];
        $search_sklad = $_GET['search_sklad'];
        $search_phrase = $_GET['search_phrase'];
        $search_phrase = str_replace(array("\s", ' '), '', $search_phrase);
        $search_phrases = explode(',', $search_phrase);
        $full_list = $search_phrases;
        $search_phrases = array_flip($search_phrases);
		
        $search_brand = array_key_exists('search_brand', $_GET) ? strtolower(trim($_GET['search_brand'])) : '';
        
        $products = array();
        $products_other = array();

        $new_list = array();
        
        //Best prices for different brands
        $all_products = array();
		$all_products_other = array();

        $hidden = $this->getHiddenBrands();
		
        if (strpos('!!'.$search_sklad, 'local') || strpos('!!'.$search_sklad, 'api')) {
			//if (strpos('!!'.$search_sklad, 'local')) {
            foreach ($full_list as $k => $v) {
                $v = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $v));
                if (!empty($v)) {
                    $full_list[$k] = $v;
                    if ($v[0] == '0') {
                        $new_list[] = substr($v, 1);
                    }
                } else {
                    unset($full_list[$k]);
                }
            }
            
            foreach ($new_list as $temp_art) {
                $full_list[] = $temp_art;
            }
			
            if (count($full_list) > 0) {
                $model = $this->loadModel($search_sklad);
                
                if ($search_brand) $model->search_brand = $search_brand;
                
                $res = $model->getData($full_list, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => $search_sklad));
                foreach ($res as $key => $value) {
                    $value['brand'] = mb_strtoupper($value['brand']);

                    $brand = mb_strtolower(trim($value['brand']));

                    if (!in_array($brand, $hidden)) {
                        $temp = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $value['articul']));
                        
                        if ($search_brand != '') {
                        	if (isset($search_phrases[$temp])) {
                        		$all_products[] = $value;
                        	} else {
                        		$all_products_other[] = $value;
                        	}
                        	
                        	if (isset($search_phrases[$temp])) {
                        		$posbrand = str_replace(' ', '', strtolower(trim($value['brand'])));
                        		
                        		if ((strpos($posbrand, $search_brand) === false) && (strpos($search_brand, $posbrand) === false)) continue;
                        	}
                        } else {
                        	/*if (!in_array($temp, $full_list)) {
                        		continue;
                        	}*/
                        }
                        
                        if (isset($search_phrases[$temp])) {
                            $products[] = $value;
                        } else {
                            $products_other[] = $value;
                        }
                    }
                }
            }
        } else {
            foreach ($search_phrases as $search_phrase => $temp_search_phrase) {
                $search_phrase = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $search_phrase));
                
                if (!empty($search_phrase)) {
                    $model = $this->loadModel($search_sklad);
                    
                    if ($search_brand) $model->search_brand = $search_brand;
                    
                    $res = $model->getData($search_phrase, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => $search_sklad));
                    foreach ($res as $key => $value) {
                    	$value['brand'] = mb_strtoupper($value['brand']);
                    	
                        $brand = mb_strtolower(trim($value['brand']));

                        if (!in_array($brand, $hidden)) {
                            $temp = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $value['articul']));
                            
    	                    if ($search_brand != '') {
    	                    	if (isset($search_phrases[$temp])) {
    	                    		$all_products[] = $value;
    	                    	} else {
    	                    		$all_products_other[] = $value;
    	                    	}
    							
    	                    	if (isset($search_phrases[$temp])) {
    	                    		$posbrand = str_replace(' ', '', strtolower(trim($value['brand'])));
    	                    		
    	                    		if ((strpos($posbrand, $search_brand) === false) && (strpos($search_brand, $posbrand) === false)) continue;
    	                    	}
    	                    } else {
    	                    	if ($temp != $search_phrase) {
    	                    		continue;
    	                    	}
    	                    }
                        
                            if (isset($search_phrases[$temp])) {
                                $products[] = $value;
                            } else {
                                $products_other[] = $value;
                            }
                        }
                    }
                }
            }
        }
        
        //Best prices for different brands
        $best_prices = array();
        $min_prices = array();
        
        if (count($all_products) || count($all_products_other)) {
        	$items = array_merge($all_products, $all_products_other);
        	
        	$count = count($items);
        	
        	for ($i = 0; $i < $count; $i ++) {
        		$brand = trim(mb_strtoupper($items[$i]['brand']));
        		$price = floatval($items[$i]['price']);
        		
        		if (!array_key_exists($brand, $min_prices) || ($price < $min_prices[$brand])) {
        			$min_prices[$brand] = $price;
        				
        			$best_prices[$brand] = $items[$i];
        		}
        	}
        }
        //Best prices for different brands
        
        header('Content-type: application/json');

        echo CJSON::encode(array('time_start'           => $time_start, 
        						 'products_count'       => count($products), 
        						 'products'             => $products, 
        						 'products_other_count' => count($products_other), 
        						 'products_other'       => $products_other,
        						 'best_prices'          => $best_prices));
    }
	
    public function loadModel($id) {
        if ($id == 'local') {
            //класс LocalSearchModel
            $temp = Yii::app()->controller->module->localPriceSearchClass;
            return new $temp;
        }
        if ($id == 'local_my') {
            //LocalMySearchModel
            $temp = Yii::app()->controller->module->localMyPriceSearchClass;
            return new $temp;
        }

        //$model = null;
        if (str_replace('api_', '', $id) != $id) {
            //ParsersApi
            $model = CActiveRecord::model(Yii::app()->controller->module->apiModelClass)->findByPk(str_replace('api_', '', $id));
            $temp = new ParserApiSearchModel;
        } else {
            //Parsers
            $model = CActiveRecord::model(Yii::app()->controller->module->modelClass)->findByPk($id);
            $temp = new $model->{Yii::app()->controller->module->parserClassName};
        }
        if ($model == null)
            throw new CHttpException(404, Yii::t('detailSearch', 'This page doesn\'t exist.'));
        // $temp = new $model->{Yii::app()->controller->module->parserClassName};
        $temp->model = $model;
        
        //ParserApiSearchModel - ParsersApi
        //file_put_contents(Yii::getPathOfAlias('webroot').'/info.txt', get_class($temp).' - '.get_class($model)."\n\n", FILE_APPEND);
            
        return $temp;
    }
}