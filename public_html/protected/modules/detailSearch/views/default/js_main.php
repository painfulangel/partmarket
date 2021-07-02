<?php
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
		$sklat_post_title = 'Склад';
        $sklat_post_value = 'data.store';              
?>
    		var Filter_search_timelimit='<?php echo $timelimit ?>';
            var Filter_input_element = 'search-input-detailSearch';
            var Filter_get_sklad_url = '<?php echo $getSkladListUrl ?>';
            var Filter_get_data_prefix_url = '<?php echo $getProductListUrl ?>';
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
                
            var Filter_criteria_total_rows_per_articul=1;
            var Filter_criteria_top_price_value=0;
            var Filter_criteria_top_delivery_value=0;
                
            function Filter_before_search() {
                if ($('#' + Filter_block_to_load).length == 0) {
                    $('#content').html('<h1 class="Filter_h1"><?php echo Yii::t('detailSearch', 'Result on demand:')?><span id="search_phrase_echo"></span> </h1>' + 
                    '<div class="clear"> <div id="Filter_done_div"></div> ' +
'<div id="Search-grid" class="search_main_table grid-view"><table class="items table"><thead>' + 
'<tr>' + 
'<th style="text-align: center;color: #0088cc;"><?php echo Yii::t('detailSearch', 'Number') ?></th>' + 
'<th style="text-align: center;" ><a name="brand"><?php echo Yii::t('detailSearch', 'Manufacturer') ?><span class="caret" style="margin-right: -15px;"></span></a></th>' + 
'<th style="text-align: center;"><a name="name"><?php echo Yii::t('detailSearch', 'Name') ?><span class="caret"></span></a></th>' + 
'<th style="text-align: center;" ><a name="price"><?php echo Yii::t('detailSearch', 'Price') ?> <span style="margin-right: -10px;" class="caret"></span></a></th>' + 
'<th style="text-align: center;"><a name="dostavka"><?php echo Yii::t('detailSearch', 'Delivery time (days)') ?><span class="caret"></span></a></th>' + 
'<th style="text-align: center;" ><a name="kolichestvo"><?php echo Yii::t('detailSearch', 'Number (in storage)') ?><span class="caret" style="margin-right: -15px;"></span></a></th>' + 
'<th style="text-align: center;color: #0088cc;"><?php echo Yii::t('detailSearch', 'Storage') ?></th>' + 
'<th style="text-align: center;color: #0088cc;"><?php echo Yii::t('detailSearch', 'Number (in order)') ?></th>' + 
'</tr>' + 
'</thead><tbody id="main_load_block">' + 
'</tbody></table>' + 
'<center style="margin-bottom: 40px;"><div><a onclick="Filter_search_more_find()" class="btn btn-warning" id="getPriceUrlId" href="<?php echo $getPriceUrl ?>"><?php echo Yii::t('detailSearch', 'Not found?<br>Get availability and price') ?></a></div></center></div>');
                    $("#Search-grid thead a").click(function() {
                		if($(this).hasClass('asc')){
                			$("#Search-grid thead a").removeClass('asc');
							$("#Search-grid thead a").removeClass('desc');
                    		$(this).addClass('desc');
                        	Filter_aply_cort_criteria($(this).attr("name"),'<');
                    	} else {
                    		$("#Search-grid thead a").removeClass('asc');
                			$("#Search-grid thead a").removeClass('desc');
                  			$(this).addClass('asc');
                        	Filter_aply_cort_criteria($(this).attr("name"),'>');
                    	}
                 	}); 
                }
            }

            function Filter_strat_show_proress() {
                document.getElementById('Filter_done_div').innerHTML = '<img src="<?php echo Yii::app()->getModule('detailSearch')->images ?>/loading.gif" ><div class="Filter_done_text"><?php echo Yii::t('detailSearch', 'Please wait, search') ?></div>';
            }
            
            function Filter_get_rating(data) {
            	var rating = '';
            	
            	if ((typeof(data.ddpercent) != 'undefined') && !isNaN(parseInt(data.ddpercent)) && (data.ddpercent != 0)) {
					var title = '';
					
					switch (parseInt(data.ddpercent)) {
						case 1:
							title = "<?php echo Yii::t('detailSearchNew', 'Possibly won\'t bring, 1 point'); ?>";
						break;
						case 2:
							title = "<?php echo Yii::t('detailSearchNew', 'Low delivery probability, 2 points'); ?>";
						break;
						case 3:
							title = "<?php echo Yii::t('detailSearchNew', 'Satisfactory delivery probability, 3 points'); ?>";
						break;
						case 4:
							title = "<?php echo Yii::t('detailSearchNew', 'Good delivery probability, 4 points'); ?>";
						break;
						case 5:
							title = "<?php echo Yii::t('detailSearchNew', 'Excellent delivery probability, 5 points'); ?>";
						break;
					}
					
					rating = '<div class="rateit" data-rateit-value="' + data.ddpercent + '" data-rateit-ispreset="true" data-rateit-readonly="true" title="' + title + '"></div>';
				}
				
				return rating;
            }
            
            function Filter_create_element(data, i,set_rows_assign) {
                temp_element = document.createElement('tr');
                Filter_temp_flag2 = !Filter_temp_flag2;

                if(set_rows_assign>0) {
                    temp = document.createElement('td');
                    temp.setAttribute('rowspan', set_rows_assign);
                    temp.setAttribute('product_id_main_row', data.brand.toUpperCase().replace(/([^A-Za-z0-9])/g, '')+data.articul);
                    temp.innerHTML = data.articul+(data.garanty==1?" <img src='/images/theme/cross.png' title='<?php echo Yii::t('detailSearch', 'The analog checked by administration to number') ?>' />":"");
                    temp.setAttribute('style', 'text-align: center; vertical-align: middle;');
                    temp_element.appendChild(temp);
                }
                
                temp = document.createElement('td');
                
                if (typeof(data.brand_link) != 'undefined' && data.brand_link == 1) {
                    temp.innerHTML = '<a class="fancybox fancybox.iframe" href="/brand/' + data.brand + '/">' + data.brand + '</a>';
                } else {
                    temp.innerHTML = data.brand;
                }
                    
                temp.setAttribute('style', 'text-align: center; vertical-align: middle;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
                temp_text = data.name;
                temp_text = temp_text.replace("/([\,\.\!\?\/\\])/g", "$1 ");
                temp.innerHTML = temp_text;
                temp.setAttribute('style', 'text-align: center; vertical-align: middle;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
                temp.innerHTML = '<b>'+data.price_echo+'</b>';
                temp.setAttribute('style', 'text-align: center; vertical-align: middle;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
                temp.innerHTML = data.dostavka;
                temp.setAttribute('style', 'text-align: center; vertical-align: middle;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
                temp.innerHTML = data.kolichestvo;
                temp.setAttribute('style', 'text-align: center; vertical-align: middle;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
                temp.innerHTML = Filter_get_rating(data);
                
                if ((typeof(data.store_description) != 'undefined') && ($.trim(data.store_description) != '')) {
					temp.innerHTML += '<div><a href="#" onclick="iFrameShowWindow(\'' + <?php echo $sklat_post_value ?> + '\', \'' + data.store_description + '\', false); return false;">' + <?php echo $sklat_post_value ?> + '</a></div>';
				} else {
                	temp.innerHTML += '<div>' + <?php echo $sklat_post_value ?> + '</div>';
                }
                
                temp.setAttribute('style', 'text-align: center; vertical-align: middle;');
                temp_element.appendChild(temp);

                temp = document.createElement('td');
                temp.setAttribute('style', 'text-align: center; min-width: 80px; vertical-align: middle;');
                temp_indf = data.articul + '_' + i;
                temp_var = data.name;
                temp_var = temp_var.replace(/"/g, '&quot;');

                temp.innerHTML = '<?php echo $form_begin ?>'+
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
                        
		                (typeof(data.store_id) != 'undefined' ?  '<input type="hidden" name="store_id" value="' + data.store_id + '" />' : '') +
		                (typeof(data.parser_id) != 'undefined' ? '<input type="hidden" name="parser_id" value="' + data.parser_id + '" />' : '') +
		                
                        '<input type="hidden" name="store" value="' + data.store + '" />' +
                        '<input type="hidden" name="name" value="' + data.name + '" />' +
                        '<input type="hidden" name="delivery" value="' + data.dostavka + '" />' +
                        '<input type="hidden" name="quantum_all" value="' + data.kolichestvo + '" />' +
                        '<input type="hidden" name="price_data_id" value="' + data.price_data_id + '" />' +
                        '<input type="hidden" name="store_count_state" value="' + data.store_count_state + '" />' +
                        '<input  style="width: 20px;" class="textfld" type="text" value="1" name="quantum" min="1" max="' + data.kolichestvo + '" id="quantum"/>' +
                        '<input type="hidden" name="weight" value="' + data.weight + '" />' +
                        
                        '<input class="js-btn-add-cart"  onclick="if('+data.kolichestvo+'==\'<?php echo Yii::t('detailSearch', 'Available') ?>\'||(this.form.quantum.value<='+data.kolichestvo+'&&'+data.kolichestvo+'>0)){return true;}else {alert(\'<?php echo Yii::t('detailSearch', 'Unavailable.') ?>\');return false;}"  style="width: 38px; height: 29px; border:none; margin: 0;top: 10px; margin-top: -10px;" type="submit" name="yt0" value=""/>' +
                        '<?php echo $form_end ?>';
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
		//alert(getProps(Filter_products));
    }
	//alert(Filter_products);
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
        document.getElementById('Filter_done_div').innerHTML = '<div class="Filter_done_text"><?php echo Yii::t('detailSearch', 'Search is finished') ?></div>';
        total = 0;
        console.log(total);
        for (var articul in Filter_products)
            for (var j in Filter_products[articul].elements)
                total++;
        for (var articul in Filter_products_other)
            for (var j in Filter_products_other[articul].elements)
                total++;

        console.log(total);

        if (total == 0) {
            Filter_search_more_find();
            document.location = $("#getPriceUrlId").attr("href");
        }
			        
		$('div.rateit').rateit();

        $('a.fancybox').click(function() {
            var href = $(this).attr('href');

            $.fancybox.open({
                href : href,
                type : "iframe",
                padding : 15,
                width: 600,
                height: 400,
                maxHeight: 400
            });
                
            return false;
        });
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
    elements = document.getElementById(Filter_block_to_load);
    elements2 = document.createElement('div');
    elements2.setAttribute('class', 'Filter_group_items');
    temp1 = document.createElement('tr');
    temp2 = document.createElement('td');
    temp2.setAttribute('colspan', '8');
    temp1.appendChild(temp2);

    temp = document.createElement('center');
    temp.setAttribute('class', 'Filter_detail_name');
    temp.innerHTML = '<?php echo Yii::t('detailSearch', 'Desired article') ?>';
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
                temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + b1.replace(/([^A-Za-z0-9])/g, '') + articul + '"><?php echo Yii::t('detailSearch', 'Show the rest') ?> ' + b1 + '</a> <span>\\</span> ';
            temp.innerHTML = temp_text + '<a class="Filter_min_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '"><?php echo Yii::t('detailSearch', 'Show spare parts') ?> ' + b1 + ' <?php echo Yii::t('detailSearch', 'Whith minimum delivery time') ?></a>';

            temp2.appendChild(temp);
            if (temp_rows_count > 1)
                elements.appendChild(temp1);
            elements.appendChild(artikul_element);
        }
        <?php /* ?>continue;
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
            temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + articul + '"><?php echo Yii::t('detailSearch', 'Show the rest') ?> ' + products[articul][0].brand + '</a> <span>\\</span> ';
        temp.innerHTML = temp_text + '<a class="Filter_min_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '"><?php echo Yii::t('detailSearch', 'Show spare parts') ?> ' + products[articul][j].brand + ' <?php echo Yii::t('detailSearch', 'Whith minimum delivery time') ?></a>';

        temp2.appendChild(temp);
        if (temp_rows_count > 1)
            elements.appendChild(temp1);
        elements.appendChild(artikul_element);<?php */ ?>
    }
    
    if (!flag) {
        if (Filter_total_count <= 0) {
            temp1 = document.createElement('tr');
            temp2 = document.createElement('td');
            temp2.setAttribute('colspan', '8');
            temp1.appendChild(temp2);

            temp = document.createElement('center');
            //temp.setAttribute('class', 'Filter_detail_name');
            temp.innerHTML = '<?php echo Yii::t('detailSearch', 'Goods not found') ?>';
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
    temp.innerHTML = '<?php echo Yii::t('detailSearch', 'Analogues and replacement of other manufacturers') ?>';

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
                temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + b1.replace(/([^A-Za-z0-9])/g, '') + articul + '"><?php echo Yii::t('detailSearch', 'Show the rest') ?> ' + b1 + '</a> <span>\\</span> ';
            temp.innerHTML = temp_text + '<a class="Filter_min_other_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '"><?php echo Yii::t('detailSearch', 'Show spare parts') ?> ' + b1 + ' <?php echo Yii::t('detailSearch', 'Whith minimum delivery time') ?></a>';
            temp2.appendChild(temp);
            if (temp_rows_count > 1)
                elements.appendChild(temp1);


        }
        <?php /* ?>continue;
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
            temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + articul + '"><?php echo Yii::t('detailSearch', 'Show the rest') ?> ' + products[articul][0].brand + '</a> <span>\\</span> ';
        temp.innerHTML = temp_text + '<a class="Filter_min_other_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '"><?php echo Yii::t('detailSearch', 'Show spare parts') ?> ' + products[articul][j].brand + ' <?php echo Yii::t('detailSearch', 'Whith minimum delivery time') ?></a>';
        temp2.appendChild(temp);
        if (temp_rows_count > 1)
            elements.appendChild(temp1);
//        artikul_element.appendChild(artikul_elements);
//        elements.appendChild(artikul_element);<?php */ ?>
    }
    
    if (!flag) {
        if (Filter_total_count <= 0) {
            temp1 = document.createElement('tr');
            temp2 = document.createElement('td');
            temp2.setAttribute('colspan', '8');
            temp1.appendChild(temp2);

            temp = document.createElement('center');
            //temp.setAttribute('class', 'Filter_detail_name');
            temp.innerHTML = '<?php echo Yii::t('detailSearch', 'The analogy is not found') ?>';
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
            document.location = '/search?search_phrase=' + encodeURIComponent(document.getElementById(Filter_input_element).value);
            return false;
        }
    });

});

function Filter_clearForm() {
    $('#search-input-detailSearch').val('');
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