<?php
class ParserSearchModel extends DDetailSearchModelClass {
    public function getData($articul, $params = array()) {
        $data = $this->data;
        $this->data = array();
        
        $prefix = false;
        
        $parser_id = 0;
        
        if (array_key_exists('id', $params)) {
            if ((strpos($params['id'], 'api') !== false) && ($parser_id = intval(str_replace('api_', '', $params['id'])))) {
                $store = ParsersApi::model()->findByPk($parser_id);
        		
        		if (is_object($store))
        			$prefix = intval($store->show_prefix) == 1;
        	}
        }
        
        if (is_array($data))
            foreach ($data as $value) {
            	if (!array_key_exists('number', $value) || !array_key_exists('brand', $value)) continue;
            	 
				//$delivery = is_numeric($value['days']) ? $value['days'] : (0 + is_numeric($this->model->delivery) ? $this->model->delivery : 0);
                $delivery = empty($value['days']) ? $this->model->delivery : (is_numeric($value['days']) ? ($value['days'] + (is_numeric($this->model->delivery) ? $this->model->delivery : 0)) : $value['days']);

                if ($delivery == 0 || empty($delivery))
                    $delivery = Yii::app()->getModule('detailSearch')->zerosDeliveryValue;

                $reliable = '';
                if (isset($value['price_supplier_inn']))
                    $reliable = Reliability::model()->getReliability($value['price_supplier_inn']);
                
                $price_purchase = Yii::app()->getModule('currencies')->getPrice($value['price'], $this->model->currency);
                $price_purchase_echo = Yii::app()->getModule('prices')->getPriceFormatFunction($price_purchase);
                
                $price = Yii::app()->getModule('parsersApi')->getPriceFunction(array('brand' => $value['brand'], 'price' => $value['price'], 'currency' => $this->model->currency, 'price_group' => $this->model->{'price_group_' . $params['price_group_id']}));
                $price_echo = Yii::app()->getModule('parsersApi')->getPriceFormatFunction($price);
				
                //$this->data[$value['number']] = array(
                $all_prices = array();
                for ($pi = 1; $pi <= 4; $pi++) {
                    $temp_price = Yii::app()->getModule('parsersApi')->getPriceFunction(array('brand' => $value['brand'], 'price' => $value['price'], 'currency' => $this->model->currency, 'price_group' => $this->model->{'price_group_' . $pi}));
                    $all_prices[] = array(
                        'price_group' => $pi,
                        'price' => $temp_price,
                        'price_echo' => Yii::app()->getModule('parsersApi')->getPriceFormatFunction($temp_price),
                    );
                }
                
                $this->data[] = array(
                    'price_group_1'  => $this->model->price_group_1,
                    'price_group_2'  => $this->model->price_group_2,
                    'price_group_3'  => $this->model->price_group_3,
                    'price_group_4'  => $this->model->price_group_4,
                    'supplier_price' => $value['price'] * Yii::app()->params['MultiKoefSuplierPrice'],
                    'articul_order'  => strtoupper($value['number']),
                    'supplier_inn'   => $this->model->supplier_inn,
                    'supplier'       => $this->model->supplier,
                    
                    'parser_id'      => $parser_id,
                	'store'          => $this->model->supplier.($prefix && array_key_exists('pricelogo', $value) ? '_'.$value['pricelogo'] : ''),
                    'name'           => $value['descr'],
                    'brand'          => $value['brand'],
                    'articul'        => strtoupper($value['number']),
                    'dostavka'       => $delivery,
                    'kolichestvo'    => $value['stock'],
                		
                	'price_purchase' => $price_purchase,
                	'price_purchase_echo' => $price_purchase_echo,
                	'price_echo'     => $price_echo,
                    'price'          => $price,
                    
                	'price_data_id'  => 0,
                    'store_count_state' => 0,
                    'weight'         => 0,
                    'reliable'       => $reliable,
                    'all_prices'     => $all_prices,
                		
                	'ddpercent'      => array_key_exists('ddpercent', $value) ? $value['ddpercent'] : '',
                );
            }
        parent::getData($articul, $params);
    }
}