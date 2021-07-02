<?php
class UniversalModule extends CWebModule {
    public $_css;
    public $_js;
    public $enabledModule = true;
    
    /**
     *
     * @var Integer Max size of uploaded files
     */
    public $maxFileSize = 26214400; //for max size 25mb
    
    /**
     *
     * @var String path for upload files start with root file system
     */
    public $pathFiles = '';
    
    /**
     *
     * @var String path fo upload files start with site path
     */
    public $pathExportFiles = '/upload_files/';
    
	public function init() {
		$this->pathFiles = realpath(Yii::app()->basePath.'/..'.$this->pathExportFiles).'/';
		
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		// import the module-level models and components
		$this->setImport(array(
			'universal.models.*',
			'universal.components.*',
		));
		
		$assetsDir = dirname(__FILE__).'/assets';
		
		$this->_css = Yii::app()->assetManager->publish($assetsDir).'/css';
		Yii::app()->clientScript->registerCssFile($this->_css.'/universal.css');
		
		//$this->_js = Yii::app()->assetManager->publish($assetsDir).'/js';
		//Yii::app()->clientScript->registerScriptFile($this->_js.'/universal.js');
	}
	
	public function getMenuPathsMap() {
		$pathsMap = array();
		
		$items = UniversalRazdel::model()->findAll(array('condition' => 'active_state = 1'));
		
		$count = count($items);
		for ($i = 0; $i < $count; $i ++) {
			$pathsMap[] = array('path' => $items[$i]->getUrl(), 'title' => $items[$i]->name, 'items' => array());
		}
		
		return $pathsMap;
	}
}