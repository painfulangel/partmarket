<?php
class WebPaymentsController extends Controller {
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('pay', 'prepay', 'view'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionView($token) {
        $model = $this->loadModel(0, $token);
        $this->render('view', array(
            'model' => $model,
        ));
    }

    public function actionPay($sum = 0) {
    	$order = null;
    	$sum = 0;
    	
    	$order_id = intval(Yii::app()->request->getQuery('order', 0));
    	
    	if ($order_id) {
    		//1. Заказ не должен быть оплачен
    		//2. Заказ должен принадлежать пользователю
    		$item = Orders::model()->findByPk($order_id);
    		
    		if (is_object($item) && $item->canPayed() && ($item->user_id == Yii::app()->user->id)) {
    		    $sum = Yii::app()->getModule('prices')->getPriceFormatFunction($item->left_pay);
    		    
    			$order = $item;
    		}
    	}
    	
        if ($sum != 0) {
            $this->temp = $sum;
        }
        
        $criteria = new CDbCriteria;
        $criteria->compare('active_state', 1);
        $order ? $criteria->addCondition('show_order = 1') : $criteria->addCondition('show_balance = 1');
        $criteria->order = 'sequence ASC';
        
        $dataProvider = new CActiveDataProvider('WebPaymentsSystem', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            ),
        ));
        
        $this->render('pay', array(
        	'dataProvider' => $dataProvider,
            'order'        => $order,
        	'order_id'     => $order_id,
            'sum'          => $sum,
        ));
    }
    
    public function actionPrepay($order) {
        if ($order_id = intval($order)) {
            $item = Orders::model()->findByPk($order_id);
            
            if (is_object($item) && $item->canPayed() && $item->isPrePayOrder() && ($item->user_id == Yii::app()->user->id)) {
                $criteria = new CDbCriteria;
                $criteria->compare('active_state', 1);
                $criteria->compare('show_prepay', 1);
                $criteria->order = 'sequence ASC';
                
                $dataProvider = new CActiveDataProvider('WebPaymentsSystem', array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 999,
                    ),
                ));
                
                $this->render('prepay', array(
                    'dataProvider' => $dataProvider,
                    'order' => $item,
                ));
            }
        }
        
        throw new CHttpException(404, Yii::t('webPayments', 'This page doesn\'t exist.'));
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $token = '') {
        if (!empty($token)) {
            $model = WebPayments::model()->findByAttributes(array('auth_key' => $token));
        } else
            $model = WebPayments::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('webPayments', 'This page doesn\'t exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'web-payments-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}