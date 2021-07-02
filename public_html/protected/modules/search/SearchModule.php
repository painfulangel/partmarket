<?php

class SearchModule extends CWebModule
{
    /**
     * Имя модели локального поиска
     * @var string
     */
    public $mySearchModel = 'MyLocalSearchModel';

    public $localPriceSearchClass = 'LocalSearch';

    public $apiModelClass = 'ParsersApi';

    public $modelClass = 'Parsers';

    public $parserClassName = 'parser_class';

    /**
     * Проверка доступности
     * @var
     */
    public $isMyAvailable;

    /**
     * Гарантия наверное
     * @var
     */
    public $garanty;

    /**
     * Url api сервера
     * @var string
     */
    public $cross_server_name = 'http://crosserver2.partexpert.ru';//'http://cros.partexpert.net';

    public $cross_login = 'demo';

    public $cross_pass = 'WcSCw4oJKnI0NQiw';//'2bEAigmj4A5jFzc8';

    public $activeName = 'active_state';

    public $langCriteria;



	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'search.models.*',
			'search.components.*',
		));

		$this->langCriteria = (Yii::app()->language == Yii::app()->params['default_language']) ? " `language`='0' " : " `language`='".Yii::app()->language."'";

		$this->isMyAvailable = $this->isMyAvailable($this->getSearchPhrase());
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

    /**
     * Получить кроссы из локальной базы и стороннего апи
     * Закешировать на 1 час
     * @param $article
     * @param string $brand
     * @return array
     */
	protected function getCrosses($article, $brand = '')
    {
        //echo CVarDumper::dump($article,10,true);
        $temp_article = $article;

        if($brand){
            $brand = trim(mb_strtolower($brand));
        }

        if(!$crosses = Yii::app()->dbCache->get('getCrosses_in_search_module')){
            $db = Yii::app()->db;
            if (is_array($article)) {

                $whereIn = $this->convertToWhereIn($article);

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
                        AND t.origion_article IN({$whereIn}) {$andBrand}
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

            $data = $db->createCommand($sql)->queryAll();

            //$dependency = new CDbCacheDependency('SELECT COUNT(id) FROM crosses_data');

            //$data = $db->cache(3600, $dependency, 20)->createCommand($sql)->queryAll();

            $crosses = array();
            $garanty = array();

            foreach ($data as $row) {
                $crosses[$row['article']] = $row['article'];
                if ($row['garanty'] == '1') {
                    $garanty[$row['article']] = $row['article'];
                }
            }

            $this->garanty = $garanty;

            //echo CVarDumper::dump($crosses,10,true);
            //echo CVarDumper::dump($garanty,10,true);exit;

            $article = $temp_article;

            if (is_array($article)) {

                foreach ($article as $art) {

                    /**
                     * Делаем запрос к api и дописываем в массив кроссов
                     */
                    $url = "$this->cross_server_name/getarts.php?num=$art&u=$this->cross_login&p=$this->cross_pass&full=on&metrika=011".($brand != '' ? '&brand='.$brand : '');

                    //Кешируем массив $data
                    if (!$data = Yii::app()->cache->get(md5($url))){
                        //Получаем данные с внутреннего апи сервера
                        $data = @file_get_contents($url);

                        Yii::app()->cache->set(md5($url), $data, 3600);
                    }

                    //echo CVarDumper::dump($data,10,true);

                    if (!empty($data)) {
                        $data = explode("<br>", $data);

                        $data = array_map(function ($var) {
                            return preg_replace("/[^a-zA-Z0-9]/", "", $var);
                        }, $data);
                        //echo CVarDumper::dump($data,10,true);
                        //Дополняем массив артикулов из кроссов
                        $crosses = array_merge($crosses, $data);
                    }
                }
            } else {
                $url = "$this->cross_server_name/getarts.php?num=$article&u=$this->cross_login&p=$this->cross_pass&full=on&metrika=011".($brand != '' ? '&brand='.$brand : '');

                //Кешируем массив $data
                if (!$data = Yii::app()->cache->get(md5($url))){
                    //Получаем данные с внутреннего апи сервера
                    $data = @file_get_contents($url);

                    Yii::app()->cache->set(md5($url), $data, 3600);
                }

                if (!empty($data)) {
                    $data = explode("<br>", $data);

                    $data = array_map(function ($var) {
                        return preg_replace("/[^a-zA-Z0-9]/", "", $var);
                    }, $data);
                    //echo CVarDumper::dump($dataTmp,10,true);
                    //echo CVarDumper::dump($data,10,true);
                    $crosses = array_merge($crosses, $data);
                }

            }

            Yii::app()->dbCache->get('getCrosses_in_search_module', $crosses, 3600);
        }

        //echo CVarDumper::dump($crosses,10,true);exit;

        return $crosses;
    }

    /**
     * @param $article
     */
    protected function convertToWhereIn($article)
    {
        $article = array_unique($article);
        $sliceIn = array_map(function ($var) {
            return '"' . $var . '"';
        }, $article);
        $whereIn = implode(',', $sliceIn);
        return $whereIn;
    }

    /**
     * Проверка доступности детали по артикулу
     * Результат кешируется на 1 час
     * @param $article
     * @param array $params
     * @return bool
     */
    protected function isMyAvailable($article, $params = array())
    {
        //return false;
        /**
         * Получить невъебенный массив артикулов из локальной базы кроссов и из базы апи
         */
        $crosses = $this->getCrosses($article);

        if(!$total = Yii::app()->dbCache->get('isMyAvailable')){
            /**
             * Добавить искомый артикул в начало массива
             */
            if (is_array($article)) {
                foreach ($article as $v) {
                    array_unshift($crosses, $v);
                }
            } else {
                array_unshift($crosses, $article);
            }

            /**
             * Конвертировать массив артикулов в WHERE IN ('1','2','3', etc)
             */
            $crosses = $this->convertToWhereIn($crosses);

            $criteria = (Yii::app()->language == Yii::app()->params['default_language'] ? ' `t_price`.`language`=\'0\' ' : ' `t_price`.`language`=\''.Yii::app()->language.'\' ');

            $sql = "SELECT COUNT(*) 
                FROM `prices_data` `t` 
                JOIN `prices` `t_price`  ON `t`.`price_id`=`t_price`.`id` 
                JOIN `stores` `t_store`  ON t_price.store_id=`t_store`.`id` 
                WHERE `t_store`.`my_available`='1' 
                AND `t_price`.`active_state`='1'  
                AND (`t_price`.`language`='' OR {$criteria})  
                AND  `t`.`article` IN ({$crosses}); ";

            $total = Yii::app()->db->cache(3600)->createCommand($sql)->queryScalar();

            Yii::app()->dbCache->set('isMyAvailable', $total, 3600);
        }

        return $total > 0;
    }

    /**
     * @return array
     */
    protected function getSearchPhrase()
    {
        $search_phrase = isset($_GET['search_phrase']) ? $_GET['search_phrase'] : '';
        $search_phrase = str_replace(array("\s", ' '), '', $search_phrase);
        $search_phrases = explode(',', $search_phrase);
        //echo CVarDumper::dump($search_phrases,10,true);
        return $search_phrases;
    }
}
