
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
        time_start: Filter_global_time_start,
        search_phrase: (document.getElementById(Filter_input_element).value).toUpperCase()
    };
    Filter_products = [];
    Filter_products_other = [];
    url = '/search?search_phrase=' + encodeURIComponent((document.getElementById(Filter_input_element).value).toUpperCase());
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
                timeout: Filter_search_timelimit,
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

    for (var i in toObj) // обращение к свойствам объекта по индексу
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
//        alert(getProps(Filter_products));
    }
//    alert(Filter_products);
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
        document.getElementById('Filter_done_div').innerHTML = '<div class="Filter_done_text">Поиск закончен</div>';
        total = 0;
        console.log(total);
//        products = Filter_sort_criateria();
        for (var articul in Filter_products)
            for (var j in Filter_products[articul].elements)
                total++;
//        products = Filter_sort_criateria_other();
        for (var articul in Filter_products_other)
            for (var j in Filter_products_other[articul].elements)
                total++;

        console.log(total);

        if (total == 0) {
            Filter_search_more_find();
            document.location = $("#getPriceUrlId").attr("href");
        }
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
//    elements = document.createElement('div');
    elements = document.getElementById(Filter_block_to_load);
    elements2 = document.createElement('div');
    elements2.setAttribute('class', 'Filter_group_items');
    temp1 = document.createElement('tr');
    temp2 = document.createElement('td');
    temp2.setAttribute('colspan', '8');
    temp1.appendChild(temp2);

    temp = document.createElement('center');
    temp.setAttribute('class', 'Filter_detail_name');
    temp.innerHTML = 'Искомый артикул';
    temp2.appendChild(temp);
    elements.appendChild(temp1);
    flag = false;
    for (var articul in products) {
//Count products with sort
        temp_rows_count = 0;
        for (var j in products[articul]) {
            temp_rows_count++;
        }
        if (temp_rows_count == 0)
            continue;

        flag = true;

        artikul_element = Filter_make_new_articul_element(articul);

        temp_rows_count = 0;
        brands_full = [];
        for (var j in products[articul]) {
            temp_rows_count++;
            if (brands_full[$.trim(products[articul][j].brand.toUpperCase())] == undefined) {
                brands_full[$.trim(products[articul][j].brand.toUpperCase())] = {'items': [], count: 0};
            }
            brands_full[$.trim(products[articul][j].brand.toUpperCase())].items[j] = products[articul][j];
            brands_full[$.trim(products[articul][j].brand.toUpperCase())].count++;
        }
//        console.log(brands_full);

        for (var b1 in brands_full) {
            set_rows_assign = Filter_criteria_total_rows_per_articul;
            set_rows_assign_save = brands_full[b1].count;
            temp_rows_count = 0;
            flag_set_maximize_block = false;
            for (var j in brands_full[b1].items) {
                insert_element = Filter_create_element(products[articul][j], j, set_rows_assign);
                if ((temp_rows_count >= Filter_criteria_total_rows_per_articul)
                        || ((Filter_criteria_top_price_value != 0 && Filter_criteria_top_price_value != '') && (parseInt(products[articul][j].price_echo, 10) > parseInt(Filter_criteria_top_price_value, 10)))
                        || ((Filter_criteria_top_delivery_value != 0 && Filter_criteria_top_delivery_value != '') && (parseInt(products[articul][j].price_echo, 10) > parseInt(Filter_criteria_top_price_value, 10)))
                        ) {

                    flag_set_maximize_block = true;
                    insert_element.setAttribute('class', 'product' + b1.replace(/([^A-Za-z0-9])/g, '') + articul);
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
                temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + b1.replace(/([^A-Za-z0-9])/g, '') + articul + '">Показать остальные ' + b1 + '</a> <span>\\</span> ';
            temp.innerHTML = temp_text + '<a class="Filter_min_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '">Показать запчасти ' + b1 + ' с минимальным сроком поставки</a>';

            temp2.appendChild(temp);
            if (temp_rows_count > 1)
                elements.appendChild(temp1);
            elements.appendChild(artikul_element);
        }
        continue;
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
            temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + articul + '">Показать остальные ' + products[articul][0].brand + '</a> <span>\\</span> ';
        temp.innerHTML = temp_text + '<a class="Filter_min_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '">Показать запчасти ' + products[articul][j].brand + ' с минимальным сроком поставки</a>';

        temp2.appendChild(temp);
        if (temp_rows_count > 1)
            elements.appendChild(temp1);
        elements.appendChild(artikul_element);
    }
    if (!flag) {
        if (Filter_total_count <= 0) {
            temp1 = document.createElement('tr');
            temp2 = document.createElement('td');
            temp2.setAttribute('colspan', '8');
            temp1.appendChild(temp2);

            temp = document.createElement('center');
            //temp.setAttribute('class', 'Filter_detail_name');
            temp.innerHTML = 'Товаров не найдено';
            temp2.appendChild(temp);
            elements.appendChild(temp1);
        }
    } else {
//        elements.appendChild(elements2);
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
    temp.innerHTML = 'Аналоги и замены других производителей';

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
        brands_full = [];
        for (var j in products[articul]) {
            temp_rows_count++;

            if (brands_full[$.trim(products[articul][j].brand.toUpperCase())] == undefined) {
                brands_full[$.trim(products[articul][j].brand.toUpperCase())] = {'items': [], count: 0};
            }
            brands_full[$.trim(products[articul][j].brand.toUpperCase())].items[j] = products[articul][j];
            brands_full[$.trim(products[articul][j].brand.toUpperCase())].count++;
        }

        for (var b1 in brands_full) {
            set_rows_assign = Filter_criteria_total_rows_per_articul;
            set_rows_assign_save = brands_full[b1].count;
            temp_rows_count = 0;
            flag_set_maximize_block = false;
            for (var j in brands_full[b1].items) {
                insert_element = Filter_create_element(products[articul][j], j, set_rows_assign);
                if ((temp_rows_count >= Filter_criteria_total_rows_per_articul)
                        || ((Filter_criteria_top_price_value != 0 && Filter_criteria_top_price_value != '') && (parseInt(products[articul][j].price_echo, 10) > parseInt(Filter_criteria_top_price_value, 10)))
                        || ((Filter_criteria_top_delivery_value != 0 && Filter_criteria_top_delivery_value != '') && (parseInt(products[articul][j].price_echo, 10) > parseInt(Filter_criteria_top_price_value, 10)))
                        ) {

                    flag_set_maximize_block = true;
                    insert_element.setAttribute('class', 'product' + b1.replace(/([^A-Za-z0-9])/g, '') + articul);
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
                temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + b1.replace(/([^A-Za-z0-9])/g, '') + articul + '">Показать остальные ' + b1 + '</a> <span>\\</span> ';
            temp.innerHTML = temp_text + '<a class="Filter_min_other_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '">Показать запчасти ' + b1 + ' с минимальным сроком поставки</a>';
            temp2.appendChild(temp);
            if (temp_rows_count > 1)
                elements.appendChild(temp1);


        }
        continue;
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
            temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + articul + '">Показать остальные ' + products[articul][0].brand + '</a> <span>\\</span> ';
        temp.innerHTML = temp_text + '<a class="Filter_min_other_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '">Показать запчасти ' + products[articul][j].brand + ' с минимальным сроком поставки</a>';
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
            temp.innerHTML = 'Аналогов не найдено';
            temp2.appendChild(temp);
            elements.appendChild(temp1);
        }
    } else {
//        elements.appendChild(elements2);
    }
//    document.getElementById(Filter_block_to_load).innerHTML = '';
//    document.getElementById(Filter_block_to_load).appendChild(elements);


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
//    new_element.setAttribute('class', 'Filter_items_articul Filter_items_articul_' + (Filter_temp_flag1 ? 'color1' : 'color2'));
    Filter_temp_flag1 = !Filter_temp_flag1;
    new_element.id = 'Filter_items_articul' + articul;

//    new_element_show_articul = document.createElement('div');
//    new_element_show_articul.setAttribute('class', 'Filter_items_articul_name');
//    new_element_show_articul.innerHTML = articul;
//    new_element.appendChild(new_element_show_articul);
    return new_element;
}

function Filter_save_criteria_to_delete() {
//    if (document.getElementById('Filter_criteria_div_rows').value == 0 || document.getElementById('Filter_criteria_div_rows').value == '')
//        Filter_criteria_total_rows_per_articul = 999;
//    else
//        Filter_criteria_total_rows_per_articul = parseInt(document.getElementById('Filter_criteria_div_rows').value, 10);
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
//    $(document).keypress(function(e) {
//        if ((e.which == 13)) {
//            //Filter_start_search();
//            return false;
//        }
//    });
//    $(document).keydown(function(e) {
//        if ((e.which == 13)) {
//            //Filter_start_search();
//            return false;
//        }
//    });
//    $(document).keyup(function(e) {
//        if ((e.which == 13)) {
//            //Filter_start_search();
//            return false;
//        }
    //    });
    $('#search-input-detailSearch ').keyup(function (e) {
        if ((e.which == 13)) {
            document.location = '/search?search_phrase=' + encodeURIComponent(document.getElementById(Filter_input_element).value);
//            Filter_start_search();
            return false;
        }
    });

});


function Filter_clearForm() {
    $('#search-input-detailSearch').val('');

//    return false;
}
function Filter_search_page(num) {
    $(document).ready(function () {
        document.getElementById(Filter_input_element).value = num;
        Filter_start_search();
    });

}
function Filter_start_search_location() {
    document.location = '/search?search_phrase=' + encodeURIComponent(document.getElementById(Filter_input_element).value);
}
function Filter_search_more_find() {
    $("#getPriceUrlId").attr("href", $("#getPriceUrlId").attr("href") + '?detail=' + document.getElementById(Filter_input_element).value);
    return true;
}