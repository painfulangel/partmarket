<?php
Yii::import('userControl.models.UsersApiAccess');
class OrderApiController extends Controller {
	public function actionGetOrdersInfo() {
		$uid = $this->login();
		if($uid > 0) {
			if(isset($_GET['order_id'])) {
				echo $this->getOrdersInfo($uid, $_GET['order_id']);
				die();
			} else {
				echo $this->getOrdersInfo($uid);
				die();
			}
		}
	}
	
	public function actionGetProducts($search_phrase = '') {
		$uid = $this->login();
		if($uid > 0) {
			echo $this->getProductList($search_phrase, $uid);
			die();
		}
		echo CJSON::encode(array(
			'status' => "0" 
		));
	}
	
	public function actionMakeOrder($search_phrase = '') {
		$uid = $this->login();
		if($uid > 0) {
			if(isset($_POST['make_order'])) {
				echo $this->makeOrder($uid, $_POST['make_order']);
				die();
			}
		}
		echo CJSON::encode(array(
				'status' => "0" 
		));
	}
	
	public function actionRefuseOrder($search_phrase = '') {
		$uid = $this->login();
		if($uid > 0) {
			if(isset($_GET['order_id'])) {
				echo $this->refuseOrder($uid, $_GET['order_id']);
				die();
			}
		}
		echo CJSON::encode(array(
				'status' => "0" 
		));
	}
	
	public function login() {
		set_time_limit(600);
		if(isset($_POST['access_token'])) {
			$access_token = $_POST['access_token'];
			$model = UsersApiAccess::model()->findByAttributes(array(
					'access_token' => $access_token 
			));
			if($model == NULL) {
				echo CJSON::encode(array(
						'status' => "100" 
				));
				die();
			}
			if($model->active_state != '1') {
				echo CJSON::encode(array(
						'status' => "105" 
				));
				die();
			}
			return $model->user_id;
		}
		echo CJSON::encode(array(
				'status' => "0" 
		));
		die();
	}
	
	public function refuseOrder($uid, $order_id) {
		$model = new OrdersStatus();
		$order = Orders::model()->findByPk($order_id);
		if($order != NULL && $order->user_id == $uid) {
			if($order->payed_status != 1 && $order->payed_status != 2 && $order->status != 8) {
				$msg = $model->changeStatus($order, 9);
				echo $this->getOrdersInfo($uid, $order->id, 220);
			} else {
				echo CJSON::encode(array(
						'status' => "225" 
				));
				die();
			}
		} else {
			echo CJSON::encode(array(
					'status' => "300" 
			));
			die();
		}
	}
	
	public function makeOrder($uid, $data) {
		// print_r($data);
		$search_phrase = '';
		foreach($data as $k => $v) {
			if(! empty($search_phrase))
				$search_phrase .= ',';
			$search_phrase .= $v['article_order'];
		}
		$search_phrase = str_replace(array(
				"\s",
				' ' 
		), '', $search_phrase);
		$search_phrases = explode(',', $search_phrase);
		$search_phrases = array_flip($search_phrases);
		$products = array();
		$products_other = array();
		$full_products = array();
		$this->loadProductsList($products, $products_other, $search_phrases, $uid, $full_products);
		unset($products_other);
		// unset($products_other);
		$make_order = array();
		foreach($data as $v) {
			foreach($products as $k => $value) {
				if(trim($value['article_order']) == trim($v['article_order']) && 
				// trim($value['article']) == trim($v['article']) &&
				trim($value['brand']) == trim($v['brand']) && trim($value['delivery']) == trim($v['delivery']) && trim($value['store']) == trim($v['store'])) {
					$temp = $v;
					$temp['price'] = $value['price'];
					$temp['key'] = $k;
					$make_order[] = $temp;
					break;
				}
			}
		}
		$model = new OrdersApi();
		// print_r($full_products);
		// print_r($products);
		foreach($make_order as $value) {
			// print_r($full_products[$value['key']]);
			$value['price_echo'] = $value['price'];
			$value['supplier_inn'] = $full_products[$value['key']]['supplier_inn'];
			$value['supplier'] = $full_products[$value['key']]['supplier'];
			$value['weight'] = $full_products[$value['key']]['weight'];
			$value['supplier_price'] = $full_products[$value['key']]['supplier_price'];
			$value['price_group_1'] = $full_products[$value['key']]['price_group_1'];
			$value['price_group_2'] = $full_products[$value['key']]['price_group_2'];
			$value['price_group_3'] = $full_products[$value['key']]['price_group_3'];
			$value['price_group_4'] = $full_products[$value['key']]['price_group_4'];
			$temp = new ShopProducts();
			unset($value['key']);
			$temp->attributes = $value;
			$temp->save();
			$model->items_order[] = array(
					'product_id' => $temp->product_id 
			);
			// $item->attributes = $model->getAttributes(array('weight', 'supplier_price', 'price_group_1', 'price_group_2', 'price_group_3', 'price_group_4'));
		}
		$model->user_id = $uid;
		$model->save();
		
		unset($products);
		unset($full_products);
		unset($make_order);
		echo $this->getOrdersInfo($uid, $model->id, 200);
		// print_r($make_order);
		// print_r($products);
	}
	
