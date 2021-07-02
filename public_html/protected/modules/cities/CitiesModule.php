<?php
class CitiesModule extends CWebModule {
    public $_css;
    public $_js;

	public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'cities.models.*',
        ));
		
		$assetsDir = dirname(__FILE__).'/assets';

		//echo $assetsDir; exit;
		
		$this->_css = Yii::app()->assetManager->publish($assetsDir).'/css';
		Yii::app()->clientScript->registerCssFile($this->_css.'/city.css');
		
		$this->_js = Yii::app()->assetManager->publish($assetsDir).'/js';
		Yii::app()->clientScript->registerScriptFile($this->_js.'/city.js');
		Yii::app()->clientScript->registerScriptFile($this->_js.'/jquery.cookie.js');
    }
}
?>