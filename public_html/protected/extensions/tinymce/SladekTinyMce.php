<?php

/**
  SladekTinyMCE
 */
 
class SladekTinyMce extends CInputWidget
{
 	public function init() {
		$_SESSION['ELFINDER']['path'] = Yii::getPathOfAlias('webroot').'/uploads/elfinder/'; // URL for the uploads folder
		$_SESSION['ELFINDER']['URL'] = Yii::app()->baseUrl.'/uploads/elfinder/'; // path to the uploads folder
      
		$assetUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.tinymce.js.assets'));
		Yii::app()->clientScript->registerScriptFile($assetUrl.'/tinymce.min.js');	
	}

}