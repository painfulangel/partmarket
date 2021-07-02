<?php
        $getSkladListUrl = Yii::app()->createAbsoluteUrl('/detailSearchNew/default/getSkladList');
        $getBrandListUrl = Yii::app()->createAbsoluteUrl('/detailSearchNew/default/getBrandList');
        $getProductListUrl = Yii::app()->createAbsoluteUrl('/detailSearchNew/default/getProductList');
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
			var Filter_head_type = 1;
			var Filter_search_brand = '';
			var Filter_search_phrase = '';
			
    		var Filter_search_timelimit='<?php echo $timelimit ?>';
            var Filter_input_element = 'search-input-detailSearchNew';
            
            var Filter_get_brand_url = '<?php echo $getBrandListUrl; ?>';
            var Filter_get_sklad_url = '<?php echo $getSkladListUrl ?>';
            var Filter_get_data_prefix_url = '<?php echo $getProductListUrl ?>';
            
            var Filter_text_for_loading='';
            var Filter_block_to_load='main_load_block';
            var Filter_search_phrase_echo='search_phrase_echo';
            
            var Filter_brands = {};
            
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
            
            var Filter_best_prices = {};
            
            function Filter_before_search() {
            	if (Filter_head_type == 1) {
                	var h1 = '<?php echo Yii::t('detailSearchNew', 'Result on demand:')?><br><?php echo Yii::t('detailSearchNew', 'Articul')?> <span id="search_phrase_echo"></span> <?php echo Yii::t('detailSearchNew', 'Was found in catalogs')?> (<span id="search_phrase_count"></span>)';
                		
                	//Brands
                	var html = '<div class="clear"> <div id="Filter_done_div"></div> ' +
'<div id="Search-grid" class="search_main_table grid-view">' + 
'<table class="items table">' + 
'<thead>' + 
'<tr>' + 
'<th style="text-align: center;"><?php echo Yii::t('detailSearchNew', 'Manufacturer') ?></th>' + 
'<th style="text-align: center;"><?php echo Yii::t('detailSearchNew', 'Number') ?></th>' + 
'<th style="text-align: center;"><?php echo Yii::t('detailSearchNew', 'Name') ?></th>' + 
'<th>&nbsp;</th>' + 
'</tr>' + 
'</thead>' + 
'<tbody id="' + Filter_block_to_load + '">' + 
'</tbody></table>' + 
'<center style="margin-bottom: 40px;"><div class="" style=""> <a onclick="Filter_search_more_find()" class="btn btn-warning" id="getPriceUrlId" href="<?php echo $getPriceUrl ?>"><?php echo Yii::t('detailSearchNew', 'Not found?<br>Get availability and price') ?></a></div></center>' + 
'</div>';
                } else {
                	var h1 = '<?php echo Yii::t('detailSearchNew', 'Result on demand:')?> <span id="search_phrase_echo">' + Filter_search_phrase + '</span> ';
                		
                	//Detailes
                	var html = '<div class="clear"> <div id="Filter_done_div"></div> ' +
'<div id="Search-grid" class="search_main_table grid-view">' + 
'<table class="items table">' + 
'<thead>' + 
'<tr>' + 
'<th style="text-align: center;"><a name="brand"><?php echo Yii::t('detailSearchNew', 'Manufacturer') ?><span class="caret" style="margin-right: -15px;"></span></a></th>' + 
'<th style="text-align: center; color: #0088cc;"><?php echo Yii::t('detailSearchNew', 'Number') ?></th>' + 
'<th style="text-align: center;"><a name="name"><?php echo Yii::t('detailSearchNew', 'Name') ?><span class="caret"></span></a></th>' + 
'<th style="text-align: center;"><a name="price"><?php echo Yii::t('detailSearchNew', 'Price') ?> <span style="margin-right: -10px;" class="caret"></span></a></th>' + 
'<th style="text-align: center;"><a name="dostavka"><?php echo Yii::t('detailSearchNew', 'Delivery time (days)') ?><span class="caret"></span></a></th>' + 
'<th style="text-align: center;"><a name="kolichestvo"><?php echo Yii::t('detailSearchNew', 'Number (in storage)') ?><span class="caret" style="margin-right: -15px;"></span></a></th>' + 
'<th style="text-align: center; color: #0088cc;"><?php echo Yii::t('detailSearchNew', 'Storage') ?></th>' + 
'<th style="text-align: center; color: #0088cc;"><?php echo Yii::t('detailSearchNew', 'Number (in order)') ?></th>' + 
'</tr>' + 
'</thead>' + 
'<tbody id="' + Filter_block_to_load + '">' + 
'</tbody>'+ 
'</table>' + 
'<center style="margin-bottom: 40px;"><div> <a onclick="Filter_search_more_find()" class="btn btn-warning" id="getPriceUrlId" href="<?php echo $getPriceUrl ?>"><?php echo Yii::t('detailSearchNew', 'Not found?<br>Get availability and price') ?></a></div></center>' + 
'</div>';
                }
				
				$('#Filter_results').html(html);
                Filter_start_show_progress();
                
                $('h1.Filter_h1').html(h1);
	                
	            if (Filter_head_type == 2) {
	                $("#Search-grid thead a").click(function(){
		                if($(this).hasClass('asc')){
		                	$("#Search-grid thead a").removeClass('asc');
		                	$("#Search-grid thead a").removeClass('desc');
		                    $(this).addClass('desc');
		                    Filter_apply_sort_criteria($(this).attr("name"),'<');
		                } else {
		                    $("#Search-grid thead a").removeClass('asc');
		                	$("#Search-grid thead a").removeClass('desc');
		                    $(this).addClass('asc');
		                    Filter_apply_sort_criteria($(this).attr("name"),'>');
		                 }
	                 });
	                 
	                 $('select[name=currency_list]').change(function() {
	                 	$.post('<?php echo Yii::app()->createUrl('userControl/userProfile/changeCurrency'); ?>', { id_currency: $(this).val(), <?php echo Yii::app()->request->csrfTokenName; ?> : '<?php echo Yii::app()->request->csrfToken; ?>' }, function( data ) {
					      Filter_search_page(Filter_search_phrase, Filter_search_brand);
					      $("#shopping-cart").load("<?php echo Yii::app()->createAbsoluteUrl('/shop_cart/shoppingCart/cart'); ?>");
					    });
	                 });
	            }
            }

            function Filter_start_show_progress() {
                $('#Filter_done_div').html('<img src="<?php echo Yii::app()->getModule('detailSearchNew')->images ?>/loading.gif" ><div class="Filter_done_text"><?php echo Yii::t('detailSearchNew', 'Please wait, search') ?></div>');
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
				<?php //temp_element.setAttribute('class', 'Filter_items_element_' + (Filter_temp_flag2 ? 'color1' : 'color2'));
				//temp_element.setAttribute('valign', 'middle'); ?>
                Filter_temp_flag2 = !Filter_temp_flag2;

                if ((typeof(data.store_highlight) != 'undefined') && !isNaN(parseInt(data.store_highlight)) && (parseInt(data.store_highlight) == 1)) {
                	temp_element.setAttribute('class', 'Filter_items_element_highlight');
                }
                
                if(set_rows_assign>0) {
                	<?php //Brand ?>
	                temp = document.createElement('td');
					temp.setAttribute('class', 'Filter_items_brand');
	                temp.setAttribute('rowspan', set_rows_assign);
	                temp.setAttribute('brand_id_main_row', data.brand.toUpperCase().replace(/([^A-Za-z0-9])/g, '')+data.articul);
					
					temp2 = document.createElement('div');
	                
                    if (typeof(data.brand_link) != 'undefined' && data.brand_link == 1) {
                        temp2.innerHTML = '<a class="fancybox fancybox.iframe" href="/brand/' + data.brand + '/">' + data.brand + '</a>';
                    } else {
                        temp2.innerHTML = data.brand;
                    }
	                
                    temp.appendChild(temp2);
	                
	                temp_element.appendChild(temp);
                	<?php //Brand ?>
                
                	<?php //Name ?>
	                temp = document.createElement('td');
					temp.setAttribute('class', 'Filter_items_brand');
	                temp.setAttribute('rowspan', set_rows_assign);
	                temp.setAttribute('product_id_main_row', data.brand.toUpperCase().replace(/([^A-Za-z0-9])/g, '')+data.articul);
	                temp.innerHTML = data.articul+(data.garanty==1?" <img src='/images/theme/cross.png' title='<?php echo Yii::t('detailSearchNew', 'The analog checked by administration to number') ?>' />":"");
	                temp_element.appendChild(temp);
	                <?php //Name ?>
                }
                
                temp = document.createElement('td');
				//temp.setAttribute('class', 'Filter_items_name');
                temp_text = data.name;
                temp_text = temp_text.replace("/([\,\.\!\?\/\\])/g", "$1 ");
                temp.innerHTML = temp_text;
                temp_element.appendChild(temp);

                temp = document.createElement('td');
				//temp.setAttribute('class', 'Filter_items_price');
                temp.innerHTML = '<b>'+data.price_echo+'</b>';
                temp_element.appendChild(temp);

                temp = document.createElement('td');
				//temp.setAttribute('class', 'Filter_items_dostavka');
                temp.innerHTML = data.dostavka;
                temp_element.appendChild(temp);

                temp = document.createElement('td');
				//temp.setAttribute('class', 'Filter_items_kolichestvo');
                temp.innerHTML = data.kolichestvo;
                temp_element.appendChild(temp);

                temp = document.createElement('td');
				//temp.setAttribute('class', 'Filter_items_sklad');
				
				temp.innerHTML = Filter_get_rating(data);
				
				if ((typeof(data.store_description) != 'undefined') && ($.trim(data.store_description) != '')) {
					temp.innerHTML += '<div><a href="#" onclick="iFrameShowWindow(\'' + <?php echo $sklat_post_value ?> + '\', \'' + data.store_description + '\', false); return false;">' + <?php echo $sklat_post_value ?> + '</a></div>';
				} else {
                	temp.innerHTML += '<div>' + <?php echo $sklat_post_value ?> + '</div>';
                }
                
                temp_element.appendChild(temp);

                temp = document.createElement('td');
				
				temp.setAttribute('class', 'buy');
				
                temp_indf = data.articul + '_' + i;
                temp_var = data.name;
                temp_var = temp_var.replace(/"/g, '&quot;');

                temp.innerHTML = '<?php echo $form_begin ?>'+
                '<input type="hidden" name="brand" value="' + $.trim(data.brand) + '" />' +
                '<input type="hidden" name="article" value="' + data.articul + '" />' +
                '<input type="hidden" name="price_group_1" value="' + data.price_group_1 + '" />' +
                '<input type="hidden" name="price_group_2" value="' + data.price_group_2 + '" />' +
                '<input type="hidden" name="price_group_3" value="' + data.price_group_3 + '" />' +
                '<input type="hidden" name="price_group_4" value="' + data.price_group_4 + '" />' +
                '<input type="hidden" name="supplier_price" value="' + data.supplier_price + '" />' +
                
                '<input type="hidden" name="price_purchase" value="' + (typeof(data.price_purchase) != 'undefined' ? data.price_purchase : '') + '" />' +
                '<input type="hidden" name="price_purchase_echo" value="' + (typeof(data.price_purchase_echo) != 'undefined' ? data.price_purchase_echo : '') + '" />' +
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
                '<input class="js-btn-add-cart"  onclick="if('+data.kolichestvo+'==\'<?php echo Yii::t('detailSearchNew', 'Available') ?>\'||(this.form.quantum.value<='+data.kolichestvo+'&&'+data.kolichestvo+'>0)){return true;}else {alert(\'<?php echo Yii::t('detailSearchNew', 'Unavailable.') ?>\');return false;}"  style="width: 38px; height: 29px; border:none;" type="submit" name="yt0" value=""/>' +
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
				
			    d = new Date();
			    Filter_global_time_start = d.getTime();
			
			    $('#' + Filter_input_element).val(($('#' + Filter_input_element).val()).replace(/\-/g, '').replace(/\./g, '').toUpperCase());
			
			    $('#' + Filter_search_phrase_echo).html($('#' + Filter_input_element).val());
				
				if (Filter_search_brand == '') {
					params = {
				        time_start: Filter_global_time_start,
				        search_phrase: ($('#'+ Filter_input_element).val()).toUpperCase()
				    };
				    Filter_products = [];
				    Filter_products_other = [];
				    
				    $.getJSON(Filter_get_sklad_url, params, function (data) {
				        if (!Filter_check_is_good_time(data)) return;
				        Filter_total_count = data.sklads_count;
				        
			            $.ajaxSetup({
			                timeout: Filter_search_timelimit,
			                error: function () {
			                    Filter_total_count --;
			                    if (Filter_last_update_parser != data.search_sklad && Filter_total_count > 0) return;
			                    Filter_calculate_brands();
			                    Filter_done_search();
			            }});
			            
				        for (i = 0; i < data.sklads_count; i++) {
				            params = {
				                search_phrase: ($('#' + Filter_input_element).val()).toUpperCase(),
				                search_sklad: data.sklads[i],
				                time_start: Filter_global_time_start
				            };
				            
				            $.getJSON(Filter_get_brand_url, params, function (data) {
                                console.log('get brand');
                                console.log(data);
				            	Filter_total_count --;
        console.log(Filter_total_count);
				            	
								if (typeof(data.brands) != 'undefined') {
									//Show brands
								    var html = '';
								    var count = 0;
								    
								    brands = data.brands;
        console.log(brands);
								    for (key in brands) {
								    	clef = brands[key]['brand'] + '_' + brands[key]['article'];
								    	Filter_brands[clef] = brands[key];
								    }
								    
								    Filter_brands = ksort(Filter_brands);
        console.log(Filter_brands);
								    
								    for (key in Filter_brands) {
								    	count ++;
								    	
								    	html += '<tr>' + 
								    			'<td class="brand">' + Filter_brands[key]['brand'] + '</td>' +
								    			'<td class="articul">' + Filter_brands[key]['article'] + '</td>' +
								    			'<td class="name">' + Filter_brands[key]['name'] + '</td>' +
								    			'<td class="link"><a href="/artbrand/' + encodeURIComponent(Filter_brands[key]['brand_link']) + '/' + Filter_brands[key]['article'] + '"><?php echo Yii::t('detailSearchNew', 'Search'); ?></a></td>' +
								    			'</tr>';
								    }
								    
								    $('#' + Filter_block_to_load).html(html);
								    $('#search_phrase_count').html(count);
							    }
							    
							    var total = 0;
							    for (key in Filter_brands) {
							    	total ++;
							    }
							    
							    if (Filter_total_count == 0) {
							    	if (total < 2) {
							    		Filter_head_type = 2;
			    						Filter_before_search();
							    		Filter_show_products();
							    	} else {
							    		$('#Filter_done_div').html('');
							    	}
							    }
							});
				        }
				    });
				} else {
				    Filter_show_products();
			    }
			}
			
			function Filter_show_products() {
				params = {
			        time_start: Filter_global_time_start,
			        search_phrase: ($('#'+ Filter_input_element).val()).toUpperCase()
			    };
			    Filter_products = [];
			    Filter_products_other = [];
			    <?php /* ?>
			    url = '/art/' + encodeURIComponent(($('#' + Filter_input_element).val()).toUpperCase());
			    if (url != window.location) {
			        window.history.pushState(null, null, url);
			    }
			    <?php */ ?>
			    $.getJSON(Filter_get_sklad_url, params, function (data) {
			        if (!Filter_check_is_good_time(data)) return;
			        Filter_total_count = data.sklads_count;
			        
		            $.ajaxSetup({
		                timeout: Filter_search_timelimit,
		                error: function () {
		                    Filter_total_count --;
		                    if (Filter_last_update_parser != data.search_sklad && Filter_total_count > 0) return;
		                    Filter_calculate_brands();
		                    Filter_done_search();
		            }});
			            
			        for (i = 0; i < data.sklads_count; i++) {
			            params = {
			                search_phrase: ($('#' + Filter_input_element).val()).toUpperCase(),
			                search_brand: Filter_search_brand,
			                search_sklad: data.sklads[i],
			                time_start: Filter_global_time_start
			            };
			            <?php //console.log(data.sklads[i]); ?>
			            $.getJSON(Filter_get_data_prefix_url, params, function (data) {
			                Filter_upgrade_elements(data);
			            });
			        }
			    });
			}

			function getProps(toObj, tcSplit) {
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
			    
			    for (brand in data.best_prices) {
			    	if (typeof(Filter_best_prices[brand]) != 'undefined') {
			    		price1 = parseFloat(data.best_prices[brand].price);
			    		price2 = parseFloat(Filter_best_prices[brand].price);
			    		
			    		if (price1 < price2) {
			    			Filter_best_prices[brand] = data.best_prices[brand];
			    		}
			    	} else {
			    		Filter_best_prices[brand] = data.best_prices[brand];
			    	}
			    }
			    
			    if (!Filter_check_is_good_time(data))
			        return;
			    
			    Filter_total_count--;
			    
			    if (Filter_last_update_parser != data.search_sklad && Filter_total_count > 0)
			        return;
			    
			    Filter_calculate_brands();
			    
			    Filter_done_search();
			}

			function Filter_done_search() {
			    if (Filter_total_count <= 0) {
			    	$('#Filter_done_div').html('');
			        
			        //$('#Filter_done_div').html('<div class="Filter_done_text"><?php echo Yii::t('detailSearchNew', 'Search is finished') ?></div>');
			        total = 0;
        console.log(total);
					//products = Filter_sort_criteria();
			        for (var articul in Filter_products)
			            for (var j in Filter_products[articul].elements)
			                total++;
					//products = Filter_sort_criteria_other();
			        for (var articul in Filter_products_other)
			            for (var j in Filter_products_other[articul].elements)
			                total++;
			
        console.log(total);
			
			        if (total == 0) {
			            Filter_search_more_find();
        //Здесь происходит редирект
			            document.location = $("#getPriceUrlId").attr("href");
			        }
			        
			        $('div.rateit').rateit();

                    //$('a.fancybox').fancybox();
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

			function Filter_sort_criteria() {
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
			
			function Filter_sort_criteria_other() {
			    products = [];
			    if (Filter_criteria != 'brand') {
			        for (var articul in Filter_products_other) {
			            Filter_criteria_search = Filter_criteria;
			            if (Filter_products_other[articul].criteria != 'general') {
			                Filter_criteria_search = Filter_products_other[articul].criteria;
			            }
			            
			            //console.log(articul + ' - ' + Filter_products_other[articul].elements.length);
			            
			            total = 0;
			            products[articul] = [];
			            for (var j in Filter_products_other[articul].elements) {
			                if (total == 0) {
			                    products[articul][total] = Filter_products_other[articul].elements[j];
			                    total ++;
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
			    } else {
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

			function ksort(w) {
				var sArr = [], tArr = [], n = 0;
			
				for (i in w){
					tArr[n++] = i;
				}
			
				tArr = tArr.sort();
				n = tArr.length;
				for (var i=0; i < n; i++) {
					sArr[tArr[i]] = w[tArr[i]];
				}
				return sArr;
			}
			
			function Filter_calculate_brands() {
				products = Filter_sort_criteria();
			
			    var brands = {};
			    var brands_count = 0;
			    
			    for (var articul in products) {
			    	//Group by brands
			    	for (var j in products[articul]) {
			    		brand = $.trim(products[articul][j].brand);
			    		if (!(brand in brands)) {
			    			brands[brand] = {'articul': products[articul][j].articul, 'name': products[articul][j].name};
			    			
			    			brands_count ++;
			    		}
			    	}
			    }
			    
			    products = Filter_sort_criteria_other();
				
			    for (var articul in products) {
			    	//Group by brands
			    	for (var j in products[articul]) {
			    		brand = $.trim(products[articul][j].brand);
			    		if (!(brand in brands)) {
			    			brands[brand] = {'articul': products[articul][j].articul, 'name': products[articul][j].name};
			    			
			    			brands_count ++;
			    		}
			    	}
			    }
				
			    Filter_start_show_progress();
			    
			    if ((brands_count == 1) || (Filter_search_brand != '') || (Filter_head_type == 2)) {
			    	//Show detailes
			    	Filter_remake_table();
				} else {
					//Show brands
				    var html = '';
				    var count = 0;
				    
				    brands = ksort(brands);
				    for (key in brands) {
				    	count ++;
				    
				    	brand_link = key.replace(/\//g, '__');
				    
				    	html += '<tr>' + 
				    			'<td class="brand">' + key + '</td>' +
				    			'<td class="articul">' + brands[key]['articul'] + '</td>' +
				    			'<td class="name">' + brands[key]['name'] + '</td>' +
				    			'<td class="link"><a href="/artbrand/' + encodeURIComponent(brand_link) + '/' + Filter_search_phrase + '"><?php echo Yii::t('detailSearchNew', 'Search'); ?></a></td>' +
				    			'</tr>';
				    }
				    
				    $('#' + Filter_block_to_load).html(html);
				    $('#search_phrase_count').html(count);
			    }
			}
			
			function Filter_best_product(products, orig) {
				if ($('div.search_the_best').length == 1) {
				    var best = null;
				    var best_price = 1000000000;
				    var count = 0;
				    for (var articul in products) {
				    	for (var j in products[articul]) {
				    		if (products[articul][j].price < best_price) {
				    			best_price = products[articul][j].price;
				    			best = products[articul][j];
				    		}
				    		
				    		count ++;
				    	}
				    }
				    
				    if ((best != null) && (count > 1)) {
				    	var sign = '<?php echo Yii::t('detailSearchNew', 'Analog'); ?>';
				    	
				    	if ((typeof(orig) != 'undefined') && (orig == true)) {
				    		sign = '<?php echo Yii::t('detailSearchNew', 'Original'); ?>';
				    	}
				    
				    	var div_best = $('<div class="best-offer span4">' +
				    						'<div class="search-row"></div>' + 
				    						'<div class="search-row span12">' + 
					    						'<div class="offer-title span8"><?php echo Yii::t('detailSearchNew', 'The lowest price'); ?></div>' + 
					    						'<div class="sign span4">' + sign + '</div>' + 
				    						'</div>' + 
				    					 '</div>');
				    	
				    	var sign = '<?php echo Yii::t('detailSearchNew', 'Day3'); ?>';
				    	if (!isNaN(parseInt(best.dostavka))) {
				    		switch (parseInt(best.dostavka)) {
				    			case 1:
				    				sign = '<?php echo Yii::t('detailSearchNew', 'Day1'); ?>';
				    			break;
				    			case 2:
				    			case 3:
				    			case 4:
				    				sign = '<?php echo Yii::t('detailSearchNew', 'Day2'); ?>';
				    			break;
				    		}
				    	}
				    	
				    	div_best.append('<div class="search-row span12">' + 
					    					'<div class="brand span8">' + best.brand + ' ' + best.articul + '</div>' +
					    					'<div class="price span4">' + best.price_echo + '</div>' +
					    				'</div>' + 
				    					'<div class="search-row span12">' + 
					    					'<div class="name span8">' + best.name + '</div>' + 
					    					'<div class="store span4">' + Filter_get_rating(best) + '<div>' + best.store + '</div></div>' + 
					    				'</div>' + 
				    					'<div class="search-row span12">' + 
				    						'<div class="count span3">' + best.kolichestvo + (!isNaN(parseInt(best.kolichestvo)) ? ' <?php echo Yii::t('detailSearchNew', 'Pieces'); ?>' : '') + '</div>' + 
				    						'<div class="delivery span4">' + best.dostavka + ' ' + sign + '</div>' + 
				    						'<?php echo $form_begin ?>'+
							                '<input type="hidden" name="brand" value="' + $.trim(best.brand) + '" />' +
							                '<input type="hidden" name="article" value="' + best.articul + '" />' +
							                '<input type="hidden" name="price_group_1" value="' + best.price_group_1 + '" />' +
							                '<input type="hidden" name="price_group_2" value="' + best.price_group_2 + '" />' +
							                '<input type="hidden" name="price_group_3" value="' + best.price_group_3 + '" />' +
							                '<input type="hidden" name="price_group_4" value="' + best.price_group_4 + '" />' +
							                '<input type="hidden" name="supplier_price" value="' + best.supplier_price + '" />' +
							                
							                '<input type="hidden" name="price_purchase" value="' + (typeof(best.price_purchase) != 'undefined' ? best.price_purchase : '') + '" />' +
							                '<input type="hidden" name="price_purchase_echo" value="' + (typeof(best.price_purchase_echo) != 'undefined' ? best.price_purchase_echo : '') + '" />' +
							                '<input type="hidden" name="price" value="' + best.price + '" />' +
							                '<input type="hidden" name="price_echo" value="' + best.price_echo + '" />' +
							                
							                '<input type="hidden" name="description" value="' + best.description + '" />' +
							                '<input type="hidden" name="article_order" value="' + best.articul_order + '" />' +
							                '<input type="hidden" name="supplier_inn" value="' + best.supplier_inn + '" />' +
							                '<input type="hidden" name="supplier" value="' + best.supplier + '" />' +
							                '<input type="hidden" name="store" value="' + best.store + '" />' +
							                '<input type="hidden" name="name" value="' + best.name + '" />' +
							                '<input type="hidden" name="delivery" value="' + best.dostavka + '" />' +
							                '<input type="hidden" name="quantum_all" value="' + best.kolichestvo + '" />' +
							                '<input type="hidden" name="price_data_id" value="' + best.price_data_id + '" />' +
							                '<input type="hidden" name="store_count_state" value="' + best.store_count_state + '" />' +
							                '<input type="hidden" value="1" name="quantum" id="quantum"/>' +
							                '<input type="hidden" name="weight" value="' + best.weight + '" />' +
				    						'<input type="submit" class="btn" onclick="if('+best.kolichestvo+'==\'<?php echo Yii::t('detailSearchNew', 'Available') ?>\'||(this.form.quantum.value<='+best.kolichestvo+'&&'+best.kolichestvo+'>0)){return true;}else {alert(\'<?php echo Yii::t('detailSearchNew', 'Unavailable.') ?>\');return false;}" value="<?php echo Yii::t('detailSearchNew', 'In cart') ?>">' + 
							                '<?php echo $form_end ?>' +
				    					'</div>');
				    	
				    	div_best.find('form').addClass('span5');
				    	
				    	$('div.search_the_best').append(div_best);
				    }
			    }
			}
			
			function Filter_best_delivery_period(products, products_other) {
				if ($('div.search_the_best').length == 1) {
				    var best = null;
				    var best_delivery = 1000000000;
				    var count = 0;
				    for (var articul in products) {
				    	for (var j in products[articul]) {
				    		var dostavka = parseInt(products[articul][j].dostavka);
				    		
				    		if (!isNaN(dostavka) && (dostavka < best_delivery)) {
				    			best_delivery = products[articul][j].dostavka;
				    			best = products[articul][j];
				    		}
				    		
				    		count ++;
				    	}
				    }
				    
				    for (var articul in products_other) {
				    	for (var j in products_other[articul]) {
				    		var dostavka = parseInt(products_other[articul][j].dostavka);
				    		
				    		if (!isNaN(dostavka) && (dostavka < best_delivery)) {
				    			best_delivery = products_other[articul][j].dostavka;
				    			best = products_other[articul][j];
				    		}
				    		
				    		count ++;
				    	}
				    }
				    
				    if ((best != null) && (count > 1)) {
				    	var div_best = $('<div class="best-offer span4">' +
				    						'<div class="search-row"></div>' + 
				    						'<div class="search-row span12">' + 
					    						'<div class="offer-title span8"><?php echo Yii::t('detailSearchNew', 'The lowest delivery period'); ?></div>' + 
				    						'</div>' + 
				    					 '</div>');
				    	
				    	var sign = '<?php echo Yii::t('detailSearchNew', 'Day3'); ?>';
				    	if (!isNaN(parseInt(best.dostavka))) {
				    		switch (parseInt(best.dostavka)) {
				    			case 1:
				    				sign = '<?php echo Yii::t('detailSearchNew', 'Day1'); ?>';
				    			break;
				    			case 2:
				    			case 3:
				    			case 4:
				    				sign = '<?php echo Yii::t('detailSearchNew', 'Day2'); ?>';
				    			break;
				    		}
				    	}
				    	
				    	div_best.append('<div class="search-row span12">' + 
					    					'<div class="brand span8">' + best.brand + ' ' + best.articul + '</div>' +
					    					'<div class="price span4">' + best.price_echo + '</div>' +
					    				'</div>' + 
				    					'<div class="search-row span12">' + 
					    					'<div class="name span8">' + best.name + '</div>' + 
					    					'<div class="store span4">' + Filter_get_rating(best) + '<div>' + best.store + '</div></div>' + 
					    				'</div>' + 
				    					'<div class="search-row span12">' + 
				    						'<div class="count span3">' + best.kolichestvo + (!isNaN(parseInt(best.kolichestvo)) ? ' <?php echo Yii::t('detailSearchNew', 'Pieces'); ?>' : '') + '</div>' + 
				    						'<div class="delivery span4">' + best.dostavka + ' ' + sign + '</div>' + 
				    						'<?php echo $form_begin ?>'+
							                '<input type="hidden" name="brand" value="' + $.trim(best.brand) + '" />' +
							                '<input type="hidden" name="article" value="' + best.articul + '" />' +
							                '<input type="hidden" name="price_group_1" value="' + best.price_group_1 + '" />' +
							                '<input type="hidden" name="price_group_2" value="' + best.price_group_2 + '" />' +
							                '<input type="hidden" name="price_group_3" value="' + best.price_group_3 + '" />' +
							                '<input type="hidden" name="price_group_4" value="' + best.price_group_4 + '" />' +
							                '<input type="hidden" name="supplier_price" value="' + best.supplier_price + '" />' +
							                
							                '<input type="hidden" name="price_purchase" value="' + (typeof(best.price_purchase) != 'undefined' ? best.price_purchase : '') + '" />' +
							                '<input type="hidden" name="price_purchase_echo" value="' + (typeof(best.price_purchase_echo) != 'undefined' ? best.price_purchase_echo : '') + '" />' +
							                '<input type="hidden" name="price" value="' + best.price + '" />' +
							                '<input type="hidden" name="price_echo" value="' + best.price_echo + '" />' +
							                
							                '<input type="hidden" name="description" value="' + best.description + '" />' +
							                '<input type="hidden" name="article_order" value="' + best.articul_order + '" />' +
							                '<input type="hidden" name="supplier_inn" value="' + best.supplier_inn + '" />' +
							                '<input type="hidden" name="supplier" value="' + best.supplier + '" />' +
							                '<input type="hidden" name="store" value="' + best.store + '" />' +
							                '<input type="hidden" name="name" value="' + best.name + '" />' +
							                '<input type="hidden" name="delivery" value="' + best.dostavka + '" />' +
							                '<input type="hidden" name="quantum_all" value="' + best.kolichestvo + '" />' +
							                '<input type="hidden" name="price_data_id" value="' + best.price_data_id + '" />' +
							                '<input type="hidden" name="store_count_state" value="' + best.store_count_state + '" />' +
							                '<input type="hidden" value="1" name="quantum" id="quantum"/>' +
							                '<input type="hidden" name="weight" value="' + best.weight + '" />' +
				    						'<input type="submit" class="btn" onclick="if('+best.kolichestvo+'==\'<?php echo Yii::t('detailSearchNew', 'Available') ?>\'||(this.form.quantum.value<='+best.kolichestvo+'&&'+best.kolichestvo+'>0)){return true;}else {alert(\'<?php echo Yii::t('detailSearchNew', 'Unavailable.') ?>\');return false;}" value="<?php echo Yii::t('detailSearchNew', 'In cart') ?>">' + 
							                '<?php echo $form_end ?>' +
				    					'</div>');
				    	
				    	div_best.find('form').addClass('span5');
				    	
				    	$('div.search_the_best').append(div_best);
				    }
			    }
			}
			
			<?php /* ?>function Filter_best_brand_prices() {
				if ($('div.search_the_best').length == 1) {
					var show = false;
					
				    draft = {};
				    
				    for (brand in Filter_best_prices) {
				    	show = true;
				    	
				    	price = parseFloat(Filter_best_prices[brand].price) * 1000;
				    	
				    	while (true) {
				    		if (typeof(draft[price]) != 'undefined') {
				    			price ++;
				    		} else {
				    			break;
				    		}
				    	}
				    	
				    	draft[price] = Filter_best_prices[brand];
				    }
				    
				    if (show == true) {
					    Filter_best_prices = ksort(draft);
					    
					    var height = 190;
					    if ($('.best-offer:first').length) height = $('.best-offer:first').height();
					    
						var div_best = $('<div class="best-offer best-brands span4" style="height: ' + (height + 13) + 'px;">' +
											'<div class="best-offer-content span12" style="height: ' + (height - 10) + 'px;">' + 
											'<div class="search-row span12">' + 
						    					'<div class="brandh1 span8"><?php echo Yii::t('detailSearchNew', 'Manufacturer'); ?></div>' +
						    					'<div class="priceh1 span4"><?php echo Yii::t('detailSearchNew', 'Price'); ?></div>' +
						    				'</div>' +
						    				'</div>' + 
					    				 '</div>');
					    
						for (key in Filter_best_prices) {
							if ($.trim(Filter_best_prices[key].brand) == $.trim(Filter_search_brand)) continue;
						
							link = '/artbrand/' + encodeURIComponent($.trim(Filter_best_prices[key].brand)) + '/' + $.trim(Filter_search_phrase);
							
							div_best.find('.best-offer-content').append('<div class="search-row span12">' + 
						    					'<div class="brand span8"><a href="' + link + '">' + $.trim(Filter_best_prices[key].brand) + '</a></div>' +
						    					'<div class="price span4"><a href="' + link + '">' + $.trim(Filter_best_prices[key].price_echo) + '</a></div>' +
						    				'</div>');
						}
						
						div_best.append('<div class="span12"><div class="togme"></div></div>');
						
						$('div.search_the_best').append(div_best);
						
						$('div.best-offer div.togme').click(function() {
							if ($(this).hasClass('togme2')) {
							    var height = 190;
							    if ($('.best-offer:first').length) height = $('.best-offer:first').height();
					    
								$('div.best-brands').css('height', (height + 13) + 'px');
								$('div.best-offer-content').css('height', (height - 10) + 'px');
								
								jQuery.scrollTo('#wrap', 300);
							} else {
								$('div.best-brands').css('height', 'auto');
								$('div.best-offer-content').css('height', 'auto');
							}
						
							$(this).toggleClass('togme2');
						});
					}
				}
			}<?php */ ?>
			
			function Filter_remake_table() {
				if ($('#' + Filter_block_to_load).length == 0) return;
				
			    var products = Filter_sort_criteria();
			    
			    if ($('div.search_the_best').length == 1) $('div.search_the_best').html('');
			    
			    <?php //Show the best price ?>
			    Filter_best_product(products, true);
			    <?php //Show the best price ?>
			    
			    $('#' + Filter_block_to_load).html('');
			    
			    elements = document.getElementById(Filter_block_to_load);
			    elements2 = document.createElement('div');
			    elements2.setAttribute('class', 'Filter_group_items');
			    temp1 = document.createElement('tr');
			    temp2 = document.createElement('td');
			    temp2.setAttribute('colspan', '8');
			    temp1.appendChild(temp2);
			
			    temp = document.createElement('center');
			    temp.setAttribute('class', 'Filter_detail_name');
			    temp.innerHTML = '<?= Yii::t('detailSearchNew', 'Desired article') ?>';
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
			                    insert_element.setAttribute('class', insert_element.getAttribute('class') + ' product' + b1.replace(/([^A-Za-z0-9])/g, '') + articul);
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
			                temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + b1.replace(/([^A-Za-z0-9])/g, '') + articul + '"><?= Yii::t('detailSearchNew', 'Show the rest') ?> ' + b1 + '</a> <span>\\</span> ';
			            temp.innerHTML = temp_text + '<a class="Filter_min_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '"><?= Yii::t('detailSearchNew', 'Show spare parts') ?> ' + b1 + ' <?= Yii::t('detailSearchNew', 'Whith minimum delivery time') ?></a>';
			
			            temp2.appendChild(temp);
			            if (temp_rows_count > 1)
			                elements.appendChild(temp1);
			            elements.appendChild(artikul_element);
			        }
			    }
			    
			    if (!flag) {
			        if (Filter_total_count <= 0) {
			            temp1 = document.createElement('tr');
			            temp2 = document.createElement('td');
			            temp2.setAttribute('colspan', '8');
			            temp1.appendChild(temp2);
			
			            temp = document.createElement('center');
			            //temp.setAttribute('class', 'Filter_detail_name');
			            temp.innerHTML = '<?= Yii::t('detailSearchNew', 'Goods not found') ?>';
			            temp2.appendChild(temp);
			            elements.appendChild(temp1);
			        }
			    }
			
			    var products_other = Filter_sort_criteria_other();
			    
			    <?php //Show the best price ?>
			    Filter_best_product(products_other, false);
			    <?php //Show the best price ?>
			    
			    <?php //Show best delivery period ?>
			    Filter_best_delivery_period(products, products_other);
			    <?php //Show best delivery period ?>
			    
			    products = products_other;
			    
			    elements2 = document.createElement('div');
			    elements2.setAttribute('class', 'Filter_group_items');
			
			    temp1 = document.createElement('tr');
			    temp2 = document.createElement('td');
			    temp2.setAttribute('colspan', '8');
			    temp1.appendChild(temp2);
			
			    temp = document.createElement('center');
			    temp.setAttribute('class', 'Filter_detail_name');
			    temp.innerHTML = '<?= Yii::t('detailSearchNew', 'Analogues and replacement of other manufacturers') ?>';
			
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
			                    insert_element.setAttribute('class', insert_element.getAttribute('class') + ' product' + b1.replace(/([^A-Za-z0-9])/g, '') + articul);
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
			                temp_text = '<a class="Filter_show_hide_products filter_show_hide_buutons_detail_search" set_rows="' + set_rows_assign_save + '"  product_id="' + b1.replace(/([^A-Za-z0-9])/g, '') + articul + '"><?= Yii::t('detailSearchNew', 'Show the rest') ?> ' + b1 + '</a> <span>\\</span> ';
			            temp.innerHTML = temp_text + '<a class="Filter_min_other_show_hide_products filter_show_hide_buutons_detail_search" product_id="' + articul + '"><?= Yii::t('detailSearchNew', 'Show spare parts') ?> ' + b1 + ' <?= Yii::t('detailSearchNew', 'Whith minimum delivery time') ?></a>';
			            temp2.appendChild(temp);
			            if (temp_rows_count > 1)
			                elements.appendChild(temp1);
			        }
			    }
			    
			    if (!flag) {
			        if (Filter_total_count <= 0) {
			            temp1 = document.createElement('tr');
			            temp2 = document.createElement('td');
			            temp2.setAttribute('colspan', '8');
			            temp1.appendChild(temp2);
			
			            temp = document.createElement('center');
			            //temp.setAttribute('class', 'Filter_detail_name');
			            temp.innerHTML = '<?= Yii::t('detailSearchNew', 'The analogy is not found') ?>';
			            temp2.appendChild(temp);
			            elements.appendChild(temp1);
			        }
			    }
			    
			    $('.Filter_show_hide_products').click(function () {
			        $('.product' + $(this).attr('product_id')).show();
			        $('[brand_id_main_row="' + $(this).attr('product_id') + '"]').attr('rowspan', $(this).attr('set_rows'));
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
				//new_element.setAttribute('class', 'Filter_items_articul Filter_items_articul_' + (Filter_temp_flag1 ? 'color1' : 'color2'));
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
			
			function Filter_apply_sort_criteria(crit1, crit2) {
			    Filter_criteria = crit1;
			    Filter_criteria_updown = crit2;
			    
			    Filter_remake_table();
			}

			$(document).ready(function () {
			    $('#search-input-detailSearch ').keyup(function (e) {
			        if ((e.which == 13)) {
			            document.location = '/art/' + encodeURIComponent(document.getElementById(Filter_input_element).value);
						//Filter_start_search();
			            return false;
			        }
			    });
			
			});

			function Filter_clearForm() {
			    $('#search-input-detailSearch').val('');
			
				//return false;
			}
			
			function Filter_search_page(num, brand) {
				Filter_search_phrase = num;
				
				if (brand != '') {
					Filter_head_type = 2;
					Filter_search_brand = brand;
				}
				
		        $('#' + Filter_input_element).val(num);
		        Filter_start_search();
			}
			
			function Filter_start_search_location() {
                var val = $('#' + Filter_input_element).val();
                val = val.replace(/\//g, '__');
			    document.location = '/art/' + encodeURIComponent(val);
			}
			
			function Filter_search_more_find() {
			    $("#getPriceUrlId").attr("href", $("#getPriceUrlId").attr("href") + '?detail=' + document.getElementById(Filter_input_element).value);
			    return true;
			}