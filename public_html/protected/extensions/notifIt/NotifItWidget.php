<?php
/**
 * Описание файла
 * 
 *
 * @author Moskvin Vitaliy <moskvinvitaliy@gmail.com>
 * @link http://moskvin-vitaliy.net/
 * @copyright Copyright &copy; 2013 Moskvin Vitaliy Software
 * @license GPL & MIT
 */
class NotifItWidget extends CWidget
{
	public static $_assest = '';
	
	public function init()
	{
		//Регистрируем пакет js для модального окна
        //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/notifIt/js/notifIt.js', CClientScript::POS_END);
        //Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/js/notifIt/css/notifIt.css');
		//Yii::app()->clientScript->registerPackage('popup');
		//Yii::app()->clientScript->registerPackage('popupCss');
		parent::init();
	}
	public function run()
	{
		$this->registerAssets();
		$this->renderContent();
		parent::run();
	}
	
	public function renderContent()
	{
		$messages = Yii::app()->user->getFlashes();
		$this->render('notifItMessage',array('messages'=>$messages));
	}

	public function registerAssets() 
	{
		if (self::$_assest == '') 
		{
			$assetsDir = dirname(__FILE__) . "/assets";
			self::$_assest = Yii::app()->assetManager->publish($assetsDir);
		}
		
		Yii::app()->clientScript->registerScriptFile(self::$_assest . "/notifIt/js/notifIt.js");
		Yii::app()->clientScript->registerCssFile(self::$_assest . "/notifIt/css/notifIt.css");
	}
}