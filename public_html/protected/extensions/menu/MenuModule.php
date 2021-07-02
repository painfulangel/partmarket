<?php
class MenuModule extends CWebModule {
    public $typeList = array(
        'link' => 'Ссылка',
        'pages_top' => 'Верхние страницы',
        'pages_left' => 'Левые страницы',
        //'user_menu'srch_form' => 'Форма поиска',
        'user_menu' => 'Меню пользователя',
        'vin' => 'VIN Запрос',
        'wu' => 'Запрос на запчасти БУ',
        'get_price' => 'Запросы на цены',
        'katalogAccessories' => 'Каталог аксессуаров',
        'katalogTO' => 'Каталог ТО',
    	'universal' => 'Универсальный каталог',
		//'katalogVavto' => 'Каталог собственный',
        'feedbback' => 'Отзывы',
        'login' => 'Вход/Регистрация',
    );
    
    public $positionList = array('top' => 'Верхнее', 'left' => 'Левое');

    public function getPosition($id) {
        return $this->positionList[$id];
    }

    public function getType($id) {
        return $this->typeList[$id];
    }

    public function init() {
        $this->typeList = array(
            'link' => Yii::t('menu', 'Links'),
            'pages_top' => Yii::t('menu', 'Top pages'),
            'pages_left' => Yii::t('menu', 'Left pages'),
            //'user_menu'srch_form' => 'Форма поиска',
            'user_menu' => Yii::t('menu', 'My account'),
            'vin' => Yii::t('menu', 'VIN Requests'),
            'wu' => Yii::t('menu', 'Requests parts used'),
            'get_price' => Yii::t('menu', 'Requests for prices'),
            'katalogAccessories' => Yii::t('menu', 'Catalog accessory'),
            'katalogTO' => Yii::t('menu', 'Catalog service'),
    		'universal' => Yii::t('universal', 'Universal catalog'),
			//'katalogVavto' => 'Каталог собственный',
            'feedbback' => Yii::t('menu', 'Reviews'),
            'login' => Yii::t('menu', 'Login/Registration'),
        );
        $this->positionList = array('top' => Yii::t('menu', 'Top'), 'left' => Yii::t('menu', 'Left'));


        if (!Yii::app()->getModule('katalogAccessories')->enabledModule) {
            unset($this->typeList['katalogAccessories']);
        }
        
        if (!Yii::app()->getModule('katalogTO')->enabledModule) {
            unset($this->typeList['katalogTO']);
        }
        
        if (!Yii::app()->getModule('universal')->enabledModule) {
        	unset($this->typeList['universal']);
        }
        
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'menu.models.*',
            'menu.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }
}