<?php
class PricegroupsModule extends CWebModule {
    public $data = array();
    public $coef = 1;

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'pricegroups.models.*',
            'pricegroups.components.*',
        ));
    }

    public function loadData($id) {
        $db = Yii::app()->db;
        $data = $db->createCommand('SELECT * FROM `' . PricesRules::model()->tableName() . '` WHERE group_id=\'' . $id . '\' ORDER BY `top_value` DESC ')->queryAll();
        $this->data[$id] = array();
        foreach ($data as $value) {
            $this->data[$id][$value['brand']][$value['top_value']] = $value['koeficient'];
        }


        //В зависимости от города
        $city = Cities::getInfo();
        if (is_object($city) && $city->coef) $this->coef = (float) $city->coef;
        //В зависимости от города
    }

    public function getPrice($price, $group, $brand = '0') {
        if (!isset($this->data[$group])) {
            $this->loadData($group);
        }
        if (!isset($this->data[$group])) {
            return round($price, 2) * $this->coef;
        }
        if (isset($this->data[$group][$brand])) {
            $data = $this->data[$group][$brand];
        } else {
            if (!isset($this->data[$group][0])) {
                return round($price, 2) * $this->coef;
            }
            $data = $this->data[$group][0];
        }
        
        $prev = 1;
        if (isset($data['0']))
            $prev = $data['0'];
        foreach ($data as $key => $value) {
            if ((float) $key < (float) $price) {
                return round($prev * $price, 2) * $this->coef;
            }
            $prev = $value;
        }

        return round($prev * $price, 2) * $this->coef;
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }

    public function getUserGroup() {
        if (!Yii::app()->user->isGuest) {
            $model = UserProfile::model()->findByAttributes(array('uid' => UserProfile::getUserActiveId()));
            if ($model != NULL) {
                return $model->price_group;
            }
        }
        return 1;
    }
}