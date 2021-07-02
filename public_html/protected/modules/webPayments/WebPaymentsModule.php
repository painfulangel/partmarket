<?php
class WebPaymentsModule extends CWebModule {
    public $_css;
    
    public function init() {
        $assetsDir = dirname(__FILE__) . "/assets";
        
        if (isset(Yii::app()->assetManager) && is_object(Yii::app()->assetManager)) {
            $this->_css = Yii::app()->assetManager->publish($assetsDir).'/css';
        }
        
        if (isset(Yii::app()->clientScript) && is_object(Yii::app()->clientScript)) {
            Yii::app()->clientScript->registerCssFile($this->_css . '/main.css');
        }
        
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'webPayments.models.*',
            'webPayments.components.*',
        ));
    }

    public function isActivePayments() {
        return WebPaymentsSystem::model()->count('active_state=1') > 0;
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