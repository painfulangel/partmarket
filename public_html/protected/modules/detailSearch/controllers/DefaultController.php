<?php
class DefaultController extends Controller {
    public function actionJsMainScript() {
        $this->renderPartial('js_main');
    }

    public function actionSearch($search_phrase = '') {
    	if (Yii::app()->config->get('Site.SearchType') == 1) {
    		$this->redirect(array('/detailSearchNew/default/search', 'article' => $search_phrase));
    	}
    	
        $p = new CHtmlPurifier();
        $p->setOptions(array('URI.AllowedSchemes' => array(
                'http' => true,
                'https' => true,
        )));
        
        $search_phrase = $p->purify($search_phrase);

        $this->render('search', array('search_phrase' => htmlspecialchars($search_phrase, ENT_QUOTES)));
    }

    public function actionGetSkladList() {
        set_time_limit(300);
        $timelimit = Yii::app()->config->get("Site.DetailSearchTimeout");
        $timelimit = preg_replace("/[^0-9]/", "", $timelimit);
        if (!empty($timelimit))
            set_time_limit($timelimit);
//                Yii::beginProfile('blockId');
        $db = Yii::app()->db;
        $time_start = $_GET['time_start'];

        $search_phrase = $_GET['search_phrase'];
        $search_phrase = str_replace(array("\s", ' '), '', $search_phrase);
        $search_phrases = explode(',', $search_phrase);
//        $search_phrases = array_flip($search_phrases);
        $temp = Yii::app()->controller->module->localMyPriceSearchClass;
        $my = new $temp;
        $flag = false;
        foreach ($search_phrases as $search_phrase) {
            if ($my->checkMyAvailable($search_phrase)) {
                $flag = true;
            }
        }

        $sklad_list = array();

//        throw new CHttpException;


        if ($flag) {
            $sklad_list = array('local_my');
        } else {
            $activeName = Yii::app()->controller->module->activeName;

            $criteria = (Yii::app()->language == Yii::app()->params['default_language'] ? ' `language`=\'0\' ' : ' `language`=\''.Yii::app()->language.'\' ');
//            if (Yii::app()->user->checkAccess('mainManager') || Yii::app()->user->checkAccess('admin')) {
//                $criteria = 1;
//            }

            $sql = "SELECT `id` FROM `parsers` WHERE `$activeName`='1' AND (`language`='' OR $criteria)";
            $data = $db->createCommand($sql)->queryAll();
            $sklad_list = array(0 => 'local');
//        $sklad_list = array();

            foreach ($data as $row) {
                $sklad_list[] = $row['id'];
            }
//            $className = Yii::app()->controller->module->apiModelClass;
//            $model = new $className;
//            $tableName = $model->tableName();
            $activeName = Yii::app()->controller->module->activeName;
            $sql = "SELECT `id` FROM `parsers_api` WHERE "./*"`$activeName`='1' AND ".*/"`admin_active_state`='1' AND (`language`='' OR $criteria) ";
            $data = $db->createCommand($sql)->queryAll();
            foreach ($data as $row) {
                $sklad_list[] = 'api_'.$row['id'];
            }
        }
//            Yii::endProfile('blockId');
        header('Content-type: application/json');
        echo CJSON::encode(array('time_start' => $time_start, 'sklads_count' => count($sklad_list), 'sklads' => $sklad_list));
    }

    private function getHiddenBrands() {
        $hidden = array();

        $hide = Brands::model()->findAll(array('condition' => 'active_state = 1 AND hide = 1', 'select' => 'name, synonym'));

        $count = count($hide);
        for ($i = 0; $i < $count; $i ++) {
            $name = mb_strtolower(trim($hide[$i]->name));

            if (!in_array($name, $hidden)) $hidden[] = $name;

            $synonym = array_map('mb_strtolower', array_map('trim', explode(',', $hide[$i]->synonym)));

            $count2 = count($synonym);
            for ($j = 0; $j < $count2; $j ++) {
                $name = mb_strtolower(trim($synonym[$j]));

                if (!in_array($name, $hidden)) $hidden[] = $name;
            }
        }

        return $hidden;
    }
    
    public function actionGetProductList() {
		//throw new CHttpException;
		//throw new Exception;

        set_time_limit(300);
        $timelimit = Yii::app()->config->get("Site.DetailSearchTimeout");
        $timelimit = preg_replace("/[^0-9]/", "", $timelimit);
        if (!empty($timelimit))
            set_time_limit($timelimit);
        $time_start = $_GET['time_start'];
        $search_sklad = $_GET['search_sklad'];
        $search_phrase = $_GET['search_phrase'];
        $search_phrase = str_replace(array("\s", ' '), '', $search_phrase);
        $search_phrases = explode(',', $search_phrase);
        $full_list = $search_phrases;
        $search_phrases = array_flip($search_phrases);
		//print_r($search_phrases);
		//die;
        $products = array();
        $products_other = array();

        $new_list = array();

        $hidden = $this->getHiddenBrands();
        
        if (strpos('!!'.$search_sklad, 'local') || strpos('!!'.$search_sklad, 'api')) {
            foreach ($full_list as $k => $v) {
                $v = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $v));
                if (!empty($v)) {
                    $full_list[$k] = $v;
                    if ($v[0] == '0') {
                        $new_list[] = substr($v, 1);
                    }
                } else {
                    unset($full_list[$k]);
                }
            }
            foreach ($new_list as $temp_art) {
                $full_list[] = $temp_art;
            }

