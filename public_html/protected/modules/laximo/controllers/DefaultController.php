<?php
class DefaultController extends Controller {
	public $layout = '//layouts/column2';
	
	public function actionIndex() {
		// Create request object
		$request = $this->getRequest('', '', Config::get('catalog_data'));
		
		// Append commands to request
		$request->appendListCatalogs();
		
		// Execute request
		$data = $request->query();
		
		$error = '';
		$catalogs = array();
		
		// Check errors
		if ($request->error != '') {
			$error = $request->error;
		} else {
			$catalogs = $data[0];
		}
		
		$this->render('index', array('error' => $error, 'catalogs' => $catalogs, 'columns' => array('name', 'version')));
	}
	
	public function actionCatalog() {
		// Create request object
		$request = $this->getRequest($_GET['c'], $_GET['ssd'], Config::get('catalog_data'));
		
		// Append commands to request
		$request->appendGetCatalogInfo();
		if (@$_GET['spi2'] == 't')
			$request->appendGetWizard2();
		
		// Execute request
		$data = $request->query();
		
		// Check errors
		$cataloginfo = array();
		$error = '';
		
		if ($request->error != '') {
			$error = $request->error;
		} else {
			$cataloginfo = $data[0]->row;
		}
		
		$this->render('catalog', array('error' => $error, 'data' => $data, 'cataloginfo' => $cataloginfo, 'c' => $_GET['c']));
	}
	
	public function actionWizard2() {
		// Create request object
		$request = $this->getRequest($_GET['c'], $_GET['ssd'], Config::get('catalog_data'));
		
		// Append commands to request
		$request->appendGetCatalogInfo();
		$request->appendGetWizard2($_GET['ssd']);
		
		// Execute request
		$data = $request->query();
		
		// Check errors
		$error = '';
		
		if ($request->error != '') {
			$error = $request->error;
		} else {
			$wizard = $data[1];
			$cataloginfo = $data[0]->row;
		}
		
		$this->render('wizard2', array('error' => $error, 'wizard' => $wizard, 'cataloginfo' => $cataloginfo, 'c' => $_GET['c']));
	}
	
	public function actionVehicles() {
		// Create request object
		$catalogCode = array_key_exists('c', $_GET) ? $_GET['c'] : false;
		$request = $this->getRequest($catalogCode, array_key_exists('ssd', $_GET) ? $_GET['ssd'] : '', Config::get('catalog_data'));
		
		// Append commands to request
		$findType = $_GET['ft'];
		if ($findType == 'findByVIN')
			$request->appendFindVehicleByVIN($_GET['vin']);
		else if ($findType == 'findByFrame')
			$request->appendFindVehicleByFrame($_GET['frame'], $_GET['frameNo']);
		else if ($findType == 'execCustomOperation')
			$request->appendExecCustomOperation($_GET['operation'], $_GET['data']);
		else if ($findType == 'findByWizard2')
			$request->appendFindVehicleByWizard2($_GET['ssd']);
		
		if ($catalogCode) {
			$request->appendGetCatalogInfo ();
		}
		// Execute request
		$data = $request->query();
		
		$error = '';
		$vehicles = array();
		$cataloginfo = false;
		
		// Check errors
		if ($request->error != '') {
			$error = $request->error;
		} else {
			$vehicles = $data[0];
			$cataloginfo = $catalogCode ? $data[1]->row : false;
		}
		
		$this->render('vehicles', array('error' => $error, 'catalogCode' => $catalogCode, 'findType' => $findType, 'vehicles' => $vehicles, 'cataloginfo' => $cataloginfo));
	}
	
	public function actionVehicle() {
		// Create request object
		$request = $this->getRequest($_GET['c'], $_GET['ssd'], Config::get('catalog_data'));
		
		// Append commands to request
		$request->appendGetCatalogInfo();
		$request->appendGetVehicleInfo($_GET['vid']);
		$request->appendListCategories($_GET['vid'], isset($_GET['cid']) ? $_GET['cid'] : -1);
		$request->appendListUnits($_GET['vid'], isset($_GET['cid']) ? $_GET['cid'] : -1);
		
		// Execute request
		$data = $request->query();
		
		// Check errors
		$error = '';
		
		if ($request->error != '') {
			$error = $request->error;
		} else {
			$catalogInfo = $data[0]->row;
			$vehicle = $data[1]->row;
			$categories = $data[2];
			$units = $data[3];
		
			if (array_key_exists('checkQG', $_GET) && CommonExtender::isFeatureSupported($catalogInfo, 'quickgroups')) {
				$link = 'qgroups.php?c='.$_GET['c'].'&vid='.$_GET['vid'].'&ssd='.$_GET['ssd'].'&path_data='.$_GET['path_data'];
				header("Location: ". $link);
				exit();
			}
		}
		
		$this->render('vehicle', array('error' => $error, 'categories' => $categories, 'units' => $units, 'vehicle' => $vehicle, 'catalogInfo' => $catalogInfo));
	}
	
