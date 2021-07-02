<?php
class ProgramController extends Controller {
    public function login($email, $password) {
//        Yii::log($email . $password);

        function myErrorHandler($errno, $msg, $file, $line) {
            Yii::log("error:<b>$errno</b>!" . "File: <tt>$file</tt>, line $line." . "Text: <i>$msg</i>");
//            echo "error:<b>$errno</b>!\n";
//            echo "File: <tt>$file</tt>, line $line.\n";
//            echo "Text: <i>$msg</i>\n";
        }

        set_error_handler("myErrorHandler", E_ALL);

        $authIdentity = Yii::app()->eauth->getIdentity('email');
        /* @var $authIdentity LEmailService */
        $authIdentity->redirectUrl = Yii::app()->user->returnUrl;
        $authIdentity->cancelUrl = $this->createAbsoluteUrl('user/login');

        $authIdentity->email = $email;
        $authIdentity->password = $password;
        $authIdentity->rememberMe = false;
        $auth = Yii::app()->AuthManager;
        //Authentication succeed
        if ($authIdentity->authenticate()) {
//            Yii::log('dd1','error');
//            Yii::log($authIdentity->getId() . 'uid1', CLogger::LEVEL_INFO, 'parsersApi');
            $identity = new LUserIdentity($authIdentity);
//            Yii::log($auth->isAssigned('admin', $identity->getId()). 'uid2' . Yii::app()->user->checkAccess('admin', $identity->getId()), CLogger::LEVEL_INFO, 'parsersApi');
    //Yii::log('dd2','error');
            if ($identity->authenticate() && $auth->isAssigned('admin', $identity->getId())) {
//                Yii::log(3);
//                Yii::log($identity->getId() . 'uid', CLogger::LEVEL_ERROR, 'parsersApi');
//                Yii::log('dd','error');
                return true;
            }
        }
        return false;
    }

    public function actionGetCsrf() {
        header('Content-type: application/json');
        echo CJSON::encode(array('name' => Yii::app()->request->csrfTokenName, 'value' => Yii::app()->request->csrfToken));
    }

    public function actionLogin() {
//        $temp = '';
//
//        foreach ($_POST as $key => $value)
//            $temp.=$key . '-' . $value . "\n";
//
//        Yii::log($temp, CLogger::LEVEL_INFO, 'parsersApi');
//
//        die;
//        Yii::log('1');
        if (isset($_POST['login']) && isset($_POST['password']) && $this->login($_POST['login'], $_POST['password'])) {
//            Yii::log('success');
            $msg = 'success';
        } else {
//            Yii::log('fail');
            $msg = 'fail';
        }
//        Yii::log('2');
//        Yii::log($msg);
        header('Content-type: application/json');
        echo CJSON::encode(array('message' => $msg));
    }

