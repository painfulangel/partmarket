<?php
class KatalogTOModule extends CWebModule {
    public $cacheId = 'katalogTOPathsMap';
    public $prefix_url = 'katalogTO/';
    public $_css;
    public $_images;
    public $enabledModule = true;

    /**
     *
     * @var Integer Max size of uploaded files 
     */
    public $maxFileSize = 26214400; //for max size 25mb
    /**
     *
     * @var Array extra encoding characters of input files
     */
    public $extraCharacters = array('cp1251' => 'cp1251', 'UTF-8' => 'utf');
//    public $extraCharacters = array('cp1251' => 'cp1251', 'UTF-8' => 'utf');

    /**
     *
     * @var String function name for echo Radio buttons of extra characters
     */
    public $radionButtonFunction = 'radioButtonListInlineRow';

    /**
     *
     * @var String path fo upload files start with site path
     */
    public $pathExportFiles = '/upload_files/';

    /**
     *
     * @var String path for upload files start with root file system 
     */
    public $pathFiles = '';

    public function init() {

        $this->pathFiles = realpath(Yii::app()->basePath.'/..'.$this->pathExportFiles).'/';

        $assetsDir = dirname(__FILE__)."/assets";
        $this->_images = Yii::app()->assetManager->publish($assetsDir);
        $this->_css = $this->_images.'/css';
//        $this->_images .= '/images';
        Yii::app()->clientScript->registerCssFile($this->_css.'/katalogTO.css');

//        $this->prefix_url = Yii::app()->config->get('KatalogAccessories.PrefixUrl');

        $this->setImport(array(
            'katalogTO.models.*',
            'katalogTO.components.*',
        ));

        if (Yii::app()->cache->get($this->cacheId) === false)
            $this->updatePathsMap();
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }

    /**
     * Возвращает карту путей из кеша.
     * @return mixed
     */
    public function getMenuPathsMap() {
        $pathsMap = Yii::app()->cache->get('Menu'.$this->cacheId);
        return $pathsMap === false ? $this->generateMenuPathsMap() : $pathsMap;
    }

    /**
     * Возвращает карту путей из кеша.
     * @return mixed
     */
    public function getPathsMap($type = 'Cathegory') {
        $pathsMap = Yii::app()->cache->get($type.$this->cacheId);
        return $pathsMap === false ? $this->{'generate'.$type.'PathsMap'}() : $pathsMap;
    }

    /**
     * Сохраняет в кеш актуальную на момент вызова карту путей.
     * @return void
     */
    public function updatePathsMap() {
        Yii::app()->cache->set('Cathegory'.$this->cacheId, $this->generateCathegoryPathsMap());
        Yii::app()->cache->set('Menu'.$this->cacheId, $this->generateMenuPathsMap());
        Yii::app()->cache->set('Items'.$this->cacheId, $this->generateItemsPathsMap());
    }

    /**
     * Генерация карты страниц.
     * Используется при разборе и создании URL.
     * @return array ID узла => путь до узла
     */
    public function generateMenuPathsMap($id = 0, $depths = array()) {
        $nodes = Yii::app()->db->createCommand()
                ->select('id, level, slug, title')
                ->from(KatalogAccessoriesCathegorias::model()->tableName())
                ->where('parent_id=:parent_id AND active_state=1', array(':parent_id' => $id))
                ->order('root, lft')
                ->queryAll();

        $pathsMap = array();
//        $depths = array();
//          print_r($nodes);
        foreach ($nodes as $node) {
            if ($node['level'] > 1)
                $path = $depths[$node['level'] - 1];
            else
                $path = '/'.$this->prefix_url;

            $path .= $node['slug'];
            $depths[$node['level']] = $path.'/';
            $pathsMap[$node['id']] = array('path' => $path, 'title' => $node['title'], 'items' => $this->generateMenuPathsMap($node['id'], $depths));
        }
        
        return $pathsMap;
    }

    /**
     * Генерация карты страниц.
     * Используется при разборе и создании URL.
     * @return array ID узла => путь до узла
     */
    public function generateCathegoryPathsMap() {
        $nodes = Yii::app()->db->createCommand()
                ->select('id, level, slug')
                ->from(KatalogAccessoriesCathegorias::model()->tableName())
                ->order('root, lft')
                ->queryAll();

        $pathsMap = array();
        $depths = array();

        foreach ($nodes as $node) {
            if ($node['level'] > 1)
                $path = $depths[$node['level'] - 1];
            else
                $path = $this->prefix_url.'';

            $path .= $node['slug'];
            $depths[$node['level']] = $path.'/';
            $pathsMap[$node['id']] = $path;
        }
        
        return $pathsMap;
    }

    /**
     * Генерация карты страниц.
     * Используется при разборе и создании URL.
     * @return array ID узла => путь до узла
     */
    public function generateItemsPathsMap() {
        $nodes = Yii::app()->db->createCommand()
                ->select('id, cathegory_id, slug')
                ->from(KatalogAccessoriesItems::model()->tableName())
                ->order('id')
                ->queryAll();

        $pathsMap = array();

        $cath = $this->generateCathegoryPathsMap();

        foreach ($nodes as $node) {
            if ($node['cathegory_id'] > 0)
                $path = $cath[$node['cathegory_id']].'/';
            else
                $path = $this->prefix_url.'';

            $path .= $node['slug'];
            $pathsMap[$node['id']] = $path;
        }

        return $pathsMap;
    }

    /**
     *
     * @var string code to echo price value 
     */
    public function getPriceFormatFunction($price) {
        return Yii::app()->getModule('currencies')->getFormatPrice($price);
    }

    public function getPriceFunction($value) {
        return Yii::app()->getModule('pricegroups')->getPrice($value['price'], $value['price_price_group'], $value['brand']);
    }

    public function getCartFormData($value) {
        $price = $this->getPriceFunction(array('price' => $value['model']->price, 'brand' => '', 'price_price_group' => Yii::app()->config->get('KatalogAccessories.PriceGroup'.Yii::app()->getModule('pricegroups')->getUserGroup())));
        $delivery = 0;

        return array(
            'article_order' => 'КАТАЛОГ АКСЕСУАРОВ №'.$value['model']->id,
            'supplier_inn' => $value['model']->supplier_inn,
            'supplier' => $value['model']->supplier,
            'store' => 'Каталог аксесуаров',
            'name' => $value['model']->title,
            'brand' => '',
            'article' => 'КАТАЛОГ АКСЕСУАРОВ №'.$value['model']->id,
            'delivery' => $delivery,
            'quantum_all' => '',
            'price_echo' => $this->getPriceFormatFunction($price),
            'price' => $price,
            'price_data_id' => $value['model']->id,
            'store_count_state' => 0,
            'weight' => '',
            'go_link' => Yii::app()->createAbsoluteUrl('/katalogAccessories/items/view', array('id' => $value['model']->id)),
        );
    }

    public function getSitemap() {
        $array = array();
        $data = Yii::app()->db->createCommand()
                ->select('id,  title')
                ->from(KatalogAccessoriesCathegorias::model()->tableName())
                ->queryAll();

        foreach ($data as $value) {
            $array[Yii::app()->createAbsoluteUrl('/katalogAccessories/cathegorias/view', array('id' => $value['id']))] = $value['title'];
        }
        $data = Yii::app()->db->createCommand()
                ->select('id,  title')
                ->from(KatalogAccessoriesItems::model()->tableName())
                ->queryAll();

        foreach ($data as $value) {
            $array[Yii::app()->createAbsoluteUrl('/katalogAccessories/items/view', array('id' => $value['id']))] = $value['title'];
        }
        return $array;
    }
}