	public function actionQgroups() {
		// Create request object
		$request = $this->getRequest($_GET['c'], $_GET['ssd'], Config::get('catalog_data'));
		
		// Append commands to request
		$request->appendGetVehicleInfo($_GET['vid']);
		$request->appendListQuickGroup($_GET['vid']);
		
		// Execute request
		$data = $request->query();
		
		// Check errors
		$error = '';
		$vehicle = null;
		$groups = array();
		$h1 = '';
		
		if ($request->error != '') {
			$error = $request->error;
		} else {
			$vehicle = $data[0]->row;
			$groups= $data[1];
			
			$h1 = CommonExtender::FormatLocalizedString('CarName', $vehicle['name']);
		}
		
		$params = array();
		if (array_key_exists('c', $_GET)) 		  $params['c'] = $_GET['c'];
		if (array_key_exists('vid', $_GET)) 	  $params['vid'] = $_GET['vid'];
		if (array_key_exists('ssd', $_GET)) 	  $params['ssd'] = $_GET['ssd'];
		if (array_key_exists('path_data', $_GET)) $params['path_data'] = $_GET['path_data'];
		
		$pictures = $groups->row->row;
		
		$quickgroupid = 0;
		
		if (array_key_exists('node', $_GET)) {
			$quickgroupid = $_GET['node'];
			
			$node = $this->getNode($pictures, $quickgroupid);
			
			$pictures = $node->children();
			
			$h1 .= ' '.$node['name'];
		}
		
		$this->render('qgroups', array('h1'			  => $h1,
									   'error'        => $error, 
									   'vehicle'      => $vehicle, 
										
									   'groups'       => $groups,
									   'gLink'        => '/'.Yii::app()->getRequest()->getPathInfo().'?'.http_build_query($params), 
									   'quickgroupid' => $quickgroupid,
										
									   'pictures'     => $pictures,
		));
	}
	
	private function getNode($nodes, $quickgroupid) {
		$result = false;
		
		foreach ($nodes as $subgroup) {
			//echo $subgroup['quickgroupid'].' - '.$quickgroupid.'<br>';
			if ($subgroup['quickgroupid'] == $quickgroupid) {
				//echo '<pre>'; print_r($subgroup->children()); echo '</pre>'; exit;
				return $subgroup;
			} else {
				$nodes = $subgroup->children();
				
				$result = $this->getNode($nodes, $quickgroupid);
				if ($result) return $result;
			}
		}
		
		return $result;
	}
	
	public function actionQdetails() {
		//!!! Left tree
		$request = $this->getRequest($this->get('c'), $this->get('ssd'), Config::get('catalog_data'));
		
		// Append commands to request
		$request->appendGetVehicleInfo($this->get('vid'));
		$request->appendListQuickGroup($this->get('vid'));
		
		// Execute request
		$data = $request->query();
		
		// Check errors
		$error = '';
		
		$nodeName = '';
		
		$groups = array();
		$params = array();
		$quickgroupid = 0;
		
		if ($request->error != '') {
			$error = $request->error;
		} else {
			$groups= $data[1];
		
			if (array_key_exists('c', $_GET)) 		  $params['c'] = $_GET['c'];
			if (array_key_exists('vid', $_GET)) 	  $params['vid'] = $_GET['vid'];
			if (array_key_exists('ssd', $_GET)) 	  $params['ssd'] = $_GET['ssd'];
			if (array_key_exists('path_data', $_GET)) $params['path_data'] = $_GET['path_data'];
			
			if (array_key_exists('gid', $_GET)) {
				$pictures = $groups->row->row;
			
				$quickgroupid = $this->get('gid');
				
				$node = $this->getNode($pictures, $quickgroupid);
				
				$nodeName = $node['name'];
			}
		}
		//!!! Left tree
		
		// Create request object
		$request = $this->getRequest($this->get('c'), $this->get('ssd'), Config::get('catalog_data'));
		
		// Append commands to request
		$request->appendGetVehicleInfo($this->get('vid'));
		$request->appendListCategories($this->get('vid'), isset($_GET['cid']) ? $_GET['cid'] : -1);
		$request->appendListQuickDetail($this->get('vid'), $this->get('gid'), 1);
		
		// Execute request
		$data = $request->query();
		
		// Check errors
		$error = '';
		$vehicle = null;
		$categories = array();
		$details = array();
		
		if ($request->error != '') {
			$error = $request->error;
		} else {
			$vehicle = $data[0]->row;
			$categories = $data[1];
			$details= $data[2];
		}
		
		$this->render('qdetails', array('error' 	   => $error, 

										'groups'       => $groups,
										'gLink'        => Yii::app()->createUrl('/laximo/default/index').'qgroups.php?'.http_build_query($params),
										'quickgroupid' => $quickgroupid,
										'nodeName'	   => $nodeName,
										
										'vehicle' 	   => $vehicle, 
										'categories'   => $categories, 
										'details' 	   => $details));
	}
	
	private function get($param) {
		if (array_key_exists($param, $_GET)) return $_GET[$param];
		return '';
	}
	
	public function actionUnit() {
		// Create request object
		$request = $this->getRequest($_GET['c'], $_GET['ssd'], Config::get('catalog_data'));
		
		// Append commands to request
		$request->appendGetUnitInfo($_GET['uid']);
		$request->appendListDetailByUnit($_GET['uid']);
		$request->appendListImageMapByUnit($_GET['uid']);
		
		// Execute request
		$data = $request->query();
		
		// Check errors
		$error = '';
		$unit = null;
		$imagemap = '';
		$details = array();
		
		if ($request->error != '') {
			$error = $request->error;
		} else {
			$unit = $data[0]->row;
			$imagemap = $data[2];
			$details = $data[1];
		}
		
		$this->render('unit', array('error' => $error, 'unit' => $unit, 'imagemap' => $imagemap, 'details' => $details));
	}
	
	private function getRequest($catalog = '', $ssd = '', $locale = 'ru_RU', IGuayquilCache $cache = null) {
		$request = new GuayaquilRequestOEM($catalog, $ssd, $locale, $cache);
		if (Config::$useLoginAuthorizationMethod) {
			$request->setUserAuthorizationMethod(Config::get('userLogin'), Config::get('userKey'));
		}
		
		return $request;
	}
}