<?php
class KatalogController extends Controller {
	public $layout = '//layouts/column2';
	
	public function actionIndex() {
		$brands = KatalogVavtoBrands::model()->findAll(array('condition' => 'active_state = 1', 'order' => '`order` ASC'));
		
		$items = array();
		$data = KatalogVavtoItems::model()->findAll(array('condition' => 'in_stock = 1', 'order' => 'title ASC'));
		
		$count = count($data);
		for ($i = 0; $i < $count; $i ++) {
			$cathegory_id = $data[$i]->cathegory_id;
			
			if (!array_key_exists($cathegory_id, $items))
				$items[$cathegory_id] = array('cath' => $data[$i]->cath, 'items' => array());
			
			$items[$cathegory_id]['items'][] = $data[$i];
		}
		
		$this->render('index', array('brands' => $brands, 
									 'seo' 	  => KatalogSettings::model()->find(), 
									 'items'  => $items,
		));
	}
}