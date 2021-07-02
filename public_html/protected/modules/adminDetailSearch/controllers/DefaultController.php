<?php

class DefaultController extends Controller {

    public function actionJsMainScript() {
        $this->renderPartial('js_main');
    }

    public function actionSearch($search_phrase = '') {
//        die;
        $this->layout = '//layouts/admin_column2';
        $p = new CHtmlPurifier();
        $p->options = array('URI.AllowedSchemes' => array(
                'http' => true,
                'https' => true,
        ));
        $search_phrase = $p->purify($search_phrase);

//        die;
        $this->render('search', array('search_phrase' => $search_phrase));
    }

    public function actionGetSkladList() {
        $db = Yii::app()->db;
        $time_start = $_GET['time_start'];
        $className = Yii::app()->controller->module->modelClass;
        $model = new $className;
        $tableName = $model->tableName();
        $activeName = Yii::app()->controller->module->activeName;
        $sql = "SELECT `id` FROM `$tableName` WHERE   `$activeName`='1'";
        $data = $db->createCommand($sql)->queryAll();
        $sklad_list = array(0 => 'local');
//        $sklad_list = array();

        foreach ($data as $row) {
            $sklad_list[] = $row['id'];
        }
        $className = Yii::app()->controller->module->apiModelClass;
        $model = new $className;
        $tableName = $model->tableName();
        $activeName = Yii::app()->controller->module->activeName;
        $sql = "SELECT `id` FROM `$tableName` WHERE  `admin_active_state`='1' AND `$activeName`='1'";
        $data = $db->createCommand($sql)->queryAll();
        foreach ($data as $row) {
            $sklad_list[] = 'api_' . $row['id'];
        }
        header('Content-type: application/json');
        echo CJSON::encode(array('time_start' => $time_start, 'sklads_count' => count($sklad_list), 'sklads' => $sklad_list));
    }

    public function actionGetProductList() {
//        throw new CHttpException;
//        throw new Exception;
        set_time_limit(300);
        $time_start = $_GET['time_start'];
        $search_sklad = $_GET['search_sklad'];
        $search_phrase = $_GET['search_phrase'];
        $search_phrase = str_replace(array("\s", ' '), '', $search_phrase);
        $search_phrases = explode(',', $search_phrase);
        $full_list = $search_phrases;
        $search_phrases = array_flip($search_phrases);
//        print_r($search_phrases);
//        die;
        $products = array();
        $products_other = array();
        if (strpos('!!' . $search_sklad, 'local') || strpos('!!' . $search_sklad, 'api')) {
//        if (strpos('!!' . $search_sklad, 'local')) {
            foreach ($full_list as $k => $v) {
                $v = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $v));
                if (!empty($v)) {
                    $full_list[$k] = $v;
                } else {
                    unset($full_list[$k]);
                }
            }
            if (count($full_list) > 0) {
                $model = $this->loadModel($search_sklad);
                $res = $model->getData($full_list, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => $search_sklad));
                foreach ($res as $key => $value) {
                    $temp = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $value['articul']));
                    if (isset($search_phrases[$temp])) {
                        $products[] = $value;
                        //$products[] = array('search_sklad' => $search_sklad, 'name' => $value[2], 'articul' => $value[1], 'articul_order' => $value[1], 'kolichestvo' => $value[4], 'dostavka' => $value[5] + $sklad['dost'], 'price' => ($value[6]), 'brand' => $value[3], 'sklad' => $sklad['id'], 'skladnum' => $sklad['id'], 'skladnnum' => $search_sklad);
                    } else {
                        $products_other[] = $value;
                        //$products_other[] = array('search_sklad' => $search_sklad, 'name' => $value[2], 'articul' => $value[1], 'articul_order' => $value[1], 'kolichestvo' => $value[4], 'dostavka' => $value[5] + $sklad['dost'], 'price' => ($value[6]), 'brand' => $value[3], 'sklad' => $sklad['id'], 'skladnum' => $sklad['id'], 'skladnnum' => $search_sklad, 'parent_articul' => $search_phrase);
                    }
                }
            }
        } else
            foreach ($search_phrases as $search_phrase => $temp_search_phrase) {
//            echo $search_phrase . '<br>';
                $search_phrase = preg_replace("/[^a-zA-Z0-9]/", "", $search_phrase);
                $search_phrase = strtoupper($search_phrase);
                if (!empty($search_phrase)) {
                    $model = $this->loadModel($search_sklad);
                    $res = $model->getData($search_phrase, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => $search_sklad));
                    foreach ($res as $key => $value) {
                        $temp = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", $value['articul']));
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

        echo CJSON::encode(array('time_start' => $time_start, 'products_count' => count($products), 'products' => $products, 'products_other_count' => count($products_other), 'products_other' => $products_other));
    }

    public function loadModel($id) {
        if ($id == 'local') {
            $temp = Yii::app()->controller->module->localPriceSearchClass;
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
            throw new CHttpException(404, Yii::t('adminDetailSearch', 'This page doesn\'t exist.'));
        // $temp = new $model->{Yii::app()->controller->module->parserClassName};
        $temp->model = $model;
        return $temp;
    }

}
