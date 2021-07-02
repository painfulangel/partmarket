<?php

class ParsersModule extends CWebModule {

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'parsers.models.*',
            'parsers.components.*',
        ));
    }

    public function getPriceFunction($value) {
        return Yii::app()->getModule('pricegroups')->getPrice(Yii::app()->getModule('currencies')->getPrice($value['price'], $value['currency']), $value['price_group'], $value['brand']);
    }

    /**
     *
     * @var string code to echo price value 
     */
    public function getPriceFormatFunction($price) {
        return Yii::app()->getModule('currencies')->getFormatPrice($price);
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
