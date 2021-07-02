<?php
class AdminOrdersController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/admin_column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }
    
    protected function beforeAction($action)
    {
        $this->admin_header = array(
            array(
                'name' => Yii::t('shop_cart', 'Orders'),
                'url' => array('/shop_cart/adminOrders/admin'),
                'active' => true,
            ),
            array(
                'name' => Yii::t('shop_cart', 'Goods'),
                'url' => array('/shop_cart/adminItems/admin'),
                'active' => false,
            ),
            array(
                'name' => Yii::t('delivery', 'Delivery'),
                'url' => array('/shop_cart/adminDelivery/index'),
                'active' => false,
            ),
            array(
                'name' => Yii::t('delivery', 'Transport companies'),
                'url' => array('/shop_cart/adminDeliveryTransport/index'),
                'active' => false,
            ),
        );

        return true;
    }
    
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('admin', 'update', 'delete','customerBill', 'updateStatus', 'refundMoney', 'mergeOrders', 'mergeOrdersCheck', 'print', 'bill', 'waybill', 'restore', 'deleteTrash', 'orderBill', 'confirm', 'toggle'),
                'roles' => array('managerNotDiscount', 'manager', 'mainManager', 'admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    public function actions() {
        return array(
            'toggle'  => 'ext.jtogglecolumn.ToggleAction',
            'switch'  => 'ext.jtogglecolumn.SwitchAction', // only if you need it
            'qtoggle' => 'ext.jtogglecolumn.QtoggleAction', // only if you need it
        );
    }
    
    public function actionMergeOrdersCheck()
    {
        $ids = Yii::app()->request->getPost('merge_id', null);
        $id = Yii::app()->request->getPost('id', null);
        $model = $this->loadModel($id);
        echo CJSON::encode($model->mergeOrdersCheck($ids));
    }

    public function actionMergeOrders()
    {
        $ids = Yii::app()->request->getPost('merge_id', null);
        $id = (isset($_POST['Orders']['id']) ? $_POST['Orders']['id'] : 0);

        $model = $this->loadModel($id);
        $model->mergeOrders($ids);
//        echo CJSON::encode(array('msg' => 'Заказы объединены успешно'));

        $this->redirect(array('update', 'id' => $model->id));
    }

    public function actionOrderBill($id)
    {

        $path = realpath(Yii::app()->basePath . '/../upload_files/1c_users_data') . '/';

        $last_file = $path . 'orders/' . $id . '/СчетНаОплату.xls';

        $model = Orders::model()->findByPk($id);
        if ($model != NULL && $model->user_id != Yii::app()->user->id && !(Yii::app()->user->checkAccess('managerNotDiscount') ||
                Yii::app()->user->checkAccess('manager') ||
                Yii::app()->user->checkAccess('mainManager') ||
                Yii::app()->user->checkAccess('admin'))
        ) {
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

    public function actionCustomerBill($id)
    {
        // unregister Yii's autoloader
        // get a reference to the path of PHPExcel classes
        $phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes');
        // Turn off our amazing library autoload
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        // Yii::app()->end();

        spl_autoload_register(array('YiiBase', 'autoload'));
        $model = $this->loadModel($id);
        // Выводим HTTP-заголовки
        header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D,d M YH:i:s') . ' GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=СчетФактура$model->id.xls");
        //$model->getBill();
        // Выводим содержимое файла
        $objWriter = new PHPExcel_Writer_Excel5($model->getCustomBill());
        $objWriter->save('php://output');
    }

    public function actionBill($id)
    {
        // unregister Yii's autoloader
        // get a reference to the path of PHPExcel classes 
        $phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes');
        // Turn off our amazing library autoload 
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        // Yii::app()->end();

        spl_autoload_register(array('YiiBase', 'autoload'));
        $model = $this->loadModel($id);
        // Выводим HTTP-заголовки
        header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D,d M YH:i:s') . ' GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=Счет$model->id.xls");
        //$model->getBill();
        // Выводим содержимое файла
        $objWriter = new PHPExcel_Writer_Excel5($model->getBill());
        $objWriter->save('php://output');
    }

    public function actionWaybill($id)
    {
        // unregister Yii's autoloader
        // get a reference to the path of PHPExcel classes 
        $phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes');
        // Turn off our amazing library autoload 
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        // Yii::app()->end();

        spl_autoload_register(array('YiiBase', 'autoload'));
        $model = $this->loadModel($id);
        // Выводим HTTP-заголовки
        header('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D,d M YH:i:s') . ' GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=Накладная$model->id.xls");
        //$model->getBill();
        // Выводим содержимое файла
        $objWriter = new PHPExcel_Writer_Excel5($model->getWaybill());
        $objWriter->save('php://output');
    }

    public function actionPrint($id)
    {
        $model = Orders::model()->findByPk($id);
        $this->layout = '//layouts/admin_simple_main';

        $orderStatus = new OrdersStatus;
        $itemStatus = new ItemsStatus;
        $this->render('print', array(
            'model' => $model,
            'orderStatus' => $orderStatus,
            'itemStatus' => $itemStatus,
        ));
    }

    public function actionRefundMoney()
    {
        $model_id = Yii::app()->request->getPost('model_id', null);
        $model = Orders::model()->findByPk($model_id);
        $msg = $model->refundMoney();
        echo CJSON::encode(array('msg' => $msg));
    }

    public function actionUpdateStatus()
    {
        $model_id = Yii::app()->request->getPost('model_id', null);
        $status_id = Yii::app()->request->getPost('status_id', null);
        $flag = Yii::app()->request->getPost('flag', false);


        $model = new OrdersStatus;

        $msg = $model->changeStatus(Orders::model()->findByPk($model_id), $status_id, $flag);
        echo CJSON::encode(array('msg' => $msg));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Orders'])) {
            $model->attributes = $_POST['Orders'];
            if ($model->save())
                $this->redirect(array('admin', 'Orders_page' => (isset($_GET['Orders_page']) ? $_GET['Orders_page'] : '')));
        }

        $orderStatus = new OrdersStatus;
        $itemStatus = new ItemsStatus;

        $this->render('update', array(
            'model' => $model,
            'orderStatus' => $orderStatus,
            'itemStatus' => $itemStatus,
        ));
    }

    public function actionDeleteTrash()
    {
        if (Yii::app()->request->isPostRequest) {
            Orders::model()->deleteTrash();
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, Yii::t('shop_cart', 'This page doesn\'t exist.'));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
//        if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
        $this->loadModel($id)->toTrash();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//        } else
//            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionRestore($id)
    {
//        if (Yii::app()->request->isPostRequest) {
        // we only allow deletion via POST request
        $this->loadModel($id)->fromTrash();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//        } else
//            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Orders('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Orders']))
            $model->attributes = $_GET['Orders'];

        $orderStatus = new OrdersStatus;
        $this->render('admin', array(
            'model' => $model,
            'orderStatus' => $orderStatus,
        ));
    }

    public function actionConfirm() {
    	$id_order = intval(Yii::app()->request->getPost('id_order'));
    	
    	$order = Orders::model()->findByPk($id_order);
    	
    	if (is_object($order) && (intval($order->confirmed) == 0)) {
    		$order->confirmed = 1;
    		$order->save();
    		
    		//Письмо клиенту о подтверждении заказа
    		$message = new YiiMailMessage();
    		$message->setBody($this->getEmailConfirmText($order), 'text/html');
    		$message->setSubject(Yii::t('shop_cart', 'Your order on site {site} is confirmed', array('{site}' => Yii::app()->config->get('Site.SiteName'))));
    		$message->addTo($order->getUser()->getEmail());
    		$message->from = Yii::app()->config->get('Site.NoreplyEmail');
    		Yii::app()->mail->send($message);
    		
    		die(CJSON::encode(array('success' => Yii::t('shop_cart', 'Order is confirmed.'))));
    	}
    	
    	die(CJSON::encode(array('error' => Yii::t('shop_cart', 'There was a mistake at order confirmation'))));
    }
    
    private function getEmailConfirmText($order) {
    	$text = Yii::t('shop_cart', 'Hello!')."<br><br>\n\n".
      	Yii::t('shop_cart', 'Thank you for your order №{number}.', array('{number}' => $order->primaryKey))."<br>\n".
      	Yii::t('shop_cart', 'Your order is confirmed.')."<br>\n".
      	Yii::t('shop_cart', 'You ordered:')."<br><br>\n\n".
      	$order->getItemsEmailText()."<br>\n";
      	
      	if ($order->isPrePayOrder()) {
      	    $amount = Yii::app()->getModule('prices')->getPriceFormatFunction($order->getPrePaySum());
      	    
      	    $link1 = Yii::app()->createAbsoluteUrl('/webPayments/webPayments/prepay', array('order' => $order->primaryKey));
      	    $link2 = Yii::app()->createAbsoluteUrl('/webPayments/webPayments/pay', array('order' => $order->primaryKey));
      	    
      	    $text .= Yii::t('shop_cart', 'In the order there are custom items, please make a prepayment {amount}.', array('{amount}' => $amount))."<br>\n".
          	         Yii::t('shop_cart', 'You can make it here: <a href="{link}">PREPAYMENT</a> / <a href="{link2}">TOTAL COST</a>.', array('{link}' => $link1, '{link2}' => $link2))."<br>\n";
      	} else {
      	    $text .= Yii::t('shop_cart', 'You can pay it <a href="{link}">here</a>.', array('{link}' => Yii::app()->createAbsoluteUrl('/webPayments/webPayments/pay', array('order' => $order->primaryKey))))."<br>\n";
      	}
      	
      	$text .= Yii::t('shop_cart', 'YOUR STORE')."<br>\n".
      	Yii::app()->getRequest()->getHostInfo();
      	
      	return $text;
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Orders::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('shop_cart', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'orders-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}