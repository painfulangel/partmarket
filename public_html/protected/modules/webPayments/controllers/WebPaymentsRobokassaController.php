<?php

class WebPaymentsRobokassaController extends Controller
{

    /**
     *
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *      using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     *
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl' // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * 
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array(
                    'success',
                    'fail',
                    'result',
                    'pay',
                    'payOrder',
                    'prepayOrder'
                ),
                'users' => array(
                    '*'
                )
            ),
            array(
                'allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array(
                    'create'
                ),
                'users' => array(
                    '@'
                )
            ),
            array(
                'deny', // deny all users
                'users' => array(
                    '*'
                )
            )
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($sum = 0)
    {
        $model = new WebPaymentsRobokassa();
        
        if ($sum != 0) {
            $model->value = $sum;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
        if (isset($_POST['WebPaymentsRobokassa'])) {
            $model->attributes = $_POST['WebPaymentsRobokassa'];
            if ($sum != 0 && $model->value < $sum) {
                $model->value = $sum;
            }
            
            if ($model->save())
                $this->redirect(array(
                    'pay',
                    'token' => $model->auth_key
                ));
        }
        
        $this->render('create', array(
            'model' => $model
        ));
    }

    public function actionResult()
    {
        if (array_key_exists("InvId", $_REQUEST)) {
            //log
            ob_start();
            echo '<pre>'; print_r($_REQUEST); echo '</pre>';
            file_put_contents(Yii::getPathOfAlias('webroot').'/robo.txt', date('d.m.Y H:i').' - result - '.ob_get_clean()."\n", FILE_APPEND);
            
            $model = new WebPaymentsRobokassa();
            $out_summ = $_REQUEST["OutSum"];
            $inv_id = $_REQUEST["InvId"];
            $shp_item = $_REQUEST["Shp_item"];
            $crc = $_REQUEST["SignatureValue"];
            
            $crc = strtoupper($crc);
            $model = $this->loadModel($inv_id);
            $model->total_value = $out_summ;
            if (strtoupper($model->getSign(2)) == $crc) {
                $model->scenario = 'finish';
                echo "OK$inv_id\n";
                $model->save();
                
                if ($order_id = intval($model->order_id)) {
                    //Если оплата заказа, отмечаем, что заказ оплачен
                    $order = Orders::model()->findByPk($order_id);
                    
                    if (is_object($order)) {
                        if ($model->prepay) {
                            //Предоплата заказа
                            $order->tatal_paid = (float) $model->value;
                            $order->prepay = 1;
                            
                            $user = UserProfile::model()->findByAttributes(array('uid' => $order->user_id));
                            if (is_object($user)) $user->addMoneyOperation(- $order->tatal_paid, Yii::t('shop_cart', 'Prepayment order №').$order->id, $order->id);
                        } else {
                            $order->finishOrder();
                        }
                        
                        $order->save();
                    }
                }
            } else {
                echo "bad sign\n";
            }
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    public function actionSuccess()
    {
        if (array_key_exists("InvId", $_REQUEST)) {
            //log
            ob_start();
            echo '<pre>'; print_r($_REQUEST); echo '</pre>';
            file_put_contents(Yii::getPathOfAlias('webroot').'/robo.txt', date('d.m.Y H:i').' - success - '.ob_get_clean()."\n", FILE_APPEND);
            
            $model = new WebPaymentsRobokassa();
            $out_summ = $_REQUEST["OutSum"];
            $inv_id = $_REQUEST["InvId"];
            $shp_item = $_REQUEST["Shp_item"];
            $crc = $_REQUEST["SignatureValue"];
            $crc = strtoupper($crc);
            // $
            // print_r($_REQUEST);
            $model = $this->loadModel($inv_id);
            $model->total_value = $out_summ;
            // print_r($crc);
            // echo ' ';
            // print_r(strtoupper($model->getSign(5)));
            // die;
            if (strtoupper($model->getSign(3)) == $crc) {
                $this->redirect(array(
                    'webPayments/view',
                    'token' => $model->auth_key
                ));
            }
            
            throw new CHttpException('400', Yii::t('webPayments', 'Incorrect inquiry. Contact your administrator to check the status of your payment.'));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    public function actionFail()
    {
        if (array_key_exists("InvId", $_REQUEST)) {
            //log
            ob_start();
            echo '<pre>'; print_r($_REQUEST); echo '</pre>';
            file_put_contents(Yii::getPathOfAlias('webroot').'/robo.txt', date('d.m.Y H:i').' - fail - '.ob_get_clean()."\n", FILE_APPEND);
            
            $inv_id = $_REQUEST["InvId"];
            
            throw new CHttpException('400', Yii::t('webPayments', 'You refused payment'));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }
    
    public function actionPayOrder() {
        $order_id = intval(Yii::app()->request->getQuery('order', 0));
        
        if ($order_id) {
            //1. Заказ не должен быть оплачен
            //2. Заказ должен принадлежать пользователю
            $item = Orders::model()->findByPk($order_id);
            
            if (is_object($item) && $item->canPayed() && ($item->user_id == Yii::app()->user->id)) {
                $model = new WebPaymentsRobokassa();
                $model->value = $item->left_pay;
                $model->order_id = $order_id;
                
                if ($model->save()) {
                    $this->redirect(array('pay', 'token' => $model->auth_key));
                }
            }
        }
        
        throw new CHttpException(404, 'The requested page does not exist.');
    }
    
    public function actionPrepayOrder() {
        $order_id = intval(Yii::app()->request->getQuery('order', 0));
        
        if ($order_id) {
            //1. Заказ не должен быть оплачен
            //2. Заказ должен принадлежать пользователю
            $item = Orders::model()->findByPk($order_id);
            
            if (is_object($item) && $item->canPayed() && $item->isPrePayOrder() && ($item->user_id == Yii::app()->user->id)) {
                $model = new WebPaymentsRobokassa();
                $model->value = $item->getPrePaySum();
                $model->order_id = $order_id;
                $model->prepay = 1;
                
                if ($model->save()) {
                    $this->redirect(array('pay', 'token' => $model->auth_key));
                }
            }
        }
        
        throw new CHttpException(404, 'The requested page does not exist.');
    }
    
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionPay($token)
    {
        $model = WebPaymentsRobokassa::model()->findByAttributes(array(
            'auth_key' => $token
        ));
        
        $this->renderPartial('pay', array(
            'model' => $model
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * 
     * @param
     *            integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = WebPaymentsRobokassa::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('webPayments', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * 
     * @param
     *            CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'web-payments-robokassa-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
