<?php
class OrdersController extends Controller {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('getMainJs', 'order', 'initStep', 'create'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update', 'index', 'getOrderTotalBlock', 'getOrderTransportTotalBlock', 'bill', 'waybill', 'checkDocument', 'orderBill', 'cancelOrder'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionGetMainJs() {
        $this->renderPartial('getMainJs');
    }

    public function actionOrderBill($id) {
        $path = realpath(Yii::app()->basePath . '/../upload_files/1c_users_data') . '/';

        $last_file = $path . 'orders/' . $id . '/СчетНаОплату.xls';

        $model = Orders::model()->findByPk($id);
        if ($model != NULL && $model->user_id != Yii::app()->user->id && !( Yii::app()->user->checkAccess('managerNotDiscount') ||
                Yii::app()->user->checkAccess('manager') ||
                Yii::app()->user->checkAccess('mainManager') ||
                Yii::app()->user->checkAccess('admin'))) {
            throw new CHttpException(400, Yii::t('shop_cart', 'Access denied.'));
        }

        if (file_exists($last_file)) {
            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header("Content-type:   application/x-msexcel; charset=utf-8");
            header("Content-Disposition: attachment; filename=СчетНаОплату" . date('d_m_Y') . ".xls");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            $this->renderFile($last_file);
        } else
            throw new CHttpException(400, Yii::t('shop_cart', 'The document has not yet formed. Please try again later.'));
    }

    public function actionBill() {
        $path = realpath(Yii::app()->basePath . '/../upload_files/1c_users_data') . '/';

        $last_file = $path . 'users/' . Yii::app()->user->id . '/СчетФактура.xls';

        if (file_exists($last_file)) {
            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header("Content-type:   application/x-msexcel; charset=utf-8");
            header("Content-Disposition: attachment; filename=СчетФактура" . date('d_m_Y') . ".xls");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            $this->renderFile($last_file);
        } else
            throw new CHttpException(400, Yii::t('shop_cart', 'The document has not yet formed. Please try again later.'));
    }

    public function actionWaybill() {
        $path = realpath(Yii::app()->basePath . '/../upload_files/1c_users_data') . '/';

        $last_file = $path . 'users/' . Yii::app()->user->id . '/РасходнаяНакладная.xls';

        if (file_exists($last_file)) {
            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header("Content-type:   application/x-msexcel; charset=utf-8");
            header("Content-Disposition: attachment; filename=РасходнаяНакладная" . date('d_m_Y') . ".xls");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            $this->renderFile($last_file);
        } else
            throw new CHttpException(400, Yii::t('shop_cart', 'The document has not yet formed. Please try again later.'));
    }

    public function actionCheckDocument() {
        $path = realpath(Yii::app()->basePath . '/../upload_files/1c_users_data') . '/';

        $last_file = $path . 'users/' . Yii::app()->user->id . '/ТОРГ12.xls';

        if (file_exists($last_file)) {
            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header("Content-type:   application/x-msexcel; charset=utf-8");
            header("Content-Disposition: attachment; filename=ТОРГ12" . date('d_m_Y') . ".xls");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            $this->renderFile($last_file);
        } else
            throw new CHttpException(400, Yii::t('shop_cart', 'The document has not yet formed. Please try again later.'));
    }

    public function actionGetOrderTotalBlock($type) {
        $this->renderPartial('_order_total_block', array(
            'type' => $type,
        ));
    }
    
    public function actionGetOrderTransportTotalBlock($transport = 0) {
        $dt = null;
        
        if ($transport) $dt = DeliveryTransport::model()->findByPk(intval($transport));
        
        $this->renderPartial('_order_transport_total_block', array(
            'dt' => $dt,
        ));
    }

