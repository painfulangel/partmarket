<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column2';
    public $admin_header = array();
    public $admin_subheader = array();

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    public $_cabinet = '';

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     *  Meta Tag which shows in the site
     * 
     */
    public $metaKeywords = null;

    /**
     *  Meta Tag which shows in the site
     * 
     */
    public $metaDescription = null;

    /**
     *  Meta Tag which shows in the site
     * 
     */
    public $metaTitle = null;
    public $temp = null;

    function beforeRender($view) {
        if (count($this->admin_header) == 0) {
            $this->admin_header[] = array(
                'name' => '{pageTitle}',
                'url' => Yii::app()->request->requestUri,
                'active' => true,
            );
        }
        if (parent::beforeRender($view)) {
            if (!Yii::app()->user->isGuest) {
                Yii::import('shop_cart.components.*');
                $model = UserProfile::model()->findByAttributes(array('uid' => Yii::app()->user->id));
                if ($model != NULL) {
                    $status_model = new OrdersStatus;
                    $user_orders_data = $model->getStatusOrders();
                    $this->_cabinet = '<h2>Заказы</h2><ul class="orders-links-list">';
                    foreach ($status_model->getList() as $key => $value) {
                        if ($key == '6')
                            $value = 'Готов к выдаче';
                        $this->_cabinet.='<li>' . CHtml::link($value . ' (' . (isset($user_orders_data[$key]) ? $user_orders_data[$key] : 0) . ')', array('/shop_cart/orders/index', 'Orders[status]' => $key)) . '</li>';
                    }
                    $this->_cabinet.='<li>' . CHtml::link('Все заказы', array('/shop_cart/orders/index')) . '</li>';

                    $this->_cabinet.='</ul>';
                }
            }
            return true;
        } else
            return false;
    }

    /**
     * Reinit function for aplication
     * Use to set site lanuage
     */
    function init() {
        parent::init();

        $app = Yii::app();
        if (isset($_GET['_lang'])) {
        	$langs = array();
        	
        	$models = Yii::app()->db->createCommand('SELECT `link_name` FROM `languages` WHERE `active` = 1')->queryAll();
        	foreach ($models as $model) {
        		$langs[] = $model['link_name'];
        	}
        	
            if (count(array_intersect(array($_GET['_lang']), array_merge(array('ru'), $langs))) == 0) {
            	//$this->redirect(array('site/index'));
            	throw new CHttpException(404, Yii::t('admin_layout', 'This page doesn\'t exist.'));
            }
            
            $app->language = $_GET['_lang'];
            $app->session['_lang'] = $app->language;
        }
        else if (isset($app->session['_lang'])) {
            $app->language = $app->session['_lang'];
        }
    }
}