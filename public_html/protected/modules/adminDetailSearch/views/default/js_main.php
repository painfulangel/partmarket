<?php
        $getSkladListUrl = Yii::app()->createAbsoluteUrl('/adminDetailSearch/default/getSkladList');
        $getProductListUrl = Yii::app()->createAbsoluteUrl('/adminDetailSearch/default/getProductList');
        $getPriceUrl = Yii::app()->createAbsoluteUrl('/requests/requestGetPrice/create');

        $form_begin = Yii::app()->getModule('shop_cart')->getStartForm();
        $form_end = Yii::app()->getModule('shop_cart')->getEndForm();
        $sklat_post_title = Yii::app()->user->checkAccess('manager') || Yii::app()->user->checkAccess('texts') || Yii::app()->user->checkAccess('managerNotDiscount') || Yii::app()->user->checkAccess('mainManager') || Yii::app()->user->checkAccess('admin') ? Yii::t('adminDetailSearch', 'Storage') : Yii::t('adminDetailSearch', 'Reliability');
        $sklat_post_value = Yii::app()->user->checkAccess('manager') || Yii::app()->user->checkAccess('texts') || Yii::app()->user->checkAccess('managerNotDiscount') || Yii::app()->user->checkAccess('mainManager') || Yii::app()->user->checkAccess('admin') ? 'data.store' : 'data.reliable';


$form_search_insert=str_replace(array("\n","\r"),"",Yii::app()->getModule('adminDetailSearch')->getSearchForm());
?>


var Filter_input_element = 'search-input-detailSearch';
var Filter_get_sklad_url = '<?= $getSkladListUrl ?>';
var Filter_get_data_prefix_url = '<?= $getProductListUrl ?>';
var Filter_text_for_loading = '';
var Filter_block_to_load = 'main_load_block';
var Filter_search_phrase_echo = 'search_phrase_echo';
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

var Filter_criteria_total_rows_per_articul = 1;
var Filter_criteria_top_price_value = 0;
var Filter_criteria_top_delivery_value = 0;

function Filter_before_search() {
    if (document.getElementById(Filter_block_to_load) == undefined) {
        $('#admin_content').html('<h1 class="Filter_h1"><?= Yii::t('adminDetailSearch', 'Result on demand:')?> <span id="search_phrase_echo"></span> </h1><div class="clear"></div><?= $form_search_insert ?> <div id="Filter_done_div"></div> ' +
                '<div id="Search-grid" class="search_main_table grid-view"><table class="items table"><thead>'
                + '<tr>  <th style="text-align: center;color: #0088cc;"><?= Yii::t('adminDetailSearch', 'Number') ?></th><th style="text-align: center;" ><a name="brand"><?= Yii::t('adminDetailSearch', 'Manufacturer') ?><span class="caret" style="margin-right: -15px;"></span></a></th><th style="text-align: center;"><a name="name"><?= Yii::t('adminDetailSearch', 'Name') ?><span class="caret"></span></a></th><th style="text-align: center;" ><a name="price"><?= Yii::t('adminDetailSearch', 'Price') ?> <span style="margin-right: -10px;" class="caret"></span></a></th><th style="text-align: center;"><a name="dostavka"><?= Yii::t('adminDetailSearch', 'Delivery time (days)') ?><span class="caret"></span></a></th><th style="text-align: center;" ><a name="kolichestvo"><?= Yii::t('adminDetailSearch', 'Storage on hand') ?><span class="caret" style="margin-right: -15px;"></span></a></th><th style="text-align: center;color: #0088cc;"><?= Yii::t('adminDetailSearch', 'Storage') ?></th><th style="text-align: center;color: #0088cc;"><?= Yii::t('adminDetailSearch', 'Quantity (in order)') ?></th></tr>'
                + '</thead><tbody id="main_load_block">'
                + '</tbody></table><center style="margin-bottom: 40px;"><div class="" style="display:none"> <a onclick="Filter_search_more_find()" class="btn btn-warning" id="getPriceUrlId" href="<?= $getPriceUrl ?>"><?= Yii::t('adminDetailSearch', 'Not found?<br>Get availability and price') ?></a></div></center></div>');
        $("#Search-grid thead a").click(function () {
            if ($(this).hasClass('asc')) {
                $("#Search-grid thead a").removeClass('asc');
                $("#Search-grid thead a").removeClass('desc');
                $(this).addClass('desc');
                Filter_aply_cort_criteria($(this).attr("name"), '<');
            }
            else {
                $("#Search-grid thead a").removeClass('asc');
                $("#Search-grid thead a").removeClass('desc');
                $(this).addClass('asc');
                Filter_aply_cort_criteria($(this).attr("name"), '>');
            }
        }
        );
    }

    $('#Filter_price_changer').change(function () {
        Filter_price_changer = $('#Filter_price_changer').val();
        Filter_remake_table();
    });
}