    public function actionInitStep() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('order'));
        } else {
            if (is_string(Yii::app()->user->getState('order_state')))
                $order_state = CJSON::decode(Yii::app()->user->getState('order_state'), true);
            else
                $order_state = Yii::app()->user->getState('order_state');
            if (!$order_state || empty($order_state)) {
                $this->redirect(array('/site/index'));
            } else {
                $this->redirect(array('order', 'step' => $order_state['step']));
            }
        }
    }

    public function actionOrder($step = '') {
        switch ($step) {
            case 'make':
                $this->redirect(array('create'));
                break;
            case 'pre-login':
                Yii::app()->user->returnUrl = '/shop_cart/orders/order';
//                $order_state = array('step' => 'make');
//                Yii::app()->user->setState('order_state', CJSON::encode($order_state));
                $this->redirect(array('order', 'step' => 'login'));
                break;
            case 'login':
                $order_state = array('step' => 'make');
                Yii::app()->user->setState('order_state', CJSON::encode($order_state));
                $this->redirect(array('/lily/user/login'));
                break;
            case 'registration':
                $model = new LRegisterForm;
                $model_profile = new UserProfile;
                $model_profile->scenario = 'fast_regs';
                if (isset($_POST['UserProfile'])) {

                    $model_profile->attributes = $_POST['UserProfile'];
                    $model->email = $model_profile->email;
//                    print_r($_POST);
                    $model->password = $model_profile->reg_password;
                    $model->passwordRepeat = $model_profile->reg_password;
//                    print_r($model_profile);
//                    print_r($model);
//                    $model_profile->email = $model->email;
                    if ($model->validate() && $model_profile->validate()) {

                        $email = $model->email;
                        $password = $model->password;
                        $authIdentity = new LEmailService;
                        $authIdentity->email = $email;

                        $authIdentity->password = $password;
                        $authIdentity->user = Yii::app()->user->isGuest ? null : LilyModule::instance()->user;
                        $authIdentity->rememberMe = $model->rememberMe;
                        if ($authIdentity->authenticate(true, true, true)) {
                            $identity = new LUserIdentity($authIdentity);
                            $identity->authenticate();
                            $result = Yii::app()->user->login($identity, $model->rememberMe ? LilyModule::instance()->sessionTimeout : 0);

                            $model_profile->uid = $identity->account->uid;
                            $model_profile->save();

                            if ($result)
                                Yii::app()->user->setFlash('lily.login.success', Yii::t('shop_cart', 'You have successfully logged in.'));
                            else
                                throw new LException("login() returned false");
                        }
                        echo $authIdentity->cancel(); //Special redirect to fire popup window closing
                    }else {
                        $this->render('order', array(
                            'model' => new ShopProducts,
                        ));
                        die;
                    }
                } else {
                    $this->redirect('/shop_cart/orders/initStep');
                }
//                forceFinish()
//                $this->redirect('/shop_cart/orders/initStep');
                $order_state = array('step' => 'make');
                Yii::app()->user->setState('order_state', CJSON::encode($order_state));
                $this->redirect(array('create', 'force' => 1));
//                $order_state = array('step' => 'make');
//                Yii::app()->user->setState('order_state', CJSON::encode($order_state));
//                $this->redirect('/lily/user/register');
                break;
            default:
                if (Yii::app()->user->isGuest)
                    $this->render('order', array(
                        'model' => new ShopProducts,
                    ));
                else
                    $this->redirect(array('create'));
                break;
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($force = 0) {
    	//If no products
    	$cart = Yii::app()->controller->module->getCartContent();
    	
    	if (!is_array($cart) || !count($cart)) {
    		throw new CHttpException(404, Yii::t('shop_cart', 'This page doesn\'t exist.'));
    	}
    	//If no products
    	
        if ($force == '1') {
            LilyModule::instance()->userIniter->forceFinish();
        }
        
        if (Yii::app()->user->isGuest)
            $this->redirect(array('initStep'));
        $model = new Orders;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

        if (isset($_POST['Orders'])) {
        	$post = $_POST['Orders'];
        	
        	$model->attributes = $post;
        	$model->user_id = UserProfile::getUserActiveId();
        	
        	//!!! Check payment method and delivery method
        	/*$payment = Yii::app()->controller->module->payment_model->getList();
        	
        	if (!array_key_exists('payment_method', $post) || !array_key_exists($post['payment_method'], $payment)) {
        		$message = Yii::t('yii', '{attribute} cannot be blank.');
        		$message = strtr($message, array('{attribute}'=>$model->getAttributeLabel('delivery_method')));
        		
        		$model->addError('payment_method', $message);
        	}*/
        	
        	$delivery = Yii::app()->controller->module->delivery_model->getList();
        	
        	if (!array_key_exists('delivery_method', $post) || !array_key_exists($post['delivery_method'], $delivery)) {
        		$message = Yii::t('yii', '{attribute} cannot be blank.');
        		$message = strtr($message, array('{attribute}'=>$model->getAttributeLabel('delivery_method')));
        		
        		$model->addError('delivery_method', $message);
        	}
        	//!!! Check payment method and delivery method
        	
            if (!$model->hasErrors() && $model->save()) {
                Yii::app()->user->setState('order_state', CJSON::encode(array()));

                /*if ($model->pay_redirect) {
                    $this->redirect(array('/webPayments/webPayments/pay', 'sum' => (-$model->getUser()->balance)));
                } else {*/
                	setcookie('neworder', $model->primaryKey, (time() + 3600), '/');
                	
                    $this->redirect(array('index'));
                //}
            }
        } else {
            $user = UserProfile::model()->findByAttributes(array('uid' => UserProfile::getUserActiveId()));
            $model->initUserData($user);
        }

        $this->render('create', array(
            'model' => $model,
            'model_cart' => new ShopProducts,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if ($model->user_id != Yii::app()->user->id)
            throw new CHttpException(404, Yii::t('shop_cart', 'This page doesn\'t exist.'));

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Orders'])) {
            $model->attributes = $_POST['Orders'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $orderStatus = new OrdersStatus;
        $itemStatus = new ItemsStatus;

        $this->render('update', array(
            'model' => $model,
            'orderStatus' => $orderStatus,
            'itemStatus' => $itemStatus,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
            $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, Yii::t('shop_cart', 'This page doesn\'t exist.'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Orders('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Orders']))
            $model->attributes = $_GET['Orders'];

        $model->user_id = Yii::app()->user->id;

        $orderStatus = new OrdersStatus;
        
        if ($neworder = (array_key_exists('neworder', $_COOKIE) ? intval($_COOKIE['neworder']) : 0)) {
        	setcookie('neworder', 0, time(), '/');
        }
        
        $this->render('index', array(
            'model' => $model,
            'orderStatus' => $orderStatus,
        	'neworder' => $neworder,
        	'checkOrder' => Yii::app()->config->get('Site.CheckOrderBeforePayment'),
        ));
    }

//    /**
//     * Manages all models.
//     */
//    public function actionAdmin() {
//        $model = new Orders('search');
//        $model->unsetAttributes();  // clear any default values
//        if (isset($_GET['Orders']))
//            $model->attributes = $_GET['Orders'];
//
//        $this->render('admin', array(
//            'model' => $model,
//        ));
//    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Orders::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('shop_cart', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'orders-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    public function actionCancelOrder() {
        $id_order = intval(Yii::app()->request->getPost('id_order'));
        
        $order = Orders::model()->findByPk($id_order);
        
        if (is_object($order) && ($order->user_id == Yii::app()->user->id)) {
            $order->cancelled = 1;
            $order->save();
            
            //Пользователь отменил заказ
            $message = new YiiMailMessage();
            $message->setBody($this->getEmailCancelText($order), 'text/html');
            $message->setSubject(Yii::t('shop_cart', 'Order is cancelled.'));
            $message->addTo(Yii::app()->config->get('Site.AdminEmail'));
            //$message->addTo('elena.london2015@yandex.ru');
            $message->from = Yii::app()->config->get('Site.NoreplyEmail');
            Yii::app()->mail->send($message);
            
            die(CJSON::encode(array('success' => Yii::t('shop_cart', 'Message is sent to administrator.'))));
        }
    }
    
    private function getEmailCancelText($order) {
        return Yii::t('shop_cart', 'Hello!')."<br><br>\n\n".
               Yii::t('shop_cart', 'Customer {customer_name} wants to cancel the order №{order_number}.', array('{customer_name}' => $order->getUser()->getFullName(), '{order_number}' => $order->id));
    }
}
