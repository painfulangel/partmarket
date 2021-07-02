<?php

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $onlyBrands = array();

        if (isset($_GET['search_phrase']) && isset($_GET['brand'])) {

            /**
             * **************************************
             * Получаем детали по артикулу и бренду
             * **************************************
             */
            $params = array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup());
            /**
             * Создать объект модели LocalSearch()
             */
            $model = $this->loadLocalModel();

            $result = $model->getDetail($_GET['search_phrase'], $_GET['brand'], $params);

            $this->render('result', array(
                'result'=>$result,
                //'brands'=>$brands,
            ));

        }

        $this->render('index', array(
            //'model'=>$model,
            //'brands'=>$brands,
        ));

    }

    public function actionBrands()
    {
        $onlyBrands = array();

        if (isset($_GET['search_phrase'])) {
            /**
             * проверяется, хранится ли искомые артикул на складах с галочкой "Моё наличие".
             * Если хранится, выдаются результаты только с этих складов.
             */
            $store_list = $this->getStoreLists();

            //Бренды
            $brands = array();


            /**
             * Обработка поискового запроса
             */
            $search_phrases = $this->handlingPhrase();
            //echo CVarDumper::dump($search_phrases,10,true);Yii::app()->end();
            foreach ($store_list as $list) {

                if (strpos('!!' . $list, 'local')) {

                    /**
                     * Создать объект модели LocalSearch()
                     */
                    $model = $this->loadLocalModel();

                    $article_search = $search_phrases;

                    /**
                     * @todo получить бренды сначала
                     */
                    $brandsLocal = $model->getBrands($article_search);

                    //echo CVarDumper::dump($brandsLocal,10,true);Yii::app()->end();

                    if (count($brandsLocal) > 1) {
                        /**
                         * Если брендов много, смержить массивы
                         */
                        $onlyBrands = array_merge($onlyBrands, $brandsLocal);
                    } else {
                        $onlyBrands = array_merge($onlyBrands, $brandsLocal);
                    }

                    //echo CVarDumper::dump($brands,10,true);
                } else if (strpos('!!' . $list, 'api')) {
                    $model = $this->loadModel($list);
                    //ParserApiSearchModel
                    $brandsApiData = $model->getBrandData($search_phrases);

                    $brandsApi = array();
                    foreach ($brandsApiData as $item) {
                        $brandsApi[] = $item['brand_link'];
                    }

                    $brandsApi = array_unique($brandsApi);

                    if ($brandsApi > 1) {
                        $onlyBrands = array_merge($onlyBrands, $brandsApi);
                    }
                }
            }

            $onlyBrands = array_unique($onlyBrands);
        }

        if (count($onlyBrands) > 1) {
            //echo CVarDumper::dump($onlyBrands,10, true);
            $this->render('brands', array(
                //'model'=>$model,
                'brands' => $onlyBrands,
                'query'  => isset($_GET['search_phrase']) ? $_GET['search_phrase'] : ''
            ));
        } else {
            /**
             * Если бренд только один, то перейти к поиску детали по артикулу и бренду
             */
            $this->redirect('/newSearch?search_phrase=' . $_GET['search_phrase'].'&brand='.$onlyBrands[0]);
        }
    }


    public function loadModel($id)
    {
        if ($id == 'local') {
            //класс LocalSearch
            $className = Yii::app()->controller->module->localPriceSearchClass;
            return new $className;
        }
        if ($id == 'local_my') {
            //MyLocalSearchModel
            $className = Yii::app()->controller->module->localMyPriceSearchClass;
            return new $className;
        }

        //echo CVarDumper::dump($id,10,true);

        if (str_replace('api_', '', $id) != $id) {
            //ParsersApi
            $model = CActiveRecord::model(Yii::app()->controller->module->apiModelClass)->findByPk(str_replace('api_', '', $id));
            //echo CVarDumper::dump($model,10,true);
            $temp = new ParserApiSearchModel;
        } else {
            //Parsers
            $model = CActiveRecord::model(Yii::app()->controller->module->modelClass)->findByPk($id);
            $temp = new $model->{Yii::app()->controller->module->parserClassName};
        }

        if ($model == null) {
            throw new CHttpException(404, Yii::t('detailSearch', 'This page doesn\'t exist.'));
        }

        $temp->model = $model;

        return $temp;
    }

    /**
     * Загрузка модели для склада local
     * класс LocalSearch
     * @return LocalSearch
     */
    public function loadLocalModel()
    {
        //класс LocalSearch
        $className = Yii::app()->controller->module->localPriceSearchClass;
        return new $className;
    }

    /**
     * @param $activeName
     * @param $criteria
     * @return array
     */
    protected function fillStoreList($activeName, $criteria)
    {
        $sql = "SELECT `id` FROM `parsers` WHERE `{$activeName}`='1' AND (`language`='' OR {$criteria})";
        $data = Yii::app()->db->createCommand($sql)->queryAll();

        $store_list = array(0 => 'local');

        foreach ($data as $row) {
            $store_list[] = $row['id'];
        }

        $sql = "SELECT `id` FROM `parsers_api` WHERE `{$activeName}`='1' AND `admin_active_state`='1' AND (`language`='' OR {$criteria}) ";
        $data = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($data as $row) {
            $store_list[] = 'api_' . $row['id'];
        }
        return $store_list;
    }

    /**
     * @return array
     */
    protected function handlingPhrase()
    {
        $search_phrase = $_GET['search_phrase'];
        $search_phrase = str_replace(array("\s", ' '), '', $search_phrase);
        $search_phrases = explode(',', $search_phrase);
        return $search_phrases;
    }

    /**
     * @return array
     */
    protected function getStoreLists()
    {
        if ($this->module->isMyAvailable) {
            /**
             * @todo потом доработать здесь
             */
            $store_list = array('local_my');
        } else {
            //column active_state
            $activeName = Yii::app()->controller->module->activeName;
            //Language criteria
            $criteria = Yii::app()->controller->module->langCriteria;

            /**
             * Заполняем список складов
             */
            $store_list = $this->fillStoreList($activeName, $criteria);
        }
        return $store_list;
    }
}