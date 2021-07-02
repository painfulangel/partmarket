<?php
class AdminShoppingCartController extends Controller {
     public $layout = '//layouts/admin_column2';
    
     public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('view', 'update','updateAll', 'delete', 'getPriceTotal'),
                'roles' => array('managerNotDiscount', 'manager', 'mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
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
            throw new CHttpException(400,Yii::t('shop_cart', 'This page doesn\'t exist.'));
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