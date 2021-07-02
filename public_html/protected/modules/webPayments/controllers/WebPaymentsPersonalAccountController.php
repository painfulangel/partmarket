<?php
class WebPaymentsPersonalAccountController extends Controller {
    public $layout = '//layouts/column2';
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }
    
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('payOrder', 'prepayOrder', 'getMainJs', 'confirmPayOrder', 'confirmPrepayOrder'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('pay'),
                'roles' => array('mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    public function actionPayOrder() {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->createUrl('/webPayments/webPaymentsPersonalAccount/getMainJs'));
        
        $order_id = intval(Yii::app()->request->getQuery('order', 0));
        
        if ($order_id) {
            //1. Заказ не должен быть оплачен
            //2. Заказ должен принадлежать пользователю
            $item = Orders::model()->findByPk($order_id);
            
            if (is_object($item) && $item->canPayed() && ($item->user_id == Yii::app()->user->id)) {
                $this->render('payOrder', array(
                    'model' => $this->loadModel(),
                    'order' => $item,
                ));
                
                die();
            }
        }
        
        throw new CHttpException(404, 'The requested page does not exist.');
    }
    
    public function actionPrepayOrder() {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->createUrl('/webPayments/webPaymentsPersonalAccount/getMainJs'));
        
        $order_id = intval(Yii::app()->request->getQuery('order', 0));
        
        if ($order_id) {
            //1. Заказ не должен быть оплачен
            //2. Заказ должен принадлежать пользователю
            $item = Orders::model()->findByPk($order_id);
            
            if (is_object($item) && $item->canPayed() && $item->isPrePayOrder() && ($item->user_id == Yii::app()->user->id)) {
                $this->render('prepayOrder', array(
                    'model' => $this->loadModel(),
                    'order' => $item,
                ));
                
                die();
            }
        }
        
        throw new CHttpException(404, 'The requested page does not exist.');
    }
    
    public function actionGetMainJs() {
        $this->renderPartial('getMainJs');
    }
    
    public function actionConfirmPayOrder() {
        $id_order = intval(Yii::app()->request->getPost('id_order'));
        
        $order = Orders::model()->findByPk($id_order);
        
        if (is_object($order) && $order->canPayed() && ($order->user_id == Yii::app()->user->id)) {
            $user = Yii::app()->getModule('userControl')->getCurrentUserModel();
            
            $model_balance = new UserBalanceOperations('search');
            $model_balance->user_id = $user->uid;
            $balance = $model_balance->getBalance();
            
            $sum = $order->left_pay;
            
            if ($sum <= $balance) {
                //Обновляем статус заказа
                $order->finishOrder();
                $order->save();
                
                die(CJSON::encode(array('success' => 1)));
            }
        }
        
        die(CJSON::encode(array('error' => Yii::t('webPayments', 'There was a mistake at order payment.'))));
    }
    
    public function actionConfirmPrepayOrder() {
        $id_order = intval(Yii::app()->request->getPost('id_order'));
        
        $order = Orders::model()->findByPk($id_order);
        
        if (is_object($order) && $order->canPayed() && $order->isPrePayOrder() && ($order->user_id == Yii::app()->user->id)) {
            $user = Yii::app()->getModule('userControl')->getCurrentUserModel();
            
            $model_balance = new UserBalanceOperations('search');
            $model_balance->user_id = $user->uid;
            $balance = $model_balance->getBalance();
            
            $sum = $order->getPrePaySum();
            
            if ($sum <= $balance) {
                //Предоплата заказа
                $order->tatal_paid = (float) $sum;
                $order->prepay = 1;
                
                $user = UserProfile::model()->findByAttributes(array('uid' => $order->user_id));
                if (is_object($user)) $user->addMoneyOperation(- $order->tatal_paid, Yii::t('shop_cart', 'Prepayment order №').$order->id, $order->id);
                
                $order->save();
                
                die(CJSON::encode(array('success' => 1)));
            }
        }
        
        die(CJSON::encode(array('error' => Yii::t('webPayments', 'There was a mistake at order payment.'))));
    }
    
    private function loadModel() {
        return WebPaymentsSystem::model()->findByPk(8);
    }
}