function Filter_strat_show_proress() {
    document.getElementById('Filter_done_div').innerHTML = '<img src="<?= Yii::app()->getModule('adminDetailSearch')->images ?>/loading.gif" ><div class="Filter_done_text"><?= Yii::t('adminDetailSearch', 'Please wait, search') ?></div>';
}

function Filter_create_element(data, i, set_rows_assign) {
    temp_element = document.createElement('tr');
    Filter_temp_flag2 = !Filter_temp_flag2;

    if (set_rows_assign > 0) {
        temp = document.createElement('td');
        temp.setAttribute('rowspan', set_rows_assign);
        temp.setAttribute('product_id_main_row', data.articul);
        temp.innerHTML = data.articul;
        temp.setAttribute('valign', 'middle');
        temp.setAttribute('style', 'text-align: center;');
        temp_element.appendChild(temp);
    }

    temp = document.createElement('td');
    temp.innerHTML = data.brand;
    temp.setAttribute('valign', 'middle');
    temp.setAttribute('style', 'text-align: center;');
    temp_element.appendChild(temp);

    temp = document.createElement('td');
    temp_text = data.name;
    temp_text = temp_text.replace("/([\,\.\!\?\/\\])/g", "$1 ");
    temp.innerHTML = temp_text;
    temp.setAttribute('valign', 'middle');
    temp.setAttribute('style', 'text-align: center;');
    temp_element.appendChild(temp);

    temp = document.createElement('td');
    temp.innerHTML = '<b>' + data.price_echo + '</b>';
    temp.setAttribute('valign', 'middle');
    temp.setAttribute('style', 'text-align: center;');
    temp_element.appendChild(temp);

    temp = document.createElement('td');
    temp.innerHTML = data.dostavka;
    temp.setAttribute('valign', 'middle');
    temp.setAttribute('style', 'text-align: center;');
    temp_element.appendChild(temp);

    temp = document.createElement('td');
    temp.innerHTML = data.kolichestvo;
    temp.setAttribute('valign', 'middle');
    temp.setAttribute('style', 'text-align: center;');
    temp_element.appendChild(temp);

    temp = document.createElement('td');
    temp.innerHTML = <?= $sklat_post_value ?>;
    temp.setAttribute('valign', 'middle');
    temp.setAttribute('style', 'text-align: center;');
    temp_element.appendChild(temp);

    temp = document.createElement('td');
    temp.setAttribute('valign', 'middle');
    temp.setAttribute('style', 'text-align: center;min-width: 80px;');
    temp_indf = data.articul + '_' + i;
    temp_var = data.name;
    temp_var = temp_var.replace(/"/g, '&quot;');

    temp.innerHTML = '<?= $form_begin ?>' +
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
            '<input class="js-btn-add-cart"  onclick="if(' + data.kolichestvo + '==\'<?= Yii::t('adminDetailSearch', 'Available') ?>\'||(this.form.quantum.value<=' + data.kolichestvo + '&&' + data.kolichestvo + '>0)){return true;}else {alert(\'<?= Yii::t('adminDetailSearch', 'Unavailable.') ?>\');return false;}"  style="width: 38px; height: 29px; border:none; margin: 0;top: 10px; margin-top: -10px;min-width: 20px;" type="submit" name="yt0" value=""/>' +
            '<?= $form_end ?>';
    temp_element.appendChild(temp);
    return temp_element;
}

function Filter_check_is_good_time(data) {
    if (data == null) {
        Filter_total_count--;
        Filter_remake_table();
        if (Filter_total_count > 0)
            return false;
        Filter_done_search();
        return false;
    }
    if (Filter_global_time_start == data.time_start) {
        return true;
    }
    return false;
}

function Filter_start_search() {
    Filter_before_search();
    Filter_strat_show_proress();
    d = new Date();
    Filter_global_time_start = d.getTime();
    document.getElementById(Filter_input_element).value = document.getElementById(Filter_input_element).value.replace(/\-/g, '').replace(/\./g, '').toUpperCase();
    document.getElementById(Filter_search_phrase_echo).innerHTML = document.getElementById(Filter_input_element).value;

    params = {
        time_start: Filter_global_time_start
    };
    Filter_products = [];
    Filter_products_other = [];
    url = '/adminDetailSearch/default/search?search_phrase=' + encodeURIComponent((document.getElementById(Filter_input_element).value).toUpperCase());
    if (url != window.location) {
        window.history.pushState(null, null, url);
    }
    $.getJSON(Filter_get_sklad_url, params, function (data) {
        if (!Filter_check_is_good_time(data))
            return;
        Filter_total_count = data.sklads_count;
        for (i = 0; i < data.sklads_count; i++) {
            params = {
                search_phrase: document.getElementById(Filter_input_element).value.toUpperCase(),
                search_sklad: data.sklads[i],
                time_start: Filter_global_time_start
            };
            $.ajaxSetup({
                "error": function () {
                    Filter_total_count--;
                    if (Filter_last_update_parser != data.search_sklad && Filter_total_count > 0)
                        return;
                    Filter_remake_table();
                    Filter_done_search();
                }});
            $.getJSON(Filter_get_data_prefix_url, params, function (data) {
                Filter_upgrade_elements(data);
            });
        }
    });
}

function getProps(toObj, tcSplit)
{
    if (!tcSplit)
        tcSplit = '\n';
    var lcRet = '';
    var lcTab = '    ';
    for (var i in toObj)
        lcRet += lcTab + i + " : " + toObj[i] + tcSplit;
    lcRet = '{' + tcSplit + lcRet + '}';
    return lcRet;
}

function Filter_upgrade_elements(data) {
    if (!Filter_check_is_good_time(data))
        return;
    Filter_last_update_parser = data.search_sklad;
    for (i = 0; i < data.products_count; i++) {
        element = data.products[i];
        if (Filter_products[element.articul] == undefined) {
            Filter_products[element.articul] = {
                count: 1,
                elements: [element],
                criteria: 'general'
            }
        } else {
            flag = true;
            for (j = 0; j < Filter_products[element.articul].count; j++) {
                if (Filter_products[element.articul].elements[j].name == element.name && Filter_products[element.articul].elements[j].price == element.price && Filter_products[element.articul].elements[j].kolichestvo == element.kolichestvo && Filter_products[element.articul].elements[j].dostavka == element.dostavka && Filter_products[element.articul].elements[j].brand == element.brand)
                    flag = false;
            }
            if (flag) {
                Filter_products[element.articul].elements[Filter_products[element.articul].count] = element;
                Filter_products[element.articul].count++;
            }
        }
    }
    for (i = 0; i < data.products_other_count; i++) {
        element = data.products_other[i];
        if (Filter_products_other[element.articul] == undefined) {
            Filter_products_other[element.articul] = {
                count: 1,
                elements: [element],
                criteria: 'general'
            }
        } else {
            flag = true;
            for (j = 0; j < Filter_products_other[element.articul].count; j++) {
                if (Filter_products_other[element.articul].elements[j].name == element.name && Filter_products_other[element.articul].elements[j].price == element.price && Filter_products_other[element.articul].elements[j].kolichestvo == element.kolichestvo && Filter_products_other[element.articul].elements[j].dostavka == element.dostavka && Filter_products_other[element.articul].elements[j].brand == element.brand)
                    flag = false;
            }
            if (flag) {
                Filter_products_other[element.articul].elements[Filter_products_other[element.articul].count] = element;
                Filter_products_other[element.articul].count++;
            }
        }
    }
    if (!Filter_check_is_good_time(data))
        return;
    Filter_total_count--;
    if (Filter_last_update_parser != data.search_sklad && Filter_total_count > 0)
        return;
    Filter_remake_table();
    Filter_done_search();
}

function Filter_done_search() {
    if (Filter_total_count <= 0) {
        document.getElementById('Filter_done_div').innerHTML = '<div class="Filter_done_text"><?= Yii::t('adminDetailSearch', 'Search is finished') ?></div>'
    }
}

function Filter_shift_and_insert(mas, element, cur, total) {
    for (i = total; i > cur; i--) {
        mas[i] = mas[i - 1];
    }
    mas[cur] = element;
    return mas;
}

function Filter_sort_criateria() {
    products = [];
    for (var articul in Filter_products) {
        for (var j in Filter_products[articul].elements) {
            Filter_products[articul].elements[j].price = Filter_products[articul].elements[j].all_prices[Filter_price_changer - 1].price;
            Filter_products[articul].elements[j].price_echo = Filter_products[articul].elements[j].all_prices[Filter_price_changer - 1].price_echo;
        }
    }
    for (var articul in Filter_products) {
        Filter_criteria_search = Filter_criteria;
        if (Filter_products[articul].criteria != 'general') {
            Filter_criteria_search = Filter_products[articul].criteria;
        }
        total = 0;
        products[articul] = [];
        for (var j in Filter_products[articul].elements) {
            if (total == 0) {
                products[articul][total] = Filter_products[articul].elements[j];
                total++;
            } else {
                for (var atribute in Filter_products[articul].elements[j]) {
                    if (Filter_criteria_search == atribute) {
                        old = total;
                        for (i = 0; i < total; i++) {
                            a = eval('Filter_products[articul].elements[j].' + atribute);
                            b = eval('products[articul][i].' + atribute);
                            if ((Filter_criteria_updown == '>' && a > b) || (Filter_criteria_updown == '<' && a < b)) {
                                products[articul] = Filter_shift_and_insert(products[articul], Filter_products[articul].elements[j], i, total);
                                total++;
                                i = total;
                            }
                        }
                        if (old == total) {
                            products[articul][total] = Filter_products[articul].elements[j];
                            total++;
                        }
                        break;
                    }
                }
            }
        }
    }
    return products;
}

function Filter_sort_criateria_other() {
    products = [];
    for (var articul in Filter_products_other) {
        for (var j in Filter_products_other[articul].elements) {
            Filter_products_other[articul].elements[j].price = Filter_products_other[articul].elements[j].all_prices[Filter_price_changer - 1].price;
            Filter_products_other[articul].elements[j].price_echo = Filter_products_other[articul].elements[j].all_prices[Filter_price_changer - 1].price_echo;
        }
    }
    if (Filter_criteria != 'brand') {
        for (var articul in Filter_products_other) {
            Filter_criteria_search = Filter_criteria;
            if (Filter_products_other[articul].criteria != 'general') {
                Filter_criteria_search = Filter_products_other[articul].criteria;
            }
            total = 0;
            products[articul] = [];
            for (var j in Filter_products_other[articul].elements) {
                if (total == 0) {
                    products[articul][total] = Filter_products_other[articul].elements[j];
                    total++;
                } else {
                    for (var atribute in Filter_products_other[articul].elements[j]) {
                        if (Filter_criteria_search == atribute) {
                            old = total;
                            for (i = 0; i < total; i++) {
                                a = eval('Filter_products_other[articul].elements[j].' + atribute);
                                b = eval('products[articul][i].' + atribute);
                                if ((Filter_criteria_updown == '>' && a > b) || (Filter_criteria_updown == '<' && a < b)) {
                                    products[articul] = Filter_shift_and_insert(products[articul], Filter_products_other[articul].elements[j], i, total);
                                    total++;
                                    i = total;
                                }
                            }
                            if (old == total) {
                                products[articul][total] = Filter_products_other[articul].elements[j];
                                total++;
                            }
                            break;
                        }
                    }
                }
            }
        }
    }
    if (Filter_criteria == 'brand') {
        total_products = 0;
        temp_table = [];
        for (var articul_trash in Filter_products_other) {
            min = -1;
            for (var articul in Filter_products_other) {
                if (products[articul] == undefined && (min == -1 || (Filter_criteria_updown == '>' && Filter_products_other[articul].elements[0].brand > Filter_products_other[min].elements[0].brand) || (Filter_criteria_updown == '<' && Filter_products_other[articul].elements[0].brand < Filter_products_other[min].elements[0].brand))) {
                    min = articul;
                }
            }
            products[min] = Filter_products_other[min].elements;
            temp_table[total_products] = min;
            total_products++;
            Filter_table_of_possiotion = temp_table;
        }
    }
    return products;
}




function Filter_remake_table() {
    products = Filter_sort_criateria();
    document.getElementById(Filter_block_to_load).innerHTML = '';
    elements = document.getElementById(Filter_block_to_load);
    elements2 = document.createElement('div');
    elements2.setAttribute('class', 'Filter_group_items');
    temp1 = document.createElement('tr');
    temp2 = document.createElement('td');
    temp2.setAttribute('colspan', '8');
    temp1.appendChild(temp2);

    temp = document.createElement('center');
    temp.setAttribute('class', 'Filter_detail_name');
    temp.innerHTML = '<?= Yii::t('adminDetailSearch', 'Desired article') ?>';
    temp2.appendChild(temp);
    elements.appendChild(temp1);
    flag = false;
    for (var articul in products) {
        temp_rows_count = 0;
        for (var j in products[articul]) {
            temp_rows_count++;
        }
        if (temp_rows_count == 0)
            continue;

        flag = true;
        artikul_element = Filter_make_new_articul_element(articul);

        temp_rows_count = 0;
        for (var j in products[articul]) {
            temp_rows_count++;
        }
        set_rows_assign = Filter_criteria_total_rows_per_articul;
        set_rows_assign_save = temp_rows_count;
        temp_rows_count = 0;
        flag_set_maximize_block = false;
        for (var j in products[articul]) {
            insert_element = Filter_create_element(products[articul][j], j, set_rows_assign);
            if ((temp_rows_count >= Filter_criteria_total_rows_per_articul)
                    || ((Filter_criteria_top_price_value != 0 && Filter_criteria_top_price_value != '') && (parseInt(products[articul][j].price_echo, 10) > parseInt(Filter_criteria_top_price_value, 10)))
                    || ((Filter_criteria_top_delivery_value != 0 && Filter_criteria_top_delivery_value != '') && (parseInt(products[articul][j].price_echo, 10) > parseInt(Filter_criteria_top_price_value, 10)))
                    ) {
                flag_set_maximize_block = true;
                insert_element.setAttribute('class', 'product' + articul);
                insert_element.setAttribute('style', 'display:none;');
            }
            elements.appendChild(insert_element);
            set_rows_assign = 0;
            temp_rows_count++;
        }
        temp1 = document.createElement('tr');
        temp2 = document.createElement('td');
        temp2.setAttribute('colspan', '8');
        temp1.appendChild(temp2);
        temp = document.createElement('center');
        temp.setAttribute('class', 'Filter_detail_name');

        temp_text = '';
        if (flag_set_maximize_block)
            temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + articul + '"><?= Yii::t('adminDetailSearch', 'Show the rest') ?> ' + products[articul][0].brand + '</a> <span>\\</span> ';

        temp.innerHTML = temp_text + '<a class="Filter_min_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '"><?= Yii::t('adminDetailSearch', 'Show spare parts') ?> ' + products[articul][j].brand + ' <?= Yii::t('adminDetailSearch', 'Minimum delivery time') ?></a>';
        temp2.appendChild(temp);
        if (temp_rows_count > 1)
            elements.appendChild(temp1);
    }
    if (!flag) {
        if (Filter_total_count <= 0) {
            temp1 = document.createElement('tr');
            temp2 = document.createElement('td');
            temp2.setAttribute('colspan', '8');
            temp1.appendChild(temp2);

            temp = document.createElement('center');
            temp.innerHTML = '<?= Yii::t('adminDetailSearch', 'Goods not found') ?>';
            temp2.appendChild(temp);
            elements.appendChild(temp1);
        }
    }

    products = Filter_sort_criateria_other();
    elements2 = document.createElement('div');
    elements2.setAttribute('class', 'Filter_group_items');

    temp1 = document.createElement('tr');
    temp2 = document.createElement('td');
    temp2.setAttribute('colspan', '8');
    temp1.appendChild(temp2);

    temp = document.createElement('center');
    temp.setAttribute('class', 'Filter_detail_name');
    temp.innerHTML = '<?= Yii::t('adminDetailSearch', 'Analogues and replacement of other manufacturers') ?>';

    temp2.appendChild(temp);
    elements.appendChild(temp1);

    flag = false;
    temp_i = 0;
    for (var articul in products) {
        if (Filter_criteria == 'brand') {
            articul = Filter_table_of_possiotion[temp_i];
            temp_i++;
        }

        temp_rows_count = 0;
        for (var j in products[articul]) {
            temp_rows_count++;
        }
        if (temp_rows_count == 0)
            continue;

        flag = true;
        artikul_element = Filter_make_new_articul_element(articul);
        artikul_elements = document.createElement('div');
        artikul_elements.setAttribute('class', 'Filter_items_articul_elements');
        temp_rows_count = 0;
        for (var j in products[articul]) {
            temp_rows_count++;
        }
        set_rows_assign = Filter_criteria_total_rows_per_articul;
        set_rows_assign_save = temp_rows_count;
        temp_rows_count = 0;
        flag_set_maximize_block = false;
        for (var j in products[articul]) {
            insert_element = Filter_create_element(products[articul][j], j, set_rows_assign);
            if ((temp_rows_count >= Filter_criteria_total_rows_per_articul)
                    || ((Filter_criteria_top_price_value != 0 && Filter_criteria_top_price_value != '') && (parseInt(products[articul][j].price_echo, 10) > parseInt(Filter_criteria_top_price_value, 10)))
                    || ((Filter_criteria_top_delivery_value != 0 && Filter_criteria_top_delivery_value != '') && (parseInt(products[articul][j].price_echo, 10) > parseInt(Filter_criteria_top_price_value, 10)))
                    ) {
                flag_set_maximize_block = true;
                insert_element.setAttribute('class', 'product' + articul);
                insert_element.setAttribute('style', 'display:none;');
            }
            elements.appendChild(insert_element);
            set_rows_assign = 0;
            temp_rows_count++;
        }
        temp1 = document.createElement('tr');
        temp2 = document.createElement('td');
        temp2.setAttribute('colspan', '8');
        temp1.appendChild(temp2);
        temp = document.createElement('center');
        temp.setAttribute('class', 'Filter_detail_name');
        temp_text = '';
        if (flag_set_maximize_block)
            temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + articul + '"><?= Yii::t('adminDetailSearch', 'Show the rest') ?> ' + products[articul][0].brand + '</a> <span>\\</span> ';
        temp.innerHTML = temp_text + '<a class="Filter_min_other_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '"><?= Yii::t('adminDetailSearch', 'Show spare parts') ?> ' + products[articul][j].brand + ' <?= Yii::t('adminDetailSearch', 'Minimum delivery time') ?></a>';
        temp2.appendChild(temp);
        if (temp_rows_count > 1)
            elements.appendChild(temp1);
//        artikul_element.appendChild(artikul_elements);
//        elements.appendChild(artikul_element);
    }
    if (!flag) {
        if (Filter_total_count <= 0) {
            temp1 = document.createElement('tr');
            temp2 = document.createElement('td');
            temp2.setAttribute('colspan', '8');
            temp1.appendChild(temp2);

            temp = document.createElement('center');
            //temp.setAttribute('class', 'Filter_detail_name');
            temp.innerHTML = '<?= Yii::t('adminDetailSearch', 'The analogy is not found') ?>';
            temp2.appendChild(temp);
            elements.appendChild(temp1);
        }
    }
    $('.Filter_show_hide_products').click(function () {
        $('.product' + $(this).attr('product_id')).show();
        $('[product_id_main_row="' + $(this).attr('product_id') + '"]').attr('rowspan', $(this).attr('set_rows'));
        $(this).parent().find('span').remove();
        $(this).remove();
    });
    $('.Filter_min_show_hide_products').click(function () {
        Filter_products[$(this).attr('product_id')].criteria = 'dostavka';
        Filter_remake_table();
    });
    $('.Filter_min_other_show_hide_products').click(function () {
        Filter_products_other[$(this).attr('product_id')].criteria = 'dostavka';
        Filter_remake_table();
    });
}

function Filter_make_new_articul_element(articul) {
    new_element = document.createElement('tr');
    Filter_temp_flag1 = !Filter_temp_flag1;
    new_element.id = 'Filter_items_articul' + articul;
    return new_element;
}
function Filter_save_criteria_to_delete() {
    Filter_criteria_total_rows_per_articul = 1;
    Filter_criteria_top_price_value = document.getElementById('Filter_criteria_div_price').value;
    Filter_criteria_top_delivery_value = document.getElementById('Filter_criteria_div_delivery').value;
    Filter_remake_table();
}
function Filter_set_sort() {
    Filter_criteria = document.getElementById('Filter_sort_by').value;
    Filter_remake_table();
}
function Filter_set_sort_updown() {
    Filter_criteria_updown = document.getElementById('Filter_sort_by_updown').value;
    Filter_remake_table();
}
function Filter_aply_cort_criteria(crit1, crit2) {
    Filter_criteria = crit1;
    Filter_criteria_updown = crit2;
    Filter_remake_table();
}

$(document).ready(function () {
    $('#search-input-detailSearch ').keyup(function (e) {
        if ((e.which == 13)) {
            document.location = '/adminDetailSearch/default/search?search_phrase=' + encodeURIComponent(document.getElementById(Filter_input_element).value);
            return false;
        }
    });
});

function Filter_clearForm() {
    $('#search-input-detailSearch').val('');
}
function Filter_search_page(num) {
    Filter_before_search();
    $(document).ready(function () {
        document.getElementById(Filter_input_element).value = num;
        Filter_start_search();
    });

}
function Filter_start_search_location() {
    document.location = '/adminDetailSearch/default/search?search_phrase=' + encodeURIComponent(document.getElementById(Filter_input_element).value);
}
function Filter_search_more_find() {
    $("#getPriceUrlId").attr("href", $("#getPriceUrlId").attr("href") + '?detail=' + document.getElementById(Filter_input_element).value);
    return true;
}