<?php
class KatalogAccessoriesModule extends CWebModule {
    public $cacheId = 'katalogAccessoriesPathsMap';
    public $prefix_url = 'katalog/';
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
        Yii::app()->clientScript->registerCssFile($this->_css.'/katalogAccessories.css');

        $this->prefix_url = Yii::app()->config->get('KatalogAccessories.PrefixUrl');

        $this->setImport(array(
            'katalogAccessories.models.*',
            'katalogAccessories.components.*',
        ));


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
     * ???????????????????? ?????????? ?????????? ???? ????????.
     * @return mixed
     */
    public function getMenuPathsMap() {
        $pathsMap = Yii::app()->cache->get('Menu'.Yii::app()->language.$this->cacheId);
        return $pathsMap === false ? $this->generateMenuPathsMap() : $pathsMap;
    }

    /**
     * ???????????????????? ?????????? ?????????? ???? ????????.
     * @return mixed
     */
    public function getPathsMap($type = 'Cathegory') {
        $pathsMap = Yii::app()->cache->get($type.$this->cacheId);
        return $pathsMap === false ? $this->{'generate'.$type.'PathsMap'}() : $pathsMap;
    }

    public function removeDirectory($dir) {
        if ($objs = glob($dir."/*")) {
            foreach ($objs as $obj) {
                @ is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
            }
        }
        @rmdir($dir);
    }

    /**
     * ?????????????????? ?? ?????? ???????????????????? ???? ???????????? ???????????? ?????????? ??????????.
     * @return void
     */
    public function updatePathsMap() {
        $this->removeDirectory(Yii::app()->basePath.'/runtime/cache');

        Yii::app()->cache->set('Cathegory'.$this->cacheId, $this->generateCathegoryPathsMap());

        Yii::app()->cache->set('Menu'.Yii::app()->language.$this->cacheId, $this->generateMenuPathsMap());
        Yii::app()->cache->set('Items'.$this->cacheId, $this->generateItemsPathsMap());
    }

    /**
     * ?????????????????? ?????????? ??????????????.
     * ???????????????????????? ?????? ?????????????? ?? ???????????????? URL.
     * @return array ID ???????? => ???????? ???? ????????
     */
    public function generateMenuPathsMap($id = 0, $depths = array()) {
        $nodes = array();
        if (Yii::app()->language == Yii::app()->params['default_language']) {
            $nodes = Yii::app()->db->createCommand()
                    ->select('id, level, slug, title')
                    ->from('katalog_accessories_cathegorias')
                    ->where('parent_id=:parent_id AND active_state=1', array(':parent_id' => $id))
                    ->order('root, lft')
                    ->queryAll();
        } else {
            $nodes = Yii::app()->db->createCommand()
                    ->select('katalog_accessories_cathegorias.id as id, katalog_accessories_cathegorias.level as level, katalog_accessories_cathegorias.slug as slug, IF(katalog_accessories_cathegorias_'.Yii::app()->language.'.title IS NULL OR katalog_accessories_cathegorias_'.Yii::app()->language.'.title=\'\', katalog_accessories_cathegorias.title, katalog_accessories_cathegorias_'.Yii::app()->language.'.title)  as title')
                    ->from('katalog_accessories_cathegorias')
                    ->leftJoin('katalog_accessories_cathegorias_'.Yii::app()->language, 'katalog_accessories_cathegorias_'.Yii::app()->language.'.id='.'katalog_accessories_cathegorias.id')
                    ->where('parent_id=:parent_id AND active_state=1', array(':parent_id' => $id))
                    ->order('root, lft')
                    ->queryAll();
        }
        $pathsMap = array();
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
     * ?????????????????? ?????????? ??????????????.
     * ???????????????????????? ?????? ?????????????? ?? ???????????????? URL.
     * @return array ID ???????? => ???????? ???? ????????
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
     * ?????????????????? ?????????? ??????????????.
     * ???????????????????????? ?????? ?????????????? ?? ???????????????? URL.
     * @return array ID ???????? => ???????? ???? ????????
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
        $price = $this->getPriceFunction(array('price' => $value['model']->price, 'brand' => '0', 'price_price_group' => Yii::app()->config->get('KatalogAccessories.PriceGroup'.Yii::app()->getModule('pricegroups')->getUserGroup())));
        $delivery = 0;

        return array(
            'article_order' => '?????????????? ???????????????????? ???'.$value['model']->id,
            'supplier_inn' => $value['model']->supplier_inn,
            'supplier' => $value['model']->supplier,
            'store' => '?????????????? ????????????????????',
            'name' => $value['model']->title,
            'brand' => '',
            'article' => '?????????????? ???????????????????? ???'.$value['model']->id,
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
        $data = array();
        if (Yii::app()->language == Yii::app()->params['default_language']) {
            $data = Yii::app()->db->createCommand()
                    ->select('id, title')
                    ->from('katalog_accessories_cathegorias')
                    ->order('root, lft')
                    ->queryAll();
        } else {
            $data = Yii::app()->db->createCommand()
                    ->select('katalog_accessories_cathegorias.id as id, IF(katalog_accessories_cathegorias_'.Yii::app()->language.'.title IS NULL OR katalog_accessories_cathegorias_'.Yii::app()->language.'.title=\'\', katalog_accessories_cathegorias.title, katalog_accessories_cathegorias_'.Yii::app()->language.'.title)  as title')
                    ->from('katalog_accessories_cathegorias')
                    ->leftJoin('katalog_accessories_cathegorias_'.Yii::app()->language, 'katalog_accessories_cathegorias_'.Yii::app()->language.'.id='.'katalog_accessories_cathegorias.id')
                    ->order('root, lft')
                    ->queryAll();
        }

//        $data = Yii::app()->db->createCommand()
//                ->select('id,  title')
//                ->from(KatalogAccessoriesCathegorias::model()->tableName())
//                ->queryAll();

        foreach ($data as $value) {
            $array[Yii::app()->createAbsoluteUrl('/katalogAccessories/cathegorias/view', array('id' => $value['id']))] = $value['title'];
        }
        $data = array();
        if (Yii::app()->language == Yii::app()->params['default_language']) {
            $data = Yii::app()->db->createCommand()
                    ->select('id, title')
                    ->from('katalog_accessories_items')
                    ->queryAll();
        } else {
            $data = Yii::app()->db->createCommand()
                    ->select('katalog_accessories_items.id as id, IF(katalog_accessories_items_'.Yii::app()->language.'.title IS NULL OR katalog_accessories_items_'.Yii::app()->language.'.title=\'\', katalog_accessories_items.title, katalog_accessories_items_'.Yii::app()->language.'.title)  as title')
                    ->from('katalog_accessories_items')
                    ->leftJoin('katalog_accessories_items_'.Yii::app()->language, 'katalog_accessories_items_'.Yii::app()->language.'.id='.'katalog_accessories_items.id')
                    ->queryAll();
        }
//        $data = Yii::app()->db->createCommand()
//                ->select('id,  title')
//                ->from(KatalogAccessoriesItems::model()->tableName())
//                ->queryAll();

        foreach ($data as $value) {
            $array[Yii::app()->createAbsoluteUrl('/katalogAccessories/items/view', array('id' => $value['id']))] = $value['title'];
        }
        return $array;
    }
}