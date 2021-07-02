<?php
class ShoppingCartController extends Controller {
    public function actionCreate() {
        $text = '';
        if (!is_numeric($_POST['quantum']) || $_POST['quantum'] <= 0) {
            $text = Yii::t('shop_cart', 'Incorrect number');
        } else {
            if (isset($_POST['price'])) {
                $cart = Yii::app()->controller->module->getCartContent();
                $cart_id = -1;
                $select_ids = array(0);
                $model = new ShopProducts;
                $model->attributes = $_POST;
                
                if (!Yii::app()->user->isGuest) {
                	$model->uid = Yii::app()->user->id;
                }
                
                //Current user price group
                $model->price_group = Yii::app()->getModule('pricegroups')->getUserGroup();
                
                if (count($cart) > 0) {
                    foreach ($cart as $key => $value) {
                        $select_ids[] = " product_id='$value[product_id]' ";
                    }
                    $rows = Yii::app()->db->createCommand("SELECT * FROM `shop_products` WHERE " . implode(' OR ', $select_ids))->queryAll();
                    //print_r($rows);
                    foreach ($rows as $key => $value) {
                        if (
                                $value['article'] == $model->article &&
                                $value['supplier_inn'] == $model->supplier_inn &&
                                $value['supplier'] == $model->supplier &&
                                $value['price'] == $model->price
                        ) {
                        	if (!Yii::app()->user->isGuest && ($model->uid != $value['uid'])) {
                        		continue;
                        	}
                        	
                            $cart_id = $value['product_id'];
                            
                            break;
                        }
                    }
                }
                if ($cart_id == -1) {
                    $model->save();
                    $cart[] = array('product_id' => $model->product_id);
                    Yii::app()->controller->module->setCartcontent($cart);
                } else {
                    $quantum = $model->quantum;
                    $model = ShopProducts::model()->findByPk($cart_id);
                    $model->quantum+=$quantum;
                    $model->save();
                }

                $text = Yii::t('shop_cart', 'Product added to cart');
            }
        }
        echo '<script type="text/javascript">parent.iFrameShowWindow("Корзина", "' . $text . '",true);'
        . 'parent.$("#shopping-cart").load("' . Yii::app()->createAbsoluteUrl('/shop_cart/shoppingCart/cart') . '");'
        . 'parent.$("#shopping-cart-small").load("' . Yii::app()->createAbsoluteUrl('/shop_cart/shoppingCart/cart', array('type' => 'small')) . '");'
        . '</script>';
    }

    public function actionView() {

        if (!Yii::app()->user->isGuest) {
            $order_state = array('step' => 'make');
            Yii::app()->user->setState('order_state', CJSON::encode($order_state));
        }

        $update_url = $this->createUrl('/shop_cart/shoppingCart/update');
        $cart_url = $this->createUrl('/shop_cart/shoppingCart/cart');
        $script = <<<SCRIPT
					function ShoppingCartUpdateQuantum(id) {
						$.ajax({
							url:"$update_url",
							data: {"id":id,"quantum":$("#amount_"+id).val()},
							success: function(result) {			
							
                                                            $.fn.yiiGridView.update("cart-grid");
							},
							error: function() {
							$(".amount_"+id).css("background-color", "lightred");
							},
							});
				};
SCRIPT;

        Yii::app()->clientScript->registerScript(__CLASS__ . "#amount_update", $script, CClientScript::POS_END);

        $this->render('view', array(
            'model' => new ShopProducts,
        ));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $cart = Yii::app()->controller->module->getCartContent();
            foreach ($cart as $key => $value) {
                if ($value['product_id'] == $id)
                    unset($cart[$key]);
            }
            
            ShopProducts::model()->deleteByPk($id);
            
            Yii::app()->controller->module->setCartContent($cart);

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view'));
        } else
            throw new CHttpException(400, Yii::t('shop_cart', 'This page doesn\'t exist.'));
    }

    public function actionCart($type = 'big') {
        if ($type == 'big')
            echo Yii::app()->controller->module->getCartUpdateBlock();
        else
            echo Yii::app()->controller->module->getCartSmallUpdateBlock();
    }

    public function actionGetPriceTotal() {
        echo Yii::app()->controller->module->getPriceTotal();
    }

    public function actionUpdate($id, $quantum) {

        $model = ShopProducts::model()->findByPk($id);
        if ($model == null) {
            throw new CHttpException(400, Yii::t('shop_cart', 'This page doesn\'t exist.'));
        }
        $model->quantum = $quantum;
        $model->save();
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view'));
    }

    public function actionUpdateAll() {



        $cart = Yii::app()->getModule('shop_cart')->getCartContent();
//        print_r($cart);
//        die;
        Yii::log(count($cart), CLogger::LEVEL_INFO, 'parsersApi');
        $quantums = Yii::app()->request->getPost('quantums', array());
        foreach ($cart as $value) {
            $id = $value['product_id'];
//            Yii::log('id' . $id);
            Yii::log('id' . $id, CLogger::LEVEL_INFO, 'parsersApi');

            $model = ShopProducts::model()->findByPk($id);
            if ($model != null && isset($quantums[$id])) {
                $model->quantum = $quantums[$id];
                $model->save();
            }
        }
        echo CJSON::encode("Ok");
    }
}