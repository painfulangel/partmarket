<?php
class WebPaymentsCourierController extends Controller {
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
                'actions' => array('payOrder', 'getMainJs', 'confirmPayOrder'),
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
        Yii::app()->clientScript->registerScriptFile(Yii::app()->createUrl('/webPayments/webPaymentsCourier/getMainJs'));
        
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
    
    public function actionGetMainJs() {
        $this->renderPartial('getMainJs');
    }
    
    public function actionConfirmPayOrder() {
        $id_order = intval(Yii::app()->request->getPost('id_order'));
        
        $order = Orders::model()->findByPk($id_order);
        
        if (is_object($order) && $order->canPayed() && ($order->user_id == Yii::app()->user->id)) {
            //Обновляем статус заказа
            $order->courier = 1;
            $order->save();
            
            //Письмо администратору
            $message = new YiiMailMessage();
            $message->setBody($this->getEmailConfirmText($order), 'text/html');
            $message->setSubject(Yii::t('webPayments', 'Pay order in cash to the courier'));
            $message->addTo(Yii::app()->config->get('Site.AdminEmail'));
            $message->from = Yii::app()->config->get('Site.NoreplyEmail');
            Yii::app()->mail->send($message);
            
            die(CJSON::encode(array('success' => 1)));
        }
        
        die(CJSON::encode(array('error' => Yii::t('webPayments', 'There was a mistake at order payment.'))));
    }
    
    private function getEmailConfirmText($order) {
        return Yii::t('shop_cart', 'Hello!')."<br><br>\n\n".
               date('d.m.Y H:i')."<br>\n".
               Yii::t('webPayments', 'Order No.{number}.', array('{number}' => $order->primaryKey))."<br>\n".
               Yii::t('webPayments', 'Chosen payment courier.')."<br>\n".
               $order->getUser()->getEmailText();
    }
    
    private function loadModel() {
        return WebPaymentsSystem::model()->findByPk(9);
    }
}