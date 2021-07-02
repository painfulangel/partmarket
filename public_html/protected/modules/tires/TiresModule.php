<?php
class TiresModule extends CWebModule {
    public $_css;
    public $_js;
    public $enabledModule = true;
    
	public function init() {
		$this->setImport(array(
			'tires.models.*',
			'tires.components.*',
		));
		
		$assetsDir = dirname(__FILE__).'/assets';
		
		$this->_css = Yii::app()->assetManager->publish($assetsDir).'/css';
		Yii::app()->clientScript->registerCssFile($this->_css.'/tires.css');
		
		$this->_js = Yii::app()->assetManager->publish($assetsDir).'/js';
		Yii::app()->clientScript->registerScriptFile($this->_js.'/tires.js');
	}
}