<?php
class Shop_cartModule extends CWebModule {
    public $_images;
    public $_css;
    public $perPage = 20;
    public $delivery_model;
    public $payment_model;

    public function init() {
        $this->setImport(array(
            'shop_cart.models.*',
            'shop_cart.components.*',
        ));

        $assetsDir = dirname(__FILE__) . "/assets";
        $this->_images = Yii::app()->assetManager->publish($assetsDir);
        $this->_css = $this->_images . '/css';
        $this->_images .= '/images';
        Yii::app()->clientScript->registerCssFile($this->_css . '/shop.css');
        $this->delivery_model = new DeliveryMethods;
        $this->payment_model = new PaymentMethods;
//        Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish($assetsDir . "/js/shop_main.js"));
        Yii::app()->clientScript->registerScriptFile(Yii::app()->createUrl('/shop_cart/orders/getMainJs'));
        $url = Yii::app()->createUrl('/shop_cart/adminItems/save');
        $user_url = Yii::app()->createUrl('/shop_cart/items/save');

        $csrfName = Yii::app()->request->csrfTokenName;
        $csrfToken = Yii::app()->request->csrfToken;
        $text_change_good = Yii::t('shop_cart', 'Change of goods');
        $text_error = Yii::t('shop_cart', 'An error occurred.');
        /*$script = <<<SCRIPT
var ShopCartCSRF={ $csrfName:'$csrfToken' };
function ShopCartSaveItem(id) {
    $.ajax({
        type: "POST",
        url: '$url',
        cache: false,
        data:
                {
                    id: id,
                    status: $('#status_' + id).val(),
                    supplier: $('#supplier_' + id).val(),
                    delivery: $('#delivery_' + id).val(),
                    quantum: $('#quantum_' + id).val(),
                    price: $('#price_' + id).val(),
                    article: $('#article_' + id).val(),
                    name: $('#name_' + id).val(),
                    brand: $('#brand_' + id).val(),
                    $csrfName: '$csrfToken',
                },
        dataType: "json",
        timeout: 5000,
        success: function(data) {
            ShowWindow('$text_change_good', data.msg);
                $.fn.yiiGridView.update("orders-grid"); 
        },
        error: function() {
            alert('$text_error');
        }
    });
}

function ShopCartUserSaveItem(id) {
    $.ajax({
        type: "POST",
        url: '$user_url',
        cache: false,
        data:
                {
                    id: id,
                    status: $('#status_' + id).val(),
                    supplier: $('#supplier_' + id).val(),
                    delivery: $('#delivery_' + id).val(),
                    quantum: $('#quantum_' + id).val(),
                    price: $('#price_' + id).val(),
                    article: $('#article_' + id).val(),
                    name: $('#name_' + id).val(),
                    brand: $('#brand_' + id).val(),
                    $csrfName: '$csrfToken',
                },
        dataType: "json",
        timeout: 5000,
        success: function(data) {
            ShowWindow('$text_change_good', data.msg);
                $.fn.yiiGridView.update("orders-grid"); 
        },
        error: function() {
            alert('$text_error');
        }
    });
}
SCRIPT;
		Yii::app()->clientScript->registerScript(__CLASS__ . "#detailSearch", $script, CClientScript::POS_END);*/
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
    }

    /**
     * 
     * @return type
     */
    public function getStartForm() {
        return '<form action="'.Yii::app()->createAbsoluteUrl('/shop_cart/shoppingCart/create').'" target="_footer_iframe" method="post">'.
               '<div style="display:none"><input type="hidden" value="' . Yii::app()->request->csrfToken . '" name="' . Yii::app()->request->csrfTokenName . '"></div>'.
               '<div style="clear: both;"></div>';
    }

    /**
     * 
     * @param type $value
     * @return type
     */
    public function getForm($value) {
        return $this->getStartForm() . $this->getBlockForm($value) . $this->getEndForm();
    }

    /**
     * 
     * @param type $value
     * @return type
     */
    public function getBlockForm($value) {

        return CHtml::hiddenField('brand', $value['brand']) .
                CHtml::hiddenField('article', $value['article']) .
                CHtml::hiddenField('price', $value['price']) .
                CHtml::hiddenField('price_echo', $value['price_echo']) .
                CHtml::hiddenField('description', '') .
                CHtml::hiddenField('article_order', $value['article_order']) .
                CHtml::hiddenField('supplier_inn', $value['supplier_inn']) .
                CHtml::hiddenField('supplier', $value['supplier']) .
                CHtml::hiddenField('go_link', isset($value['go_link']) ? $value['go_link'] : '') .
                CHtml::hiddenField('store', $value['store']) .
                CHtml::hiddenField('name', $value['name']) .
                CHtml::hiddenField('delivery', $value['delivery']) .
                CHtml::hiddenField('quantum_all', $value['quantum_all']) .
                CHtml::hiddenField('price_data_id', $value['price_data_id']) .
                CHtml::hiddenField('store_count_state', $value['store_count_state']) .
                CHtml::hiddenField('weight', $value['weight']) .
                CHtml::textField('quantum', 1, array('class' => 'form_textfld', 'min' => '1', 'max' => $value['quantum_all'])) .
                CHtml::submitButton('', array('class' => 'cart-js-btn-add-cart', 'style' => 'width: 38px; height: 29px; border:none; margin: 0; '));
    }

