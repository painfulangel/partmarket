<?php
class DetailSearchModule extends CWebModule {
    public $images;
    public $_css;
    public $cache = 0;
    public $dependency = null;
    public $activeName = 'active_state';
    public $parserClassName = 'parser_class';
    public $modelClass = 'Parsers';
    public $apiModelClass = 'ParsersApi';
    public $localPriceSearchClass = 'LocalSearchModel';
    public $localMyPriceSearchClass = 'LocalMySearchModel';
    public $zerosDeliveryValue = 'н.д.';

    public function init() {
        $assetsDir = dirname(__FILE__) . '/assets';
		
        $getSkladListUrl = Yii::app()->createAbsoluteUrl('/detailSearch/default/getSkladList');
        $getProductListUrl = Yii::app()->createAbsoluteUrl('/detailSearch/default/getProductList');
        $getPriceUrl = Yii::app()->createAbsoluteUrl('/requests/requestGetPrice/create');

        $timelimit = Yii::app()->config->get("Site.DetailSearchTimeout");
        $timelimit = preg_replace("/[^0-9]/", "", $timelimit);
        if (empty($timelimit))
            $timelimit = 300;
        $timelimit.='000';

        $form_begin = Yii::app()->getModule('shop_cart')->getStartForm();
        $form_end = Yii::app()->getModule('shop_cart')->getEndForm();
		//$sklat_post_title = Yii::app()->user->checkAccess('manager') || Yii::app()->user->checkAccess('texts') || Yii::app()->user->checkAccess('managerNotDiscount') || Yii::app()->user->checkAccess('mainManager') || Yii::app()->user->checkAccess('admin') ? 'Склад' : 'Надежность';
		//$sklat_post_value = Yii::app()->user->checkAccess('manager') || Yii::app()->user->checkAccess('texts') || Yii::app()->user->checkAccess('managerNotDiscount') || Yii::app()->user->checkAccess('mainManager') || Yii::app()->user->checkAccess('admin') ? 'data.store' : 'data.reliable';
        $sklat_post_title = 'Склад';
        $sklat_post_value = 'data.store';
        $this->images = Yii::app()->assetManager->publish($assetsDir);
        $this->_css = $this->images . '/css';
        $this->images .= '/images';

        Yii::app()->clientScript->registerScriptFile("/detailSearch/default/jsMainScript");
        
        //Вероятность поставки
        Yii::app()->clientScript->registerScriptFile("/js/jquery.rateit.min.js");
        Yii::app()->clientScript->registerCssFile("/css/rateit.css");
        	
        Yii::app()->clientScript->registerCssFile($this->_css . "/detail_search.css");

        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'detailSearch.models.*',
            'detailSearch.components.*',
                // 'application.modules.prices.components.*',
        ));
    }

    public function getSearchForm() {
        return '<form class="" onsubmit="return false;" ><div class="btn-group">
<input style="margin-right: -5px;border-right: 0;" id="search-input-detailSearch"    type="text" data-provide="typeahead" data-items="4" placeholder="' . Yii::t('adminDetailSearch', 'Numbers of details (articles)') . '"
                                       />
<div class="btn btn-default" id="but2" onclick="Filter_clearForm()"><img src="/images/theme/x.png"></div>                                       

                                  </div><button class="btn" type="submit" onclick="Filter_start_search_location()">' . Yii::t('adminDetailSearch', 'Find') . '</button></form>';
//        return '<form class="" onsubmit="return false;" >
//                                    <a target="_blank" href="http://catalog.autojek.ru/" class="pull-right catalog-cat btn"  onfocus="this.blur();"><img src="' . Yii::app()->theme->baseUrl . '/gfx/catcar.png"></a>
//                                    <button class="btn"  onclick="Filter_start_search_location()"  onfocus="this.blur();">поиск автозапчастей</button>
//                                    <input type="text"  id="search-input-detailSearch" class="search-query" style="color: #000;" value="Введите номера деталей через запятую" onfocus="if (this.value == \'Введите номера деталей через запятую\') {
//                                                this.value = \'\';
//                                                this.style.color = \'#000\';
//                                            }" onblur="if (this.value == \'\') {
//                                                        this.value = \'Введите номера деталей через запятую\';
//                                                        this.style.color = \'#000\';
//                                                    }" />
//                                </form>';
    }

    protected function getDbConnection() {
        if ($this->cache)
            $db = Yii::app()->db->cache($this->cache, $this->dependency);
        else
            $db = Yii::app()->db;

        return $db;
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