<?php
/**
 * @author ElisDN <mail@elisdn.ru>
 * @link http://www.elisdn.ru
 */
class DMenu extends CApplicationComponent {
    public $cache = 0;
    public $cacheId = 'MenuAllFiles';
    public $dependency = null;
    protected $data = array();

    public function init() {

        parent::init();
    }

    public function generateMenuFromDB() {
        $db = $this->getDbConnection();
        $items = array();
        if (Yii::app()->language == Yii::app()->params['default_language']) {
            $items = $db->createCommand('SELECT `id`, `order`, `menu_type`, `menu_value`, `echo_position`, `title`, `visible` FROM menus `t` ORDER BY t.order, id')->queryAll();
        } else {
            $items = $db->createCommand('SELECT t.id as id, `order`, `menu_type`, `menu_value`, `echo_position`, menus_' . Yii::app()->language . '.`title`, `visible` FROM menus `t` LEFT JOIN `menus_' . Yii::app()->language . '` ON menus_' . Yii::app()->language . '.id=t.id ORDER BY t.order, t.id')->queryAll();
        }
        $this->data = array();

        foreach ($items as $item) {
        	switch ($item['menu_type']) {
        		case 'pages_top':
        		case 'pages_left':
        			$this->data[$item['echo_position']][] = array('type' => 'cache', 'value' => $item['menu_type'], 'title' => '');
        		break;
        		
        		case 'katalogAccessories':
        			if (Yii::app()->getModule('katalogAccessories')->enabledModule) {
        				$this->data[$item['echo_position']][] = array('type' => 'cache', 'value' => 'katalogAccessories', 'title' => '');
        			}
        		break;
        		case 'katalogVavto':
        			//$this->data[$item['echo_position']][] = array('type' => 'cache', 'value' => 'katalogVavto', 'title' => '');
        		break;
        		case 'katalogTO':
        			if (Yii::app()->getModule('katalogTO')->enabledModule) {
        				$this->data[$item['echo_position']][] = array(
        					'type' => 'link',
        					'value' => array('/katalogTO/katalogTO/brands'),
        					'title' => empty($item['title']) ? Yii::t('menu', 'Catalog service') : $item['title'],
        					'visible' => $item['visible'],
        				);
        			}
        		break;
        		case 'universal':
        			if (Yii::app()->getModule('universal')->enabledModule) {
        				$this->data[$item['echo_position']][] = array(
        					'type' => 'cache', 
        					'value' => 'universal', 
        					'title' => '');
        			}
        		break;
        		
        		case 'feedbback':
        			$this->data[$item['echo_position']][] = array(
        				'type' => 'link',
        				'value' => array('/requests/feedbacks/index'),
        				'title' => empty($item['title']) ? Yii::t('menu', 'Reviews') : $item['title'],
        				'visible' => $item['visible'],
        			);
        		break;
        		case 'login':
        			if (Yii::app()->user->isGuest) {
        				$this->data[$item['echo_position']][] = array(
        					'type' => 'link',
        					'value' => array('/lily/user/login'),
        					'title' => Yii::t('menu', 'Input'),
        					'visible' => '',
        				);
        				$this->data[$item['echo_position']][] = array(
        					'type' => 'link',
        					'value' => array('/userControl/userProfile/registration'),
        					'title' => Yii::t('menu', 'Registration'),
        					'visible' => '',
        				);
        			}
        		break;
        		case 'vin':
        			$this->data[$item['echo_position']][] = array(
        				'type' => 'link',
        				'value' => array('/requests/requestVin/create'),
        				'title' => empty($item['title']) ? Yii::t('menu', 'VIN Requests') : $item['title'],
        				'visible' => $item['visible'],
        			);
        		break;
        		case 'get_price':
        			$this->data[$item['echo_position']][] = array(
        				'type' => 'link',
        				'value' => array('/requests/requestGetPrice/create'),
        				'title' => empty($item['title']) ? Yii::t('menu', 'Requests for prices') : $item['title'],
        				'visible' => $item['visible'],
        			);
        		break;
        		case 'wu':
        			$this->data[$item['echo_position']][] = array(
        				'type' => 'link',
        				'value' => array('/requests/requestWu/create'),
        				'title' => empty($item['title']) ? Yii::t('menu', 'Requests parts used') : $item['title'],
        				'visible' => $item['visible'],
        			);
        		break;
        		case 'user_menu':
        			$this->data[$item['echo_position']][] = array(
        					'type' => 'link',
        					'value' => array('/userControl/userProfile/cabinet'),
        					'visible' => '!Yii::app()->user->isGuest',
        					'title' => Yii::t('menu', 'My account'),
        					'items' => array(
        							array(
        									'type' => 'link',
        									'value' => array('/shop_cart/orders/index'),
        									'visible' => '!Yii::app()->user->isGuest',
        									'title' => Yii::t('menu', 'My orders')),
        									//                        array(
        											//                            'type' => 'link',
        											//                            'value' => array('/shop_cart/items/index'),
        											//                            'visible' => '!Yii::app()->user->isGuest',
        											//                            'title' => 'Лист товаров'),
        							array(
        									'type' => 'link',
        									'value' => array('/userControl/userBalance/index'),
        									'visible' => '!Yii::app()->user->isGuest',
        									'title' => Yii::t('menu', 'Money')),
        							array(
        									'type' => 'link',
        									'value' => array('/webPayments/webPayments/pay'),
        									'visible' => '!Yii::app()->user->isGuest',
        									'title' => Yii::t('menu', 'Add money')),
        							array(
        									'type' => 'link',
        									'value' => array('/lily/account/edit'),
        									'visible' => '!Yii::app()->user->isGuest && Yii::app()->user->checkAccess(\'editEmailAccount\', array(\'uid\' =>Yii::app()->user->id ))',
        									'title' => Yii::t('menu', 'Edit password')),
        							array(
        									'type' => 'link',
        									'value' => array('/userControl/userProfile/update'),
        									'visible' => '!Yii::app()->user->isGuest',
        									'title' => Yii::t('menu', 'Edit profile')),
        							array(
        									'type' => 'link',
        									'value' => array('/userControl/usersCars/index'),
        									'visible' => '!Yii::app()->user->isGuest',
        									'title' => Yii::t('menu', 'My cars')),
        							array(
        									'type' => 'link',
        									'value' => array('/prices/default/downloadPrice'),
        									'visible' => '!Yii::app()->user->isGuest',
        									'title' => Yii::t('menu', 'Download price')),
        							array(
        									'type' => 'link',
        									'value' => array('/userControl/userMessages/index'),
        									'visible' => '!Yii::app()->user->isGuest',
        									'title' => Yii::t('menu', 'Messages')),
        							array(
        									'type' => 'link',
        									'value' => array('/lily/user/logout'),
        									'visible' => '!Yii::app()->user->isGuest',
        									'title' => Yii::t('menu', 'Logout')),
        			));
        		break;
        		default:
	                $this->data[$item['echo_position']][] = array(
	                    'type' => $item['menu_type'],
	                    'value' => $item['menu_value'],
	                    'visible' => $item['visible'],
	                    'title' => $item['title']);
	            break;
            }
        }
    }

    public function getMenu() {
        $this->generateMenuFromDB();
//        if (empty($this->data)) {
//            $pathsMap = Yii::app()->cache->get($this->cacheId);
//            if ($pathsMap == false)
//                $this->generateMenuFromDB();
//            else {
//                $this->data = $pathsMap;
//            }
//        }
        return $this->data;
    }

    /**
     * Сохраняет в кеш актуальную на момент вызова карту путей.
     * @return void
     */
    public function updateMenu() {
        $this->generateMenuFromDB();
        Yii::app()->cache->set($this->cacheId, array());
        Yii::app()->cache->set($this->cacheId, $this->data);
    }

    protected function getDbConnection() {
        if ($this->cache)
            $db = Yii::app()->db->cache($this->cache, $this->dependency);
        else
            $db = Yii::app()->db;

        return $db;
    }
}