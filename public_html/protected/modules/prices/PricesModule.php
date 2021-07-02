<?php

class PricesModule extends CWebModule {

    /**
     *
     * @var String function name for echo Radio buttons of extra characters
     */
    public $radionButtonFunction = 'radioButtonListInlineRow';

    /**
     *
     * @var Array extra encoding characters of input files
     */
    public $extraCharacters = array('cp1251' => 'cp1251', 'UTF-8' => 'utf');

    /**
     *
     * @var Integer Max size of uploaded files 
     */
    public $maxFileSize = 26214400; //for max size 25mb

    /**
     *
     * @var String path fo upload files start with site path
     */
    public $pathExportFiles = '/upload_files/croses/';

    /**
     *
     * @var String path for upload files start with root file system 
     */
    public $pathFiles = '';

    public function getCartFormData($value) {
        $price = Yii::app()->getModule('prices')->getPriceFunction(array('price' => $value['model']->price, 'brand' => $value['model']->brand, 'price_currency' => $value['model_price']->currency, 'price_price_group' => $value['model_price']->{'price_group_' . Yii::app()->getModule('pricegroups')->getUserGroup()}));
        $delivery = is_numeric($value['model']->delivery) ? $value['model']->delivery : 0 + is_numeric($value['model_price']->delivery) ? $value['model_price']->delivery : 0;
        if ($delivery == 0)
            $delivery = Yii::app()->getModule('detailSearch')->zerosDeliveryValue;
        return array(
            'article_order' => strtoupper($value['model']->original_article),
            'supplier_inn' => $value['model_price']->supplier_inn,
            'supplier' => $value['model_price']->supplier,
            'store' => $value['model_store']->name,
            'name' => $value['model']->name,
            'brand' => $value['model']->brand,
            'article' => strtoupper($value['model']->article),
            'delivery' => $delivery,
            'quantum_all' => $value['model']->quantum,
            'price_echo' => Yii::app()->getModule('prices')->getPriceFormatFunction($price),
            'price' => $price,
            'price_data_id' => $value['model']->id,
            'store_count_state' => $value['model_store']->count_state,
            'weight' => $value['model']->weight,
        );
    }

    /**
     *
     * @var string code to convert price value 
     */
    public function getPriceFunction($value) {
    	$price = Yii::app()->getModule('pricegroups')->getPrice(Yii::app()->getModule('currencies')->getPrice($value['price'], $value['price_currency']), $value['price_price_group'], $value['brand']);
    	
    	if (!defined('PLAN_TASK')) {
	    	//User individual discount
	    	$profile = Yii::app()->getModule('userControl')->getCurrentUserModel();
	    	if (is_object($profile) && $profile->discount) {
	    		$price = round((100 - $profile->discount) * $price / 100, 2);
	    	}
	    	//User individual discount
    	}
    	
        return $price;
    }

    /**
     *
     * @var string code to echo price value 
     */
    public function getPriceFormatFunction($price) {
        return Yii::app()->getModule('currencies')->getFormatPrice($price);
    }

    public function init() {


        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'prices.models.*',
            'prices.components.*',
        ));

        $this->pathFiles = realpath(Yii::app()->basePath . '/..' . $this->pathExportFiles) . '/';
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }

    public function getSitemap() {
        $array = array(Yii::app()->createAbsoluteUrl('/prices/default/index') => Yii::t('prices', 'Prices'));
        $data = Prices::model()->findAll('search_state=1');
        foreach ($data as $value) {
            $array[Yii::app()->createAbsoluteUrl('/prices/default/view', array('id' => $value->id))] = $value->name;
        }
        return $array;
    }

}
