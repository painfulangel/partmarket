<?php
	class WebPaymentsCreditController extends Controller {
		public function actionPayOrder() {
	        $order_id = intval(Yii::app()->request->getQuery('order', 0));
	        
	        if ($order_id) {
	            //1. Заказ не должен быть оплачен
	            //2. Заказ должен принадлежать пользователю
	            $item = Orders::model()->findByPk($order_id);
	            
	            if (is_object($item) && $item->canPayed() && ($item->user_id == Yii::app()->user->id)) {
	                $model = new WebPaymentsCredit();
	                $model->value = $item->left_pay;
	                $model->order_id = $order_id;
	                
	                if ($model->save()) {
	                    $this->redirect(array('pay', 'token' => $model->auth_key));
	                }
	            }
	        }
	        
	        throw new CHttpException(404, 'The requested page does not exist.');
	    }

	    public function actionPay($token) {
	        $model = WebPaymentsCredit::model()->findByAttributes(array(
	            'auth_key' => $token
	        ));
	        
	        $this->renderPartial('pay', array(
	            'model' => $model,
	            'order' => Orders::model()->findByPk($model->order_id)
	        ));
	    }

	    public function actionSuccess() {
	    	if ($order_id = Yii::app()->request->getPost('order_id')) {
	            $order = Orders::model()->findByPk($order_id);
		    	$wpc = WebPaymentsCredit::model()->find(array('condition' => 'order_id = '.$order_id, 'order' => 'id DESC'));

		    	if (is_object($order) && is_object($wpc) && !$wpc->finish_date) {
	            	$wpc->total_value = $order->total_cost;
		    		$wpc->result = 1;
	                $wpc->scenario = 'finish';
	                $wpc->save();

	                $order->finishOrder();
	                $order->save();

	                echo CJSON::encode(array('success' => true));
		    	}
		    }
	    }

	    public function actionFail() {
	    	if ($order_id = Yii::app()->request->getPost('order_id')) {
	            $order = Orders::model()->findByPk($order_id);
		    	$wpc = WebPaymentsCredit::model()->find(array('condition' => 'order_id = '.$order_id, 'order' => 'id DESC'));

		    	if (is_object($order) && is_object($wpc) && !$wpc->finish_date) {
	            	$wpc->total_value = $order->total_cost;
	                $wpc->scenario = 'finish';
	                $wpc->save();

	                echo CJSON::encode(array('success' => true));
		    	}
		    }
	    }
	}
?>