<?php
class StatisticsModule extends CWebModule {
	public $_js;
	
	public function init() {
		parent::init();
		
		$assetsDir = dirname(__FILE__).'/assets';
		
		$this->_js = Yii::app()->assetManager->publish($assetsDir).'/js';
		Yii::app()->clientScript->registerScriptFile($this->_js.'/jquery-ui-i18n.min.js');
		Yii::app()->clientScript->registerScriptFile($this->_js.'/statistics.js');
	}
}
?>