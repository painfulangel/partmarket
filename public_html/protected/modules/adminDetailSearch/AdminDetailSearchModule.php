<?php

class AdminDetailSearchModule extends CWebModule {

    public $images;
    public $_css;
    public $cache = 0;
    public $dependency = null;
    public $activeName = 'active_state';
    public $parserClassName = 'parser_class';
    public $modelClass = 'Parsers';
    public $apiModelClass = 'ParsersApi';
    public $localPriceSearchClass = 'LocalSearchModel';
    public $zerosDeliveryValue = 'н.д.';

    public function init() {

        $this->zerosDeliveryValue = Yii::t('adminDetailSearch', 'no information');

        $assetsDir = dirname(__FILE__) . '/assets';
        $getSkladListUrl = Yii::app()->createAbsoluteUrl('/adminDetailSearch/default/getSkladList');
        $getProductListUrl = Yii::app()->createAbsoluteUrl('/adminDetailSearch/default/getProductList');
        $getPriceUrl = Yii::app()->createAbsoluteUrl('/requests/requestGetPrice/create');

        $form_begin = Yii::app()->getModule('shop_cart')->getStartForm();
        $form_end = Yii::app()->getModule('shop_cart')->getEndForm();
        $sklat_post_title = Yii::app()->user->checkAccess('manager') || Yii::app()->user->checkAccess('texts') || Yii::app()->user->checkAccess('managerNotDiscount') || Yii::app()->user->checkAccess('mainManager') || Yii::app()->user->checkAccess('admin') ? Yii::t('adminDetailSearch', 'Storage') : Yii::t('adminDetailSearch', 'Reliability');
        $sklat_post_value = Yii::app()->user->checkAccess('manager') || Yii::app()->user->checkAccess('texts') || Yii::app()->user->checkAccess('managerNotDiscount') || Yii::app()->user->checkAccess('mainManager') || Yii::app()->user->checkAccess('admin') ? 'data.store' : 'data.reliable';
        $this->images = Yii::app()->assetManager->publish($assetsDir);
        $this->_css = $this->images . '/css';
        $this->images .= '/images';

       $form_search_insert=$this->getSearchForm();


     // echo $form_search_insert;
       // die;
        $script = <<<SCRIPT
            var Filter_input_element = 'search-input-detailSearch';
            var Filter_get_sklad_url = '$getSkladListUrl';
            var Filter_get_data_prefix_url = '$getProductListUrl';
            var Filter_text_for_loading='';
            var Filter_block_to_load='main_load_block';
            var Filter_search_phrase_echo='search_phrase_echo';
            var Filter_products = [];
            var Filter_products_other = [];
            var Filter_total_count = 0;
            var Filter_global_time_start = 0;
                
            var Filter_criteria = 'price';
            var Filter_criteria_updown = '<';

            var Filter_shown_products = [];

            var Filter_last_update_parser = '';

            var Filter_temp_flag1 = false;
            var Filter_temp_flag2 = false;

            var Filter_table_of_possiotion = [];
            var Filter_price_changer = 1;
                
            var Filter_criteria_total_rows_per_articul=1;
            var Filter_criteria_top_price_value=0;
            var Filter_criteria_top_delivery_value=0;
                //
            function Filter_before_search() {
                if (document.getElementById(Filter_block_to_load) == undefined) {                
                    $('#admin_content').html('<h1 class="Filter_h1">Результат по запросу: <span id="search_phrase_echo"></span> </h1><div class="clear"> <div id="Filter_done_div"></div> ' +
   '$form_search_insert' +
                                '<div id="Search-grid" class="search_main_table grid-view"><table class="items table"><thead>'
 +'<tr>  <th style="text-align: center;color: #0088cc;">Номер</th><th style="text-align: center;" ><a name="brand">Производитель<span class="caret" style="margin-right: -15px;"></span></a></th><th style="text-align: center;"><a name="name">Наименование<span class="caret"></span></a></th><th style="text-align: center;" ><a name="price">Цена <span style="margin-right: -10px;" class="caret"></span></a></th><th style="text-align: center;"><a name="dostavka">Срок доставки (дней)<span class="caret"></span></a></th><th style="text-align: center;" ><a name="kolichestvo">Кол-во (на складе)<span class="caret" style="margin-right: -15px;"></span></a></th><th style="text-align: center;color: #0088cc;">Склад</th><th style="text-align: center;color: #0088cc;">Кол-во (в заказ)</th></tr>'
  +'      </thead><tbody id="main_load_block">'
//   +'         <tr class="odd"><td style="text-align: center;"><a href="/shop_cart/orders/update?id=1">1</a></td><td style="text-align: center;">29.09.2014 22:19:28</td><td style="text-align: center;">JRA137</td><td style="text-align: center;"> TRW</td><td style="text-align: center;">Тяга рулевая в сборе </td><td style="text-align: center;">2(4) дня</td><td style="text-align: center;">1</td><td style="text-align: center;">1370.08 руб.</td></tr>  '
    +'    </tbody></table><center style="margin-bottom: 40px;"><div class="" style="display:none"> <a onclick="Filter_search_more_find()" class="btn btn-warning" id="getPriceUrlId" href="$getPriceUrl">Не нашли?<br>ЗАПРОСИТЬ НАЛИЧИЕ и ЦЕНУ</a></div></center></div>');
                    $("#Search-grid thead a").click(function(){
                if($(this).hasClass('asc')){
                $("#Search-grid thead a").removeClass('asc');
                $("#Search-grid thead a").removeClass('desc');
                    $(this).addClass('desc');
                        Filter_aply_cort_criteria($(this).attr("name"),'<');}
                else{
                 
                    $("#Search-grid thead a").removeClass('asc');
                $("#Search-grid thead a").removeClass('desc');
                   $(this).addClass('asc');
                        Filter_aply_cort_criteria($(this).attr("name"),'>');}
                 }
                 ); 
                }
             
             
    $('#Filter_price_changer').change(function () {
        Filter_price_changer = $('#Filter_price_changer').val();
        Filter_remake_table();
    });

                
            }

               
                
                
            function Filter_strat_show_proress() {
                document.getElementById('Filter_done_div').innerHTML = '<img src="$this->images/loading.gif" ><div class="Filter_done_text">Подождите, идет поиск</div>';
            }
                
                
                
            function Filter_create_element(data, i,set_rows_assign) {
                temp_element = document.createElement('tr');
//                temp_element.setAttribute('class', 'Filter_items_element_' + (Filter_temp_flag2 ? 'color1' : 'color2'));
//                temp_element.setAttribute('valign', 'middle');
                Filter_temp_flag2 = !Filter_temp_flag2;

                
                if(set_rows_assign>0){
                temp = document.createElement('td');
//                temp.setAttribute('class', 'Filter_items_brand');
                temp.setAttribute('rowspan', set_rows_assign);
                temp.setAttribute('product_id_main_row', data.articul);
                temp.innerHTML = data.articul;
                temp.setAttribute('valign', 'middle');
                temp.setAttribute('style', 'text-align: center;');
                temp_element.appendChild(temp);}
                
                temp = document.createElement('td');
//                temp.setAttribute('class', 'Filter_items_brand');
                temp.innerHTML = data.brand;
                temp.setAttribute('valign', 'middle');
                temp.setAttribute('style', 'text-align: center;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
//                temp.setAttribute('class', 'Filter_items_name');
                temp_text = data.name;
                temp_text = temp_text.replace("/([\,\.\!\?\/\\])/g", "$1 ");
                temp.innerHTML = temp_text;
                temp.setAttribute('valign', 'middle');
                temp.setAttribute('style', 'text-align: center;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
//                temp.setAttribute('class', 'Filter_items_price');
                temp.innerHTML = '<b>'+data.price_echo+'</b>';
                temp.setAttribute('valign', 'middle');
                temp.setAttribute('style', 'text-align: center;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
//                temp.setAttribute('class', 'Filter_items_dostavka');
                temp.innerHTML = data.dostavka;
                temp.setAttribute('valign', 'middle');
                temp.setAttribute('style', 'text-align: center;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
//                temp.setAttribute('class', 'Filter_items_kolichestvo');
                temp.innerHTML = data.kolichestvo;
                temp.setAttribute('valign', 'middle');
                temp.setAttribute('style', 'text-align: center;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
//                temp.setAttribute('class', 'Filter_items_sklad');
                temp.innerHTML = $sklat_post_value;
                temp.setAttribute('valign', 'middle');
                temp.setAttribute('style', 'text-align: center;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
//                temp.setAttribute('class', 'Filter_items_get_order');
                temp.setAttribute('valign', 'middle');
                temp.setAttribute('style', 'text-align: center;min-width: 80px;');
                temp_indf = data.articul + '_' + i;
                temp_var = data.name;
                temp_var = temp_var.replace(/"/g, '&quot;');

                temp.innerHTML = '$form_begin'+
                        '<input type="hidden" name="brand" value="' + data.brand + '" />' +
                '<input type="hidden" name="article" value="' + data.articul + '" />' +

                        '<input type="hidden" name="price_group_1" value="' + data.price_group_1 + '" />' +
                        '<input type="hidden" name="price_group_2" value="' + data.price_group_2 + '" />' +
                        '<input type="hidden" name="price_group_3" value="' + data.price_group_3 + '" />' +
                        '<input type="hidden" name="price_group_4" value="' + data.price_group_4 + '" />' +
                        '<input type="hidden" name="supplier_price" value="' + data.supplier_price + '" />' +
                        '<input type="hidden" name="price" value="' + data.price + '" />' +
                        '<input type="hidden" name="price_echo" value="' + data.price_echo + '" />' +
                        '<input type="hidden" name="description" value="' + data.description + '" />' +
                        '<input type="hidden" name="article_order" value="' + data.articul_order + '" />' +
                        '<input type="hidden" name="supplier_inn" value="' + data.supplier_inn + '" />' +
                        '<input type="hidden" name="supplier" value="' + data.supplier + '" />' +
                        '<input type="hidden" name="store" value="' + data.store + '" />' +
                        '<input type="hidden" name="name" value="' + data.name + '" />' +
                        '<input type="hidden" name="delivery" value="' + data.dostavka + '" />' +
                        '<input type="hidden" name="quantum_all" value="' + data.kolichestvo + '" />' +
                        '<input type="hidden" name="price_data_id" value="' + data.price_data_id + '" />' +
                        '<input type="hidden" name="store_count_state" value="' + data.store_count_state + '" />' +
                        '<input  style="width: 20px;min-width: 20px; " class="textfld" type="text" value="1" name="quantum" min="1" max="' + data.kolichestvo + '" id="quantum"/>' +
                        '<input type="hidden" name="weight" value="' + data.weight + '" />' +
                        
                        //'<input type="hidden" name="" value="' + data. + '" />' +
                       
                        '<input class="js-btn-add-cart"  onclick="if('+data.kolichestvo+'==\'в наличии\'||(this.form.quantum.value<='+data.kolichestvo+'&&'+data.kolichestvo+'>0)){return true;}else {alert(\'Нет в наличии.\');return false;}"  style="width: 38px; height: 29px; border:none; margin: 0;top: 10px; margin-top: -10px;min-width: 20px;" type="submit" name="yt0" value=""/>' +
                        '$form_end';
                temp_element.appendChild(temp);
                return temp_element;
            }
SCRIPT;
            Yii::app()->clientScript->registerScriptFile('/adminDetailSearch/default/jsMainScript');
//        Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish($assetsDir . "/js/detail_main.js"));
//        Yii::app()->clientScript->registerScript(__CLASS__ . "#detailSearch", $script, CClientScript::POS_END);

        Yii::app()->clientScript->registerCssFile($this->_css . "/detail_search.css");
        $this->setImport(array(
            'detailSearch.models.*',
            'detailSearch.components.*',
        ));
    }
    public function getSearchForm() {
     $select_price='<select id="Filter_price_changer" style=" margin-bottom: 0;   width: 80px;"><option value="1">'. Yii::t('adminDetailSearch', 'Price group') .' 1</option><option value="2">'. Yii::t('adminDetailSearch', 'Price group') .' 2</option><option value="3">'. Yii::t('adminDetailSearch', 'Price group') .' 3</option><option value="4">'. Yii::t('adminDetailSearch', 'Price group') .' 4</option></select>';
        return '<form class="" onsubmit="return false;" ><div class="btn-group">
                <input style="margin-right: -5px;border-right: 0;" id="search-input-detailSearch"  value="'.Yii::app()->request->getParam('search_phrase').'"  type="text" data-provide="typeahead" data-items="4" placeholder="'.Yii::t('adminDetailSearch', 'Numbers of details (articles)').'"
                                       />
                            <div class="btn btn-default" style="margin-top: -10px; padding: 4px 10px;margin-right: 10px;box-shadow: none;border: 1px solid #CCC;" id="but2" onclick="Filter_clearForm()"><img src="/images/theme/x.png"></div>                                       
                                  </div><button class="btn" type="submit" onclick="Filter_start_search_location()">'.Yii::t('adminDetailSearch', 'Find').'</button> '.$select_price.'</form>';
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
            return true;
        } else
            return false;
    }
}