	public function getOrdersInfo($user_id, $order_id = 0, $status = 400) {
		$models = NULL;
		if($order_id == 0)
			$models = Orders::model()->findAllByAttributes(array(
					'user_id' => $user_id 
			));
		else
			$models = Orders::model()->findAllByAttributes(array(
					'user_id' => $user_id,
					'order_id' => $order_id 
			));
		$orders = array();
		$orderStatus = new OrdersStatus();
		$itemStatus = new ItemsStatus();
		// $z=0;
		if($models != NULL)
			foreach($models as $model) {
				$items = array();
				$items_models = Items::model()->findAllByAttributes(array(
						'order_id' => $model->id 
				));
				// $y=0;
				if($items_models != NULL)
					foreach($items_models as $items_model) {
						$items[] = array(
								'price' => $items_model->price,
								'brand' => $items_model->brand,
								'quantum' => $items_model->quantum,
								'delivery' => $items_model->delivery,
								'article' => $items_model->article_order,
								'name' => $items_model->name,
								'store' => $items_model->store,
								'status' => $itemStatus->getName($items_model->status),
								'payed_status' => $itemStatus->getPayedName($items_model->payed_status),
								'create_date' => date(DATE_ATOM, $items_model->create_date) 
						);
						// $y++;
						// if($y>1)
						//
						// break;
					}
				$orders[] = array(
						'order_id' => $model->id,
						'delivery_cost' => $model->delivery_cost,
						'total_cost' => $model->total_cost,
						'status' => $orderStatus->getName($model->status),
						'payed_status' => $orderStatus->getPayedName($model->payed_status),
						'delivery_zipcode' => $model->zipcode,
						'delivery_country' => $model->country,
						'delivery_city' => $model->city,
						'delivery_street' => $model->street,
						'delivery_house' => $model->house,
						'create_date' => date(DATE_ATOM, $model->create_date),
						'items' => $items 
				);
				// $z++;
				// if($z>2)
				// break;
			}
		// print_r(array('status' => $status, 'orders_count' => count($orders), 'orders' => $orders));
		return CJSON::encode(array(
				'status' => $status,
				'orders_count' => count($orders),
				'orders' => $orders 
		));
	}
	
	public function checkOrder() {
	}
	
	public function getSkladList() {
		$db = Yii::app()->db;
		$time_start = array_key_exists('time_start', $_GET) ? $_GET['time_start'] : '';
		$className = Yii::app()->controller->module->modelClass;
		$model = new $className();
		$tableName = $model->tableName();
		$activeName = Yii::app()->controller->module->activeName;
		$sql = "SELECT `id` FROM `$tableName` WHERE `$activeName`='1'";
		$data = $db->createCommand($sql)->queryAll();
		$sklad_list = array(
				0 => 'local' 
		);
		foreach($data as $row) {
			$sklad_list[] = $row['id'];
		}
		$className = Yii::app()->controller->module->apiModelClass;
		$model = new $className();
		$tableName = $model->tableName();
		$activeName = Yii::app()->controller->module->activeName;
		$sql = "SELECT `id` FROM `$tableName` WHERE `$activeName`='1'";
		$data = $db->createCommand($sql)->queryAll();
		foreach($data as $row) {
			$sklad_list[] = 'api_' . $row['id'];
		}
		return $sklad_list;
	}
	
	public function getProductList($search_phrase, $uid) {
		$search_phrase = str_replace(array(
				"\s",
				' ' 
		), '', $search_phrase);
		$search_phrases = explode(',', $search_phrase);
		$search_phrases = array_flip($search_phrases);
		$products = array();
		$products_other = array();
		$this->loadProductsList($products, $products_other, $search_phrases, $uid);
		// print_r(array('status' => "10", 'products_count' => count($products), 'products' => $products, 'products_other_count' => count($products_other), 'products_other' => $products_other));
		return CJSON::encode(array(
				'status' => "10",
				'products_count' => count($products),
				'products' => $products,
				'products_other_count' => count($products_other),
				'products_other' => $products_other 
		));
	}
	
	public function loadProductsList(&$products, &$products_other, &$search_phrases, $uid, &$full_products_array = -1) {
		$user_model = UserProfile::model()->findByAttributes(array(
				'uid' => $uid 
		));
		$sklad_list = $this->getSkladList();
		foreach($sklad_list as $search_sklad) {
			foreach($search_phrases as $search_phrase => $temp_search_phrase) {
				$search_phrase = preg_replace("/[^a-zA-Z0-9]/", "", $search_phrase);
				$search_phrase = strtoupper($search_phrase);
				if(! empty($search_phrase)) {
					$model = $this->loadModel($search_sklad);
					$res = $model->getData($search_phrase, array(
							'price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(),
							'id' => $search_sklad 
					));
					
					foreach($res as $key => $value) {
						$insert_value = array(
								'price' => $value['all_prices'][$user_model->price_group - 1]['price'],
								'brand' => $value['brand'],
								'quantum' => $value['kolichestvo'],
								'delivery' => $value['dostavka'],
								'article' => $value['articul_order'],
								'article_order' => $value['articul'],
								'name' => $value['name'],
								'store' => $value['store'] 
						);
						if(isset($search_phrases[$value['articul']])) {
							if(is_array($full_products_array)) {
								$full_products_array[] = $value;
							}
							$products[] = $insert_value;
						} else {
							$products_other[] = $insert_value;
						}
					}
				}
			}
		}
	}
	
	public function loadModel($id) {
		if($id == 'local') {
			$temp = Yii::app()->controller->module->localPriceSearchClass;
			return new $temp();
		}
		if(str_replace('api_', '', $id) != $id) {
			$model = CActiveRecord::model(Yii::app()->controller->module->apiModelClass)->findByPk(str_replace('api_', '', $id));
			$temp = new ParserApiSearchModel();
		} else {
			$model = CActiveRecord::model(Yii::app()->controller->module->modelClass)->findByPk($id);
			$temp = new $model->{Yii::app()->controller->module->parserClassName}();
		}
		if($model == null)
			throw new CHttpException(404, 'The requested page does not exist.');
		$temp->model = $model;
		return $temp;
	}
}