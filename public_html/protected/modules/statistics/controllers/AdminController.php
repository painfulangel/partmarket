<?php
class AdminController extends Controller {
	public $layout = '//layouts/admin_column2';
	
	public function beforeAction($action) {
		$this->admin_header = array (
			array (
				'name' => Yii::t('admin_layout', 'Price politics'),
				'url' => array ('/pricegroups/adminGroups/admin'),
				'active' => false
			),
			array (
				'name' => Yii::t('admin_layout', 'Payment system'),
				'url' => array ('/webPayments/adminWebPaymentsSystem/admin'),
				'active' => false
			),
			array (
				'name' => Yii::t('admin_layout', 'Currency'),
				'url' => array ('/currencies/admin/admin'),
				'active' => false
			),
			array (
				'name' => Yii::t('admin_layout', 'Statistics'),
				'url' => array ('/statistics/admin/admin'),
				'active' => true
			),
		);

		if (!defined('TURNON_CITIES') || (TURNON_CITIES === true)) {
			$this->admin_header[] = array (
				'name' => Yii::t('cities', 'Cities'),
				'url' => array ('/cities/admin/admin'),
				'active' => false
			);
		}

		return parent::beforeAction($action);
	}
	
	/**
	 *
	 * @return array action filters
	 */
	public function filters() {
		return array (
				'accessControl'  // perform access control for CRUD operations
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 *
	 * @return array access control rules
	 */
	public function accessRules() {
		return array (
			array (
				'allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array (
					'admin', 'checkManagerForm', 'exportAllManagers', 'exportSalesAllManagers',
				),
				'roles' => array (
					'mainManager',
					'admin'
				)
			),
			array (
				'deny', // deny all users
				'users' => array (
					'*'
				)
			)
		);
	}
	
	
	public function actionAdmin() {
		$this->render('admin', array (
			'managers' => UserProfile::getManagers(),
			'stores' => Stores::model()->findAll(),
		));
	}
	
	public function actionCheckManagerForm() {
		$error = array();
		
		$request = Yii::app()->request;
		
		$manager = array_diff(array_map('intval', $request->getPost('manager', array())), array('', 0));
		
		if (!is_array($manager) || (count($manager) == 0)) {
			$error[] = Yii::t('statistics', 'Elect at least one manager.');
		}
		
		if ($dateBegin = $request->getPost('dateBegin')) {
			$parts = array_map('intval', explode('.', $dateBegin));
			if (count($parts) < 3) {
				$error[] = Yii::t('statistics', 'Incorrect format of start date of the period.');
			}
		}
		
		if ($dateEnd = $request->getPost('dateEnd')) {
			$parts =  array_map('intval', explode('.', $dateEnd));
			if (count($parts) < 3) {
				$error[] = Yii::t('statistics', 'Incorrect format of end date of the period.');
			}
		}
		
		$data = array('error' => implode("\n", $error));
		
		echo CJSON::encode($data);
	}
	
	public function actionExportAllManagers() {
		header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
		header('Last-Modified: '.gmdate("D,d M YH:i:s").' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=export_all_managers_cp1251.csv');
		
		$request = Yii::app()->request;
		
		$manager = array_diff(array_map('intval', $request->getPost('manager', array())), array('', 0));
		
		if ($dateBegin = $request->getPost('dateBegin')) {
			$parts = array_map('intval', explode('.', $dateBegin));
			
			$dateBegin = mktime(0, 0, 0, $parts[1], $parts[0], $parts[2]);
		}
		
		if ($dateEnd = $request->getPost('dateEnd')) {
			$parts =  array_map('intval', explode('.', $dateEnd));
			
			$dateEnd = mktime(23, 59, 59, $parts[1], $parts[0], $parts[2]);
		}
		
		$store = array_diff($request->getPost('store', array()), array(''));
		
		$article = trim($request->getPost('article', ''));
		$brand = trim($request->getPost('brand', ''));
		
		$condition = 'manager_id IN('.implode(', ', $manager).')';
		
		$addCondition = array();
		
		if ($dateBegin && $dateEnd) {
			$addCondition[] = 'create_date BETWEEN '.$dateBegin.' AND '.$dateEnd;
		} else if ($dateBegin) {
			$addCondition[] = 'create_date >= '.$dateBegin;
		} else if ($dateEnd) {
			$addCondition[] = 'create_date <= '.$dateEnd;
		}
		
		if (count($addCondition)) {
			$condition .= ' AND '.implode(' AND ', $addCondition);
		}
		
		$data = array();
		
		$orders = Orders::model()->findAll($condition);
		$count = count($orders);
		for ($i = 0; $i < $count; $i ++) {
			$o = $orders[$i];
			
			if (!array_key_exists($o->manager_id, $data)) {
				$manager = $o->manager;
				
				$data[$o->manager_id] = array('name'  => $manager->first_name.' '.$manager->father_name.' '.$manager->second_name,
											  'email' => $manager->email,
											  'ready' => 0,
											  'sum_ready' => 0,
											  'all' => 0,
											  'sum_all' => 0,
											  'sum' => 0,
				);
			}
			
			if ($o->status == 8) {
				$data[$o->manager_id]['ready'] ++;
				$data[$o->manager_id]['sum_ready'] += $o->total_cost;
			} else {
				$data[$o->manager_id]['all'] ++;
				$data[$o->manager_id]['sum_all'] += $o->total_cost;
			}
		}
		
		//Имя менеджера
		//его почта
		//выполненные заказы ( кол-во заказов со статусом "Выполнено")
		//Сумма выполненных заказов
		//Всего заказов (количество заказов без учёта статуса выполнено)
		//Сумма заказов
		//Сумма пополнения поступления денег от клиентов
		
		$export = Yii::t('statistics', 'Manager name').';'.
				  Yii::t('statistics', 'Manager E-mail').';'.
				  Yii::t('statistics', 'Number of orders with status "Done"').';'.
				  Yii::t('statistics', 'Sum of done orders').';'.
				  Yii::t('statistics', 'Number of orders without status "Done"').';'.
				  Yii::t('statistics', 'Orders sum').';'.
				  Yii::t('statistics', 'Sum of of money from clients')."\n";
		
		foreach ($data as $row) {
			$export .= $row['name'].';'.$row['email'].';'.$row['ready'].';'.$row['sum_ready'].';'.$row['all'].';'.$row['sum_all'].';'.$row['sum'];
		}
		
		echo iconv('UTF-8', 'cp1251', $export);
	}
	
	public function actionExportSalesAllManagers() {
		header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
		header('Last-Modified: '.gmdate("D,d M YH:i:s").' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=export_sales_all_managers_cp1251.csv');
		
		$request = Yii::app()->request;
		
		$manager = array_diff(array_map('intval', $request->getPost('manager', array())), array('', 0));
		
		if ($dateBegin = $request->getPost('dateBegin')) {
			$parts = array_map('intval', explode('.', $dateBegin));
			
			$dateBegin = mktime(0, 0, 0, $parts[1], $parts[0], $parts[2]);
		}
		
		if ($dateEnd = $request->getPost('dateEnd')) {
			$parts =  array_map('intval', explode('.', $dateEnd));
			
			$dateEnd = mktime(23, 59, 59, $parts[1], $parts[0], $parts[2]);
		}
		
		$store = array_diff($request->getPost('store', array()), array(''));
		
		$article = trim($request->getPost('article', ''));
		$brand = trim($request->getPost('brand', ''));
		
		$condition = 'manager_id IN('.implode(', ', $manager).')';
		
		$addCondition = array();
		
		if ($dateBegin && $dateEnd) {
			$addCondition[] = 'create_date BETWEEN '.$dateBegin.' AND '.$dateEnd;
		} else if ($dateBegin) {
			$addCondition[] = 'create_date >= '.$dateBegin;
		} else if ($dateEnd) {
			$addCondition[] = 'create_date <= '.$dateEnd;
		}
		
		if (count($addCondition)) {
			$condition .= ' AND '.implode(' AND ', $addCondition);
		}
		
		$data = array();
		
		$orders = Orders::model()->findAll(array('condition' => $condition, 'order' => 'create_date DESC'));
		$count = count($orders);
		for ($i = 0; $i < $count; $i ++) {
			$o = $orders[$i];
			
			$items = $o->items;
			foreach ($items as $item) {
				$index = $o->manager_id.' - '.$item->article.' - '.$item->brand;
				
				//if (!array_key_exists($index, $data)) {
					$manager = $o->manager;
					
					if (($article != '') && (mb_strtolower($item->article) != mb_strtolower($article))) continue;
					if (($brand != '') && (stripos($item->brand, $brand) === false)) continue;
					
					$data[$index] = array('name'  		   => $manager->first_name.' '.$manager->father_name.' '.$manager->second_name,
										  'email' 		   => $manager->email,
										  'article' 	   => $item->article,
										  'brand' 		   => $item->brand,
										  'store' 		   => $item->store,
										  'price_purchase' => $item->price_purchase,
										  'price' 		   => $item->price,
										  'quantum' 	   => $item->quantum,
										  'sum' 		   => $item->quantum * $item->price,
					);
				//}
			}
		}
		
		//имя менеджера
		//почта менеджера
		//Артикул
		//Бренд
		//Название склада
		
		//цена детали
		//продажная цена
		//кол-во
		//сумма продажная
		
		$export = Yii::t('statistics', 'Manager name').';'.
				  Yii::t('statistics', 'Manager E-mail').';'.
				  Yii::t('statistics', 'Article').';'.
				  Yii::t('statistics', 'Brand').';'.
				  Yii::t('statistics', 'Store name').';'.
				  Yii::t('statistics', 'Detail price').';'.
				  Yii::t('statistics', 'Selling price').';'.
				  Yii::t('statistics', 'Number').';'.
				  Yii::t('statistics', 'Selling sum')."\n";
		
		foreach ($data as $row) {
			$export .= $row['name'].';'.
					   $row['email'].';'.
					   $row['article'].';'.
					   $row['brand'].';'.
					   $row['store'].';'.
					   $row['price_purchase'].';'.
					   $row['price'].';'.
					   $row['quantum'].';'.
					   $row['sum'];
		}
		
		echo iconv('UTF-8', 'cp1251', $export);
	}
}
?>