            if (count($full_list) > 0) {
                $model = $this->loadModel($search_sklad);
                
                //echo $search_sklad.' - '.get_class($model); exit;
                
                $res = $model->getData($full_list, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => $search_sklad));
                foreach ($res as $key => $value) {
                    $temp = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $value['articul']));

                    $brand = mb_strtolower(trim($value['brand']));

                    if (!in_array($brand, $hidden)) {
                        if (isset($search_phrases[$temp])) {
                            $products[] = $value;
                            //$products[] = array('search_sklad' => $search_sklad, 'name' => $value[2], 'articul' => $value[1], 'articul_order' => $value[1], 'kolichestvo' => $value[4], 'dostavka' => $value[5] + $sklad['dost'], 'price' => ($value[6]), 'brand' => $value[3], 'sklad' => $sklad['id'], 'skladnum' => $sklad['id'], 'skladnnum' => $search_sklad);
                        } else {
                            $products_other[] = $value;
                            //$products_other[] = array('search_sklad' => $search_sklad, 'name' => $value[2], 'articul' => $value[1], 'articul_order' => $value[1], 'kolichestvo' => $value[4], 'dostavka' => $value[5] + $sklad['dost'], 'price' => ($value[6]), 'brand' => $value[3], 'sklad' => $sklad['id'], 'skladnum' => $sklad['id'], 'skladnnum' => $search_sklad, 'parent_articul' => $search_phrase);
                        }
                    }
                }
            }
        } else
            foreach ($search_phrases as $search_phrase => $temp_search_phrase) {
                $search_phrase = preg_replace("/[^a-zA-Z0-9]/", "", $search_phrase);
                $search_phrase = strtoupper($search_phrase);
                if (!empty($search_phrase)) {
                    $model = $this->loadModel($search_sklad);
                    $res = $model->getData($search_phrase, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => $search_sklad));
                    foreach ($res as $key => $value) {
                        $temp = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $value['articul']));
                        $brand = mb_strtolower(trim($value['brand']));

                        if (!in_array($brand, $hidden)) {
                            if (isset($search_phrases[$temp])) {
                                $products[] = $value;
                                //$products[] = array('search_sklad' => $search_sklad, 'name' => $value[2], 'articul' => $value[1], 'articul_order' => $value[1], 'kolichestvo' => $value[4], 'dostavka' => $value[5] + $sklad['dost'], 'price' => ($value[6]), 'brand' => $value[3], 'sklad' => $sklad['id'], 'skladnum' => $sklad['id'], 'skladnnum' => $search_sklad);
                            } else {
                                $products_other[] = $value;
                                //$products_other[] = array('search_sklad' => $search_sklad, 'name' => $value[2], 'articul' => $value[1], 'articul_order' => $value[1], 'kolichestvo' => $value[4], 'dostavka' => $value[5] + $sklad['dost'], 'price' => ($value[6]), 'brand' => $value[3], 'sklad' => $sklad['id'], 'skladnum' => $sklad['id'], 'skladnnum' => $search_sklad, 'parent_articul' => $search_phrase);
                            }
                        }
                    }
                }
            }
        header('Content-type: application/json');
        echo CJSON::encode(array('time_start' => $time_start, 'products_count' => count($products), 'products' => $products, 'products_other_count' => count($products_other), 'products_other' => $products_other));
    }

    public function loadModel($id) {
        if ($id == 'local') {
            $temp = Yii::app()->controller->module->localPriceSearchClass;
            return new $temp;
        }
        if ($id == 'local_my') {
            $temp = Yii::app()->controller->module->localMyPriceSearchClass;
            return new $temp;
        }

        //$model = null;
        if (str_replace('api_', '', $id) != $id) {
            $model = CActiveRecord::model(Yii::app()->controller->module->apiModelClass)->findByPk(str_replace('api_', '', $id));
            $temp = new ParserApiSearchModel;
        } else {
            $model = CActiveRecord::model(Yii::app()->controller->module->modelClass)->findByPk($id);
            $temp = new $model->{Yii::app()->controller->module->parserClassName};
        }
        if ($model == null)
            throw new CHttpException(404, Yii::t('detailSearch', 'This page doesn\'t exist.'));
        // $temp = new $model->{Yii::app()->controller->module->parserClassName};
        $temp->model = $model;
        return $temp;
    }
}