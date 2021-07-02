<?php
class CarsController extends Controller {
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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('view', 'index'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);

        $type_ru = '';
        $subtype_ru = '';

        //Если получен тип
        $type = trim(Yii::app()->request->getParam('type', ''));
        if ($type) {
            $item = KatalogVavtoItems::model()->findByAttributes(array('detail_type_slug' => $type));
            if (is_object($item)) {
                $type_ru = $item->detail_type;
            } else {
                throw new CHttpException(404, Yii::t('katalogVavto', 'This Page not found.'));
            }
        }
        //или подтип
        $subtype = trim(Yii::app()->request->getParam('subtype', ''));
        if ($subtype) {
            $item = KatalogVavtoItems::model()->findByAttributes(array('detail_subtype_slug' => $subtype));
            if (is_object($item)) {
                $subtype_ru = $item->detail_subtype;
            } else {
                throw new CHttpException(404, Yii::t('katalogVavto', 'This Page not found.'));
            }
        }

        //Перечень категорий, встречающихся у товаров заданной модели
        $categories = array();

        $criteria = new CDbCriteria;
        $criteria->compare('cathegory_id', $id);
        $criteria->distinct = true;
        if ($type) {
            $criteria->select = 'detail_subtype, detail_subtype_slug';
            $criteria->compare('detail_type_slug', $type);
            $criteria->order = 'detail_subtype ASC';
        } else {
            $criteria->select = 'detail_type, detail_type_slug';
            $criteria->order = 'detail_type ASC';
        }

        /*if (array_key_exists('i', $_GET)) {
            echo '<pre>'; print_r($criteria->toArray()); echo '</pre>'; exit;
        }*/

        $data = KatalogVavtoItems::model()->findAll($criteria);
        $count = count($data);
        for ($i = 0; $i < $count; $i ++) {
            $categories[$type ? $data[$i]->detail_subtype_slug : $data[$i]->detail_type_slug] = $type ? $data[$i]->detail_subtype : $data[$i]->detail_type;
        }

        $model2 = new KatalogVavtoItems('search');
        $model2->unsetAttributes();
        $model2->cathegory_id = $model->id;
        if ($type) $model2->detail_type_slug = $type;
        if ($subtype) $model2->detail_subtype_slug = $subtype;

        $this->render('view', array(
            'model'      => $model,
            'model2'     => $model2,
            'categories' => $categories,
            'type'       => $type,
            'subtype'    => $subtype,
            'type_ru'    => $type_ru,
            'subtype_ru' => $subtype_ru,
            'path'       => '/'.Yii::app()->request->getPathInfo()
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = KatalogVavtoCars::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('katalogVavto', 'This Page not found.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'katalog-accessories-cathegorias-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function createUrl($route,$params=array(),$ampersand='&')
    {
        if($route==='')
            $route=$this->getId().'/'.$this->getAction()->getId();
        elseif(strpos($route,'/')===false)
            $route=$this->getId().'/'.$route;
        if($route[0]!=='/' && ($module=$this->getModule())!==null)
            $route=$module->getId().'/'.$route;

        $type = '';
        $subtype = '';

        if (array_key_exists('type', $params)) {
            $type = $params['type'];
            unset($params['type']);
        }

        if (array_key_exists('subtype', $params)) {
            $subtype = $params['subtype'];
            unset($params['subtype']);
        }

        $url = Yii::app()->createUrl(trim($route,'/'), $params, $ampersand);

        if ($type != '') {
            if (strpos($url, '?') !== false) {
                $parts = explode('?', $url);
                $url = $parts[0].'type-'.$type.'/?'.$parts[1];
            } else {
                $url .= 'type-'.$type.'/';
            }
        }

        if ($subtype != '') {
            if (strpos($url, '?') !== false) {
                $parts = explode('?', $url);
                $url = $parts[0].'subtype-'.$subtype.'/?'.$parts[1];
            } else {
                $url .= 'subtype-'.$subtype.'/';
            }
        }

        return $url;
    }
}
