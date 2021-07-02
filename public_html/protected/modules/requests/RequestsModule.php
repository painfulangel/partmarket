<?php

class RequestsModule extends CWebModule {

    public $_images;
    public $_css;

    public function init() {

        $assetsDir = dirname(__FILE__) . "/assets";
        $this->_images = Yii::app()->assetManager->publish($assetsDir);
        $this->_css = $this->_images . '/css';
        $this->_images .= '/images';
        Yii::app()->clientScript->registerCssFile($this->_css . '/requests_css.css');
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'requests.models.*',
            'requests.components.*',
        ));
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
