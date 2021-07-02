<?php

class ApiModule extends CWebModule {

    public $maxFileSize = 26214400; //for max size 25mb

    /**
     *
     * @var String path fo upload files start with site path
     */
    public $pathExportFiles = '/upload_files/croses/';

    /**
     *
     * @var String path for upload files start with root file system 
     */
    public $pathFiles = '';
    public $delivery_model;
    public $payment_model;
    public $parserClassName = 'parser_class';
    public $modelClass = 'Parsers';
    public $apiModelClass = 'ParsersApi';
    public $localPriceSearchClass = 'LocalSearchModel';
    public $activeName = 'active_state';

    public function init() {
        $this->setImport(array(
            'shop_cart.models.*',
            'shop_cart.components.*',
        ));
        $this->pathFiles = realpath(Yii::app()->basePath . '/..' . $this->pathExportFiles) . '/';

        $this->delivery_model = new DeliveryMethods;
        $this->payment_model = new PaymentMethods;
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'api.models.*',
            'api.components.*',
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
