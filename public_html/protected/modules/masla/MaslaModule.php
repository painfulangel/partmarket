<?php
class MaslaModule extends CWebModule {
    public $_css;
    public $_js;
    public $enabledModule = true;
    
	public function init() {
		$this->setImport(array(
			'masla.models.*',
			'masla.components.*',
		));
		
		$assetsDir = dirname(__FILE__).'/assets';
		
		$this->_css = Yii::app()->assetManager->publish($assetsDir).'/css';
		Yii::app()->clientScript->registerCssFile($this->_css.'/masla.css');
		
		$this->_js = Yii::app()->assetManager->publish($assetsDir).'/js';
		Yii::app()->clientScript->registerScriptFile($this->_js.'/masla.js');
	}
}