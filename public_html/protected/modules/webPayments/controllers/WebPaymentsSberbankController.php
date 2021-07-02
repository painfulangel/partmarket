<?php
class WebPaymentsSberbankController extends Controller {
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
				'actions' => array('create', 'payOrder', 'prepayOrder', 'getMainJs', 'confirmPayOrder', 'confirmPrepayOrder'),
				'users' => array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array('pay', 'prepay'),
				'roles' => array('mainManager', 'admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}
	
	public function actionCreate($sum = 0) {
		$this->render('create', array(
			'model' => $this->loadModel(),
		));
	}
	
	public function actionPayOrder() {
		Yii::app()->clientScript->registerScriptFile(Yii::app()->createUrl('/webPayments/webPaymentsSberbank/getMainJs'));
		
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
	    Yii::app()->clientScript->registerScriptFile(Yii::app()->createUrl('/webPayments/webPaymentsSberbank/getMainJs'));
	    
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
		
		if (is_object($order) && $order->canPayed()) {
			//Письмо администратору об оплате заказа
			$message = new YiiMailMessage();
			$message->setBody($this->getEmailConfirmText($order), 'text/html');
			$message->setSubject(Yii::t('webPayments', 'Sberbank.Online payment'));
			$message->addTo(Yii::app()->config->get('Site.AdminEmail'));
			$message->from = Yii::app()->config->get('Site.NoreplyEmail');
			Yii::app()->mail->send($message);
			
			die(CJSON::encode(array('success' => Yii::t('webPayments', 'Notification payment is sent to administrator.'))));
		}
		
		die(CJSON::encode(array('error' => Yii::t('webPayments', 'There was a mistake at order confirmation'))));
	}
	
	public function actionConfirmPrepayOrder() {
	    $id_order = intval(Yii::app()->request->getPost('id_order'));
	    
	    $order = Orders::model()->findByPk($id_order);
	    
	    if (is_object($order) && $order->canPayed()) {
	        //Письмо администратору о предоплате заказа
	        $message = new YiiMailMessage();
	        $message->setBody($this->getEmailConfirmText($order, true), 'text/html');
	        $message->setSubject(Yii::t('webPayments', 'Sberbank.Online payment'));
	        $message->addTo(Yii::app()->config->get('Site.AdminEmail'));
	        //$message->addTo('elena.london2015@yandex.ru');
	        $message->from = Yii::app()->config->get('Site.NoreplyEmail');
	        Yii::app()->mail->send($message);
	        
	        die(CJSON::encode(array('success' => Yii::t('webPayments', 'Notification payment is sent to administrator.'))));
	    }
	    
	    die(CJSON::encode(array('error' => Yii::t('webPayments', 'There was a mistake at order confirmation'))));
	}
	
	public function actionPay($id_order) {
		$order = Orders::model()->findByPk(intval($id_order));
		
		if (is_object($order) && $order->canPayed()) {
		    $order->finishOrder();
			$order->save();
			
			$this->redirect(array('/shop_cart/adminOrders/admin/'));
		}
	}
	
	public function actionPrepay($id_order) {
	    $order = Orders::model()->findByPk(intval($id_order));
	    
	    if (is_object($order) && $order->canPayed()) {
	        $sum = $order->getPrePaySum();
	        
            //Предоплата заказа
            $order->tatal_paid = (float) $sum;
            $order->prepay = 1;
            
            $user = UserProfile::model()->findByAttributes(array('uid' => $order->user_id));
            if (is_object($user)) $user->addMoneyOperation(- $order->tatal_paid, Yii::t('shop_cart', 'Prepayment order №').$order->id, $order->id);
            
            $order->save();
            
	        $this->redirect(array('/shop_cart/adminOrders/admin/'));
	    }
	}
	
	private function getEmailConfirmText($order, $prepay = false) {
	    $link = Yii::app()->createAbsoluteUrl('/webPayments/webPaymentsSberbank/'.($prepay ? 'pre' : '').'pay', array('id_order' => $order->primaryKey));
	    
	    $sum = $prepay ? $order->getPrePaySum(): $order->total_cost;
	    
		return Yii::t('shop_cart', 'Hello!')."<br><br>\n\n".
				Yii::t('webPayments', 'There was a payment through Sberbank online, the amount of payment: {sum}', array('{sum}' => Yii::app()->getModule('shop_cart')->getPriceFormatFunction($sum)))."<br>\n".
				Yii::t('webPayments', 'Order No.{number}.', array('{number}' => $order->primaryKey))."<br>\n".
				Yii::t('webPayments', 'Check the operation. If the payment has passed, then <a href="{link}">click the link</a>.', array('{link}' => $link));
	}
	
	private function loadModel() {
		return WebPaymentsSystem::model()->findByPk(7);
	}
}