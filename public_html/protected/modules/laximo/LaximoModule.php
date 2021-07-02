<?php
class LaximoModule extends CWebModule {
    private $_css;
    private $_js;
    
	public function init() {
		$assetsDir = dirname(__FILE__).'/assets';
		
		$this->_css = Yii::app()->assetManager->publish($assetsDir).'/css';
		Yii::app()->clientScript->registerCssFile($this->_css.'/laximo.css');
		
		$this->setImport(array(
			'laximo.models.IGuayaquilExtender',
			'laximo.models.'.Config::get('ui_localization').'.LanguageTemplate',
			
			'laximo.models.guayaquillib.render.vin.GuayaquilVinSearchForm',
			'laximo.models.guayaquillib.render.vin.VinSearchExtender',
				
			'laximo.models.guayaquillib.render.framesearch.GuayaquilFrameSearchForm',
			'laximo.models.guayaquillib.render.framesearch.FrameSearchExtender',
				
			'laximo.models.guayaquillib.data.GuayaquilRequestOEM',
			'laximo.models.CommonExtender',
			'laximo.models.CatalogExtender',

			'laximo.models.guayaquillib.render.GuayaquilTemplate',
			'laximo.models.guayaquillib.render.GuayaquilToolbar',
				
			'laximo.models.guayaquillib.render.catalog.WizardExtender',
			'laximo.models.guayaquillib.render.catalog.GuayaquilWizard2',

			'laximo.models.guayaquillib.render.wizard.GuayaquilWizard',
			'laximo.models.guayaquillib.render.wizard.Wizard2Extender',
				
			'laximo.models.guayaquillib.render.vehicles.GuayaquilVehiclesList',
			'laximo.models.guayaquillib.render.vehicles.VehiclesExtender',
			
			'laximo.models.guayaquillib.render.qgroups.GuayaquilQuickGroupsList',
			'laximo.models.guayaquillib.render.qgroups.QuickGroupsExtender',
				
			'laximo.models.guayaquillib.render.qdetails.GuayaquilDetailsList',
			'laximo.models.guayaquillib.render.qdetails.GuayaquilQuickDetailsList',
			'laximo.models.guayaquillib.render.qdetails.QuickDetailsExtender',
				
			'laximo.models.guayaquillib.render.unit.DetailExtender',
			'laximo.models.guayaquillib.render.unit.GuayaquilUnitImage',
			'laximo.models.guayaquillib.render.unit.GuayaquilUnit',
				
			'laximo.models.guayaquillib.render.vehicle.CategoryExtender',
			'laximo.models.guayaquillib.render.vehicle.GuayaquilCategoriesList',
			'laximo.models.guayaquillib.render.vehicle.GuayaquilUnitsList',
			'laximo.models.guayaquillib.render.vehicle.UnitExtender',
				
			'laximo.models.guayaquillib.render.operation.OperationSearchExtender',
			'laximo.models.guayaquillib.render.operation.GuayaquilOperationSearchForm',
		));
	}
}

class Config {
	private static $ui_localization_my = ''; // ru or en
	private static $catalog_data_my = ''; // en_GB or ru_RU

	public static $useLoginAuthorizationMethod = true;

	// login/key from laximo.ru
	private static $userLogin_my = '';
	private static $userKey_my = '';

	private static $redirectUrl_my = '';
	
	public static function get($name) {
		switch ($name) {
			case 'ui_localization':
				if (self::$ui_localization_my == '') {
					switch (self::get('catalog_data')) {
						case 'ru_RU':
							self::$ui_localization_my = 'ru';
						break;
						default:
							self::$ui_localization_my = 'en';
						break;
					}
				}
				
				return self::$ui_localization_my;
			break;
			case 'catalog_data':
				if (self::$catalog_data_my == '') {
					self::$catalog_data_my = 'ru_RU';
					
					if (Yii::app()->language == 'en') self::$catalog_data_my = 'en_GB';
				}
				
				return self::$catalog_data_my;
			break;
			case 'userLogin':
				if (self::$userLogin_my == '') {
					self::$userLogin_my = Yii::app()->config->get('Laximo.UserLogin');
				}
				
				return self::$userLogin_my;
			break;
			case 'userKey':
				if (self::$userKey_my == '') {
					self::$userKey_my = Yii::app()->config->get('Laximo.UserKey');
				}
				
				return self::$userKey_my;
			break;
			case 'redirectUrl':
				if (self::$redirectUrl_my == '') {
					switch (Yii::app()->config->get('Site.SearchType')) {
						case 1:
							self::$redirectUrl_my = Yii::app()->getRequest()->getHostInfo().'/art/$oem$';
						break;
						default:
							self::$redirectUrl_my = Yii::app()->getRequest()->getHostInfo().'/search?search_phrase=$oem$';
						break;
					}
				}
				
				return self::$redirectUrl_my;
			break;
		}
	}
}