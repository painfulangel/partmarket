<?php
class CurrenciesModule extends CWebModule {
    public $cache = 0;

    /**
     * @var string current currency value
     */
    protected $currentCurrency = 1;

    /**
     * @var string current currency Id
     */
    protected $currentCurrencyId = 0;
    protected $currencies = array();

    /**
     * @var string current currency Marker
     */
    public $currentCurrencyMarker;
    public $defaultCurrencyMarker = 'руб.';

    public function loadData() {
        $db = Yii::app()->db;
        $data = $db->createCommand('SELECT * FROM `' . Currencies::model()->tableName() . '` WHERE  1')->queryAll();
        $this->currencies = array();
        foreach ($data as $value) {
            $this->currencies[$value['id']] = $value['exchange'];
        }
    }

    public function getFormatPrice($price) {
        //return number_format($this->currentCurrency * $price, 2, ".", " ").' '.$this->currentCurrencyMarker;
    	return number_format($price / $this->currentCurrency, 2, ".", "").$this->currentCurrencyMarker;
    }

    public function getPrice($price, $currency) {
        if (count($this->currencies) == 0) {
            $this->loadData();
        }
        if (!isset($this->currencies[$currency]))
            return $price;
        $price*=$this->currencies[$currency];
        return $price;
    }

    public function getMarket() {
        return $this->currentCurrencyMarker;
    }

    public function getDefaultPrice($price) {
        return$price . ' ' . $this->defaultCurrencyMarker;
    }
    
    public function getUserCurrency() {
    	$currency = null;
    	 
    	if (!defined('PLAN_TASK') && !Yii::app()->user->isGuest) {
    		//Get client basic currency
    		$profile = UserProfile::model()->findByAttributes(array('uid' => Yii::app()->user->id));
    	
    		if (is_object($profile)) {
    			$currency = Currencies::model()->findByPk($profile->currency_type);
    		}
    	}
    	 
    	if (!is_object($currency)) {
    		$currency = Currencies::model()->findByAttributes(array('basic' => 1));
    	}
    	
    	return $currency;
    }

    public function init() {
    	$currency = null;
    	if (!defined('PLAN_TASK')) {
    		$currency = $this->getUserCurrency();
    	}
    	
    	if (is_object($currency)) {
    		$this->currentCurrencyId = $currency->primaryKey;
    		
    		$this->currentCurrency = $currency->exchange * (1 + intval($currency->percent)/100);
    		
    		$this->defaultCurrencyMarker = $currency->marker;
    	} else {
        	$this->defaultCurrencyMarker = Yii::t('currencies', 'rub.');
    	}
    	
        $this->currentCurrencyMarker = $this->defaultCurrencyMarker;

        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'currencies.models.*',
            'currencies.components.*',
        ));
		
        /*if (isset(Yii::app()->request->cookies['cookie_name']->value)) {
            $temp_currency = Yii::app()->request->cookies['cookie_name']->value;
            if ($temp_currency != $this->currentCurrencyId) {
                $this->currentCurrencyId = $temp_currency;
                $db = $this->getDbConnection();
                $temp_currency = $db->createCommand('SELECT `marker` FROM `currencies` WHERE id=\'' . $temp_currency . '\' LIMIT 1')->queryRow();
                $this->currentCurrency = $temp_currency['exchange'];
                $this->currentCurrencyMarker = $temp_currency['marker'];
            }
        }*/
    }

    protected function getDbConnection() {
        if ($this->cache)
            $db = Yii::app()->db->cache($this->cache, $this->dependency);
        else
            $db = Yii::app()->db;

        return $db;
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