<?php
class ParsersApiModule extends CWebModule {
    public $server_name = 'http://price.partexpert.ru/api/v1/engine.php';
    public $key;
    public $timelimit = 120;
    public $price_group_1 = 1;
    public $price_group_2 = 1;
    public $price_group_3 = 1;
    public $price_group_4 = 1;
    public $delivery = 1;
    public $currency = 0;
    public $supplier_inn = 'Новый парсер';
    public $supplier = 'Новый парсер';

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'parsersApi.models.*',
            'parsersApi.components.*',
        ));

        $this->key = Yii::app()->config->get('ParserApi.Key');
        for ($i = 1; $i <= 4; $i++)
            $this->{'price_group_' . $i} = Yii::app()->config->get('ParserApi.price_group_' . $i);
        $this->delivery = Yii::app()->config->get('ParserApi.delivery');
        $this->currency = Yii::app()->config->get('ParserApi.currency');
        $this->supplier_inn = Yii::app()->config->get('ParserApi.supplier_inn');
        $this->supplier = Yii::app()->config->get('ParserApi.supplier');
    }

    public function getPriceFunction($value) {
        return Yii::app()->getModule('pricegroups')->getPrice(Yii::app()->getModule('currencies')->getPrice($value['price'], $value['currency']), $value['price_group'], $value['brand']);
    }

    /**
     *
     * @var string code to echo price value 
     */
    public function getPriceFormatFunction($price) {
        return Yii::app()->getModule('currencies')->getFormatPrice($price);
    }

    public function getSupplierDataList($article, $id, $brand = '') {
        $request_array = array();
        if (is_array($article)) {
            foreach ($article as $v) {
                $request_array[] = array(
                    'supplier' => $id,
                    'number' => $v,
                    'brand' => $brand != '' ? $brand : '1');
            }
        } else {
            $request_array[] = array(
                'supplier' => $id,
                'number' => $article,
                'brand' => $brand != '' ? $brand : '1');
        }
        
        $request = array(
            'method' => 'SupplierCrossPrice',
            'key' => $this->key,
            'timeout' => $this->timelimit,
            'request' => $request_array,
        );
        
        //ob_start(); echo '<pre>'; print_r($request); echo '</pre>';
        //file_put_contents(Yii::getPathOfAlias('webroot').'/info.txt', ob_get_clean()."\n\n", FILE_APPEND);
        
        $data = $this->getApiRequest($request);
        
        if ($this->analyseData($data)) {
            $return = array();

            if (array_key_exists($id, $data['response'])) {
                $data = $data['response'][$id];
                foreach ($data as $v) {
                    foreach ($v as $v2) {
                        $return[] = $v2;
                    }
                }
            }
            
            return $return;
        } else
            return array();
    }
    
    public function getBrandDataList($article, $id) {
    	$request_array = array();
    	if (is_array($article)) {
    		foreach ($article as $v) {
    			$request_array[] = array(
    				'supplier' => $id,
    				'number'   => $v,
    				'type'     => 'brands',);
    		}
    	} else {
    		$request_array[] = array(
    			'supplier' => $id,
    			'number'   => $article);
    	}
    	
    	$request = array(
    		'method'  => 'SupplierCrossBrand',
    		'key'     => $this->key,
    		'timeout' => $this->timelimit,
    		'request' => $request_array,
    	);
    	
    	$data = $this->getApiRequest($request);
    	
    	if ($this->analyseData($data)) {
            $return = array();

            if (array_key_exists($id, $data['response'])) {
                $data = $data['response'][$id];
                foreach ($data as $v) {
                    $v['article'] = mb_strtoupper($v['article']);
                    $v['brand'] = str_replace(' ', '', $v['brand']);
                    $v['brand_link'] = str_replace('/', '__', str_replace('%2F', '/', $v['brand']));
                    $return[] = $v;
                }
            }
    		
    		return $return;
    	} else
    		return array();
    }

    public function getAvailableSupplierList() {
        $request = array(
            'method' => 'SuppliersList',
            'key' => $this->key,
            'timeout' => $this->timelimit,
            'request' => array(
            )
        );

        $data = $this->getApiRequest($request);
        if ($this->analyseData($data))
            return $data['response'];
        else
            return array();
    }

    public function getAllSupplierList() {
        $request = array(
            'method' => 'FullSuppliersList',
            'key' => $this->key,
            'timeout' => $this->timelimit,
            'request' => array(
            )
        );

        $data = $this->getApiRequest($request);
        if ($this->analyseData($data))
            return $data['response'];
        else
            return array();
    }

    public function UpdateData() {
        $data = $this->getAllSupplierList();
        $temp = ' 0 ';
        $map = array();
        foreach ($data as $key => $value) {
            $temp.=" or supplier_code='$value[code]' ";
            $map[$value['code']] = $key;
        }
        $db = Yii::app()->db;
        $tableName = ParsersApiAll::model()->tableName();
        $sql = "SELECT `id`, `supplier_code` FROM `$tableName` WHERE $temp";
        $data_res = $db->createCommand($sql)->queryAll();
        foreach ($data_res as $value) {
            unset($map[$value['supplier_code']]);
        }
//        print_r($data);
//        print_r($data);

        if (!(isset($data['error']['msg']) && isset($data['error']['code'])))
            foreach ($map as $value) {
                $model = new ParsersApiAll;
                $model->name = $data[$value]['name'];
                $model->supplier_code = $data[$value]['code'];
                $model->site_url = $data[$value]['website'];
                $model->save();
            }
//        die;
        $data = $this->getAvailableSupplierList();
        $temp = ' 0 ';
        $map = array();
        foreach ($data as $key => $value) {
            $temp.=" or supplier_code='$value[code]' ";
            $map[$value['code']] = $key;
        }
        $db = Yii::app()->db;
        $tableName = 'parsers_api';

        $sql = "SELECT `id`, `supplier_code` FROM `$tableName` WHERE active_state=1 and not( $temp )";
        $data_res = $db->createCommand($sql)->queryAll();
        $temp2 = ' 0 ';
        foreach ($data_res as $value) {
            $temp2.=" or supplier_code='$value[supplier_code]' ";
            unset($map[$value['supplier_code']]);
        }
        $sql = "UPDATE `$tableName` SET `active_state`='0' WHERE $temp2";
        $db->createCommand($sql)->query();

        $sql = "SELECT `id`, `supplier_code` FROM `$tableName` WHERE $temp";
        $data_res = $db->createCommand($sql)->queryAll();
        foreach ($data_res as $value) {
            unset($map[$value['supplier_code']]);
        }
//        print_r($data);
//        die;
        if (!isset($data['error']['msg']) && !isset($data['error']['code']))
            foreach ($map as $value) {
                $model = new ParsersApi;
                $model->name = $data[$value]['name'];
                $model->supplier_code = $data[$value]['code'];
                for ($i = 1; $i <= 4; $i++)
                    $model->{'price_group_' . $i} = $this->{'price_group_' . $i};
                $model->delivery = $this->delivery;
                $model->currency = $this->currency;
                $model->supplier_inn = $this->supplier_inn;
                $model->supplier = $this->supplier;
                $model->save();
            }
    }

    public function analyseData($data) {
        if (empty($data['response']))
            return false;
        foreach ($data['response'] as $key => $value) {
            if (isset($value['error'])) {
                Yii::log('ParsersApiModule: error_code=' . $value['error']['code'] . ' error_msg=' . $value['error']['msg'], CLogger::LEVEL_INFO, 'parsersApi');
                return false;
            }
        }

        return true;
    }

    public function getApiRequest($request) {
        $post = json_encode($request);
        
        //echo '<pre>'; print_r($request); echo '</pre>';
        
        $ch = curl_init($this->server_name);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $data = curl_exec($ch);

        //echo $data; exit;
        
        curl_close($ch);
        
        return CJSON::decode($data, true);
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }
}