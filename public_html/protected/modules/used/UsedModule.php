<?php
/**
 * Модуль Б/У каталог
 */
class UsedModule extends CWebModule
{
    const TRANSLATE_PATH = 'usedModule.app';

	public $enabledModule = true;

    public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'used.models.*',
			'used.components.*',
		));
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

	public function getCartFormData($model) 
	{
		$allData = $model->getPriceDataAll();
		return array(
			'article_order' => $model->id,
			'supplier_inn' => ($allData['price'])?$allData['price']->supplier_inn:0,
			'supplier' => ($allData['price'])?$allData['price']->supplier:0,
			'store' => 'Каталог б/у',
			'name' => $model->title,
			'brand' => $model->brandItem->name,
			'article' => $model->original_num,
			'delivery' => $model->delivery_time,
			'quantum_all' => '',
			'price_echo' => UsedItems::getPriceMarkup($model->vendor_code, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => 'local_my')),
			'price' => UsedItems::getPriceToCart($model->vendor_code, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => 'local_my')),
			'price_data_id' => $model->getPriceDataId(),
			'store_count_state' => 0,
			'weight' => '',
			'go_link' => Yii::app()->createAbsoluteUrl('/used/cars/item', array('slug'=>$model->slug)),
		);
	}
}
