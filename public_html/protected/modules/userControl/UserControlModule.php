<?php
class UserControlModule extends CWebModule {
    public $_images;
    public $_css;
    public $_js;

    public function init() {
        $assetsDir = dirname(__FILE__) . "/assets";
        
        if (isset(Yii::app()->assetManager) && is_object(Yii::app()->assetManager)) {
	        $this->_images = Yii::app()->assetManager->publish($assetsDir);
	        $this->_css = $this->_images . '/css';
            $this->_js = $this->_images . '/js';
        	$this->_images .= '/images';
        }
        
        if (isset(Yii::app()->clientScript) && is_object(Yii::app()->clientScript)) {
        	Yii::app()->clientScript->registerCssFile($this->_css . '/main.css');
            Yii::app()->clientScript->registerScriptFile($this->_js."/userControl.js");
        }
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'userControl.models.*',
            'userControl.components.*',
        ));
    }

    public function getCurrentUserModel() {
        if (!Yii::app()->user->isGuest)
            $model = UserProfile::model()->findByAttributes(array('uid' => Yii::app()->user->id));
        if (Yii::app()->user->isGuest || $model == NULL)
            return new UserProfile;
        else
            return $model;
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }

    public function getAsUserBlock() {
        if (Yii::app()->user->checkAccess('UserNameOrder')) {
            $temp = Yii::app()->user->getState('UserOrderId');
            if (!empty($temp)) {
                $model = UserProfile::model()->findByAttributes(array('uid' => $temp));
                return array(
                    'label' => 'Зашли от пользователя ' . $model->getFullName().' (Нажмите чтобы выйти) ',
                    'itemOptions' => array('class' => 'nav-header'),
                    'url'=>'/userControl/adminUserProfile/logoutAsUser',
                );
            }
        }
        return '';
    }

    public function getUserLoginName() {
        if (!Yii::app()->user->isGuest) {
            $temp = Yii::app()->user->id;
            if (!empty($temp)) {
                $model = UserProfile::model()->findByAttributes(array('uid' => $temp));
                return '' . ($model != NULL ? $model->fullName : ' Личный кабинет');
            }
        }
        return '';
    }

    public function getTopUserBlock() {
        if (!Yii::app()->user->isGuest) {
            $temp = Yii::app()->user->id;
            if (!empty($temp)) {
                $model = UserProfile::model()->findByAttributes(array('uid' => $temp));
                return CHtml::link('Выйти', '/lily/user/logout') . CHtml::link('' . ($model != NULL ? $model->first_name : ' Личный кабинет') . '', '/userControl/userProfile/cabinet');
            }
        }
        return '';
    }
    
    public function unreadMessages() {
    	$count = Yii::app()->db->createCommand()
    	->select('COUNT(*) AS count')
    	->from('user_message u1')
    	->join('user_message_dialog u2', 'u1.user_dialog_id = u2.id')
    	->where('u1.user_id = u2.user_id AND readed_admin = 0')
    	->queryScalar();
    	
    	return intval($count) > 0;
    }
    
    public function userUnreadMessages() {
    	if (Yii::app()->user->checkAccess('admin')) {
    		return $this->unreadMessages();
    	} else {
	    	$count = Yii::app()->db->createCommand()
	    	->select('COUNT(*) AS count')
	    	->from('user_message u1')
	    	->join('user_message_dialog u2', 'u1.user_dialog_id = u2.id')
	    	->where('u1.user_id != u2.user_id AND readed_user = 0')
	    	->queryScalar();
	    	
	    	return intval($count) > 0;
    	}
    }
}