    public function actionGetData() {
        $stores = array();
        $currencies = array();
        $prices_groups = array();
        $msg = 'fail';
        if (isset($_POST['login']) && isset($_POST['password']) && $this->login($_POST['login'], $_POST['password'])) {
            $msg = 'success';
            $db = Yii::app()->db;

            $stores = $db->createCommand('SELECT id, name FROM ' . Stores::model()->tableName())->queryAll();
            $currencies = $db->createCommand('SELECT id, name FROM ' . Currencies::model()->tableName())->queryAll();
            $prices_groups = $db->createCommand('SELECT id, name FROM ' . PricesRulesGroups::model()->tableName())->queryAll();
        }
//  Yii::log($msg);
        header('Content-type: application/json');
        echo CJSON::encode(array('message' => $msg, 'stores' => $stores, 'currencies' => $currencies, 'prices_groups' => $prices_groups));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUploadPrice() {
        $msg = 'fail';
        $_POST['jPrices'] = CJSON::decode($_POST['jPrices'], true);
        $_POST['Prices']['name'] = $_POST['jPrices']['name'];
        $_FILES['Prices']['name']['priceFile'] = '1.txt';
//        foreach ($_POST as $k => $v) {
//            Yii::log($k . '-' . $v);
//            if (is_array($v))
//                foreach ($v as $k2 => $v2) {
//
//                    Yii::log($k . '-' . $k2 . '-' . $v2);
//                }
//        }
        if (isset($_POST['login']) && isset($_POST['password']) && $this->login($_POST['login'], $_POST['password'])) {
            $model = NULL;
            if (isset($_POST['price_id'])) {
                $model = Prices::model()->findByPk(trim($_POST['price_id']));
                Yii::log($_POST['price_id'], 'warning');
                $model->scenario = 'create';
                if (isset($_POST['Prices'])) {
                    $model->attributes = $_POST['Prices'];
                    $model->isProgramWork = true;
                    if (isset($_POST['clear']) && $_POST['clear'] == 1) {
                        $model->isClear = true;
                    }
                    if ($model->saveFile == NULL) {
//                        $temp = CUploadedFile::getInstance($model, 'priceFile');
                        set_time_limit(0);
                        if (($model->saveFile == null && $model->scenario != '1c') && ($model->priceFile != NULL || true)) {
                            $model->priceFile = CUploadedFile::getInstance($model, 'priceFile');
                            $filename = pathinfo($model->priceFile->getName());
                            $extension = $filename['extension'];

                            $filename = md5(time()) . '.' . $extension;
                            $model->priceFile->saveAs(Yii::app()->getModule('prices')->pathFiles . $filename);

                            if ($extension == 'xls') {
                                $model->importXLS(Yii::app()->getModule('prices')->pathFiles . $filename);
                            } else {
                                $model->importTXT(Yii::app()->getModule('prices')->pathFiles . $filename);
                            }
                            unlink(Yii::app()->getModule('prices')->pathFiles . $filename
                            );
                        } else if ($model->saveFile != null) {
                            $model->importTXT($model->saveFile);
                        }
                        $msg = $model->id;
                    }
                }
            } else {
                $model = new Prices('create');

                if (isset($_POST['Prices'])) {
                    $model->attributes = $_POST['Prices'];
                    $m = Stores::model()->findByPk($model->store_id);
                    if ($m != NULL) {
                        $model->supplier_inn = $m->supplier_inn;
                        $model->supplier = $m->supplier;
                    }
                    $model->isProgramWork = true;
                    if (isset($_POST['clear']) && $_POST['clear'] == 1) {
                        $model->isClear = true;
                    }
                    if ($model->save()) {
                        $msg = $model->id;
//                    $msg = 'success';
                    }
//                foreach ($model->errors as $k => $v) {
//                    foreach ($v as $k2 => $v2) {
//
//                        Yii::log($k . '-' . $k2 . '-' . $v2);
//                    }
//                }
                }
            }

            header('Content-type: application/json');
            echo CJSON::encode(array('message' => $msg));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    /*public function actionUploadCrosses() {
        $msg = 'fail';
        $_POST['jCrosses'] = CJSON::decode($_POST['jCrosses'], true);
        $_POST['Crosses']['name'] = $_POST['jCrosses']['name'];
        $_FILES['Crosses']['name']['crossFile'] = '1.txt';
        if (isset($_POST['login']) && isset($_POST['password']) && $this->login($_POST['login'], $_POST['password'])) {
            $model = new Crosses('create');
            if (isset($_POST['Crosses'])) {
                $model->attributes = $_POST['Crosses'];
                $model->isProgramWork = true;
                if ($model->save()) {
                    $msg = 'success';
                }
            }
        }

        header('Content-type: application/json');
        echo CJSON::encode(array('message' => $msg));
    }*/
    
    public function actionUploadCrosses() {
    	$msg = 'fail';
    	$_POST['jCrosses'] = CJSON::decode($_POST['jCrosses'], true);
    	$_POST['Crosses']['name'] = $_POST['jCrosses']['name'];
    	$_FILES['Crosses']['name']['crossFile'] = '1.txt';
    
    	if (isset($_POST['login']) && isset($_POST['password']) && $this->login($_POST['login'], $_POST['password'])) {
    		$model = new Crosses('create');
    		if (isset($_POST['Crosses'])) {
    			/*ob_start();
    			echo '<pre>'; print_r($_POST); print_r($_FILES); echo '</pre>';
    			file_put_contents(Yii::getPathOfAlias('webroot').'/cross_error.txt', ob_get_clean(), FILE_APPEND);*/
    			
    			//$_FILES['Crosses'] = $_FILES['Crosses'];
    			
    			$model->attributes = $_POST['Crosses'];
    			$model->isProgramWork = true;
    
    			//Грузим в первую базу
    			$base = CrossesBase::model()->find(array('order' => 'id ASC'));
    			if (is_object($base)) {
    				$model->base_id = $base->primaryKey;
    				$model->create_date = time();
    				
    				/*ob_start();
    				echo '<pre>'; print_r($model); echo '</pre>';
    				file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/2.txt', ob_get_clean(), FILE_APPEND);*/
    				
    				$model->crossCharset = 'ASCII';
    				
    				if ($model->save()) {
    					$msg = 'success';
    				} else {
    					ob_start();
    					echo '<pre>'; print_r($model->getErrors()); echo '</pre>';
    					file_put_contents(Yii::getPathOfAlias('webroot').'/cross_error.txt', ob_get_clean(), FILE_APPEND);
    				}
    			}
    		}
    	}
    
    	header('Content-type: application/json');
    	echo CJSON::encode(array('message' => $msg));
    }
}