    /**
     * 
     * @return string
     */
    public function getEndForm() {
        return '</form>';
    }

    /**
     * 
     * @param type $controller
     * @param type $action
     * @return boolean
     */
    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }

    /**
     * 
     * @param type $type
     * @return type
     */
    public function getCartBlock($type = 'big') {
        if ($type == 'big')
            return '<div id="shopping-cart">' . $this->getCartUpdateBlock() . '</div>';
        else
            return '<div id="shopping-cart-small">' . $this->getCartSmallUpdateBlock() . '</div>';
    }

    /**
     * 
     * @return string
     */
    public function getCartSmallUpdateBlock() {
        $count = $this->getCountTotal();
        if ($count > 0)
            return '' .
                    CHtml::link(CHtml::image($this->_images . '/cart.png'), array('/shop_cart/shoppingCart/view')) .
                    '';
        else
            return '';
    }

    /**
     * 
     * @return string
     */
    public function getCartUpdateBlock() {
        $count = $this->getCountTotal();

        return '<div style="height:30px;margin-bottom:10px; float:left">
                ' . CHtml::link('<img src="/images/theme/item14.png">' . Yii::t('shop_cart', 'Basket'), array('/shop_cart/shoppingCart/view'), array('id' => 'cart', 'class' => 'btn btn-warning')) . '
                          
                            <div class="cart"><span>' .
                $count . ' ' . Yii::t('shop_cart', 'goods') . ', ' . $this->getPriceTotal() . '</span></div>
                        </div>';
    }
    
    /**
     * 
     * @return type
     */
    public function getCartContent() {
    	$cart = Yii::app()->user->getState('cart');
    	
    	if (is_string($cart))
    		$aCart = CJSON::decode($cart, true);
    	else
    		$aCart = $cart;
    	 
    	$prs = array();
    	 
    	if (is_array($aCart) && count($aCart))
    		foreach ($aCart as $c) {
    		if (!in_array($c['product_id'], $prs)) {
    			$prs[] = $c['product_id'];
    		}
    	}
    	 
    	//Если в сессии ничего не хранится, проверяем в базе
    	if (!Yii::app()->user->isGuest) {
    		$ps = ShopProducts::model()->findAllByAttributes(array('uid' => Yii::app()->user->id));
    	
    		$count = count($ps);
    		for ($i = 0; $i < $count; $i ++) {
    			if (!in_array($ps[$i]->product_id, $prs)) {
    				$aCart[] = array('product_id' => $ps[$i]->product_id);
    			}
    		}
    	
    		if (count($aCart)) {
    			$this->setCartContent($aCart);
    			$cart = Yii::app()->user->getState('cart');
    		}
    	}
    	 
    	if (is_string($cart))
    		$aCart = CJSON::decode($cart, true);
    	else
    		$aCart = $cart;
    	
    	//Проверка на наличие товаров в базе, иначе удаляем их из сессии
    	if (count($aCart)) {
    		foreach ($aCart as $key => $product) {
    			$model = ShopProducts::model()->findByPk($product['product_id']);
    			if (!is_object($model)) {
    				unset($aCart[$key]);
    			}
    		}
    		 
    		$aCart = array_values($aCart);
    		 
    		$this->setCartContent($aCart);
    	}
    	
    	return $aCart;
    }

    /**
     * 
     * @param type $cart
     * @return type
     */
    public function setCartContent($cart) {
        return Yii::app()->user->setState('cart', CJSON::encode($cart));
    }

    /**
     * 
     * @return type
     */
    public function getCountTotal() {
        return count($this->getCartContent());
    }

    /**
     * 
     * @param type $delivery_price
     * @return type
     */
    public function getPriceTotal($delivery_price = 0) {
        $price_total = 0;
        if (count($this->getCartContent()) > 0)
            foreach ($this->getCartContent() as $product) {
                $model = ShopProducts::model()->findByPk($product['product_id']);
                $price_total += $model->price * $model->quantum;
            }
        return $this->getPriceFormatFunction($price_total + $delivery_price);
    }

    /**
     * 
     * @return type
     */
    public function getWeightTotal() {
        $weight_total = 0;
        if (count($this->getCartContent()) > 0)
            foreach ($this->getCartContent() as $product) {
                $model = ShopProducts::model()->findByPk($product['product_id']);
                $weight_total += $model->weight;
            }
        return $weight_total;
    }

    /**
     * 
     */
    public function clearCart() {
    	//Delete all records from shop_products
    	$cartContent = $this->getCartContent();
    	if (is_array($cartContent) && ($count = count($cartContent))) {
    		for ($i = 0; $i < $count; $i ++) {
    			if (array_key_exists('product_id', $cartContent[$i])) ShopProducts::model()->deleteByPk(intval($cartContent[$i]['product_id']));
    		}
    	}
    	
        $this->setCartContent(array());
    }

    /**
     * 
     * @param type $price
     * @return type
     */
    public function getPriceFormatFunction($price) {
        return Yii::app()->getModule('currencies')->getFormatPrice($price);
    }
	
    //Recalculate cart prices for price group of user
    public function recalculateCart() {
    	if (!Yii::app()->user->isGuest) {
    		$uid = Yii::app()->user->id;

    		$pg_user = Yii::app()->getModule('pricegroups')->getUserGroup();
    		
    		//If products exist in session
    		$cart = Yii::app()->user->getState('cart');
    		$aCart = $cart;
    		if (is_string($aCart))
    			$aCart = CJSON::decode($aCart, true);
    		
    		$prs = array();
    		
    		if (is_array($aCart) && count($aCart))
    			foreach ($aCart as $c) {
	    			if (!in_array($c['product_id'], $prs)) {
	    				$prs[] = $c['product_id'];
	    			}
	    		}
    		
    		if (count($prs)) {
	    		$products = ShopProducts::model()->findAll(array('condition' => 'product_id IN('.implode(', ', $prs).')'));
	    			
	    		$count = count($products);
	    			
	    		for ($i = 0; $i < $count; $i ++) {
	    			//Which price politic in prices corresponds user price group
	    			$price_group = 0;
	    			
	    			if ($products[$i]->price_data_id) {
		    			$price_data = PricesData::model()->findByPk($products[$i]->price_data_id);
		    			if (is_object($price_data)) {
		    				$price = Prices::model()->findByPk($price_data->price_id);
		
		    				if (is_object($price)) {
		    					$field = 'price_group_'.$pg_user;
		    					$price_group = $price->{$field};
		    				}
		    			}
	    			}
	    			
	    			if ($price_group || ($products[$i]->price_data_id == 0)) {
	    				$action = false;
	    				
	    				if ($products[$i]->price_data_id != 0) {
		    				//If user had this position
		    				$item = ShopProducts::model()->findByAttributes(array('price_data_id' => $products[$i]->price_data_id, 'uid' => $uid));
		    				if (is_object($item)) {
		    					$item->quantum = $item->quantum + $products[$i]->quantum;
		    					$item->save();
		
		    					$products[$i]->delete();
		    					
		    					$action = true;
		    				}
	    				}

	    				if ($action == false) {
	    					if ($price_group != $products[$i]->price_group) {
	    						$value = array('price'             => $products[$i]->supplier_price,
	    									   'price_currency'    => $products[$i]->currency,
	    									   'price_price_group' => $price_group,
	    									   'brand'             => $products[$i]->brand);
	
	    						$price = Yii::app()->getModule('prices')->getPriceFunction($value);
	    						$price_echo = Yii::app()->getModule('prices')->getPriceFormatFunction($price);
	
	    						ShopProducts::model()->updateByPk($products[$i]->primaryKey, array('price' => $price, 'price_echo' => $price_echo, 'price_group' => $price_group, 'uid' => $uid));
	    					} else {
	    						ShopProducts::model()->updateByPk($products[$i]->primaryKey, array('uid' => Yii::app()->user->id));
	    					}
	    				}
	    			} else {
	    				//Price or position was deleted
	    				$products[$i]->delete();
	    			}
	    		}
    		}
    	}
    }

    public function getCreditInfo($order) {
        $wpc = WebPaymentsCredit::model()->find(array('condition' => 'order_id = '.$order->primaryKey, 'order' => 'id DESC'));

        if (is_object($wpc)) {
            if (!$wpc->finish_date)
                return '<div class="credit_'.$order->primaryKey.'">'.
                       '<button class="btn" onclick="credit_accepted('.$order->primaryKey.');">'.Yii::t('webPayments', 'Request is accepted').'</button><br><br>'.
                       '<button class="btn" onclick="credit_denied('.$order->primaryKey.');" style="width: 150px;">'.Yii::t('webPayments', 'Request has been denied').'</button>'.
                       '</div>';
            else if ($wpc->result == 1)
                return Yii::t('webPayments', 'Request is accepted');
            else
                return Yii::t('webPayments', 'Request has been denied');
        }
    }
}