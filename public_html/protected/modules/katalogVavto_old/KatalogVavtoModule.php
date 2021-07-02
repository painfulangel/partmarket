<?php
class KatalogVavtoModule extends CWebModule {
    public $cacheId = 'katalogVavtoPathsMap';
    public $prefix_url = 'katalog_vavto/';
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

    public function getSearchForm() {
        return '<form class="" action="' . Yii::app()->createUrl('/katalogVavto/items/search') . '"  ><div class="btn-group">
<input style="margin-right: -5px;border-right: 0;"  id="KatalogAvtoSearchInput"  name="KatalogVavtoItems[search_text]"  type="text" data-provide="typeahead" data-items="4" placeholder="Текст поиска"
                                       data-source=\'["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut",
                                       "Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas",
                                       "Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota",
                                       "Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey",
                                       "New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon",
                                       "Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas",
                                       "Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"]\'/>           
<div class="btn btn-default" onclick="$(\'#KatalogAvtoSearchInput\').val(\'\')" ><img src="/images/theme/x.png"></div>                                       

                                  </div><button class="btn" type="submit" >Найти</button></form>';
    }

    public function getLeftMenu() {
//        print_r(Yii::app()->controller->getModule());
        if ((isset(Yii::app()->controller->module->id) && (Yii::app()->controller->module->id == 'katalogVavto') &&
                ((Yii::app()->controller->id == 'cars' && Yii::app()->controller->action->id == 'view') ||
                (Yii::app()->controller->id == 'brands' && Yii::app()->controller->action->id == 'view') ||
                (Yii::app()->controller->id == 'items' && Yii::app()->controller->action->id == 'view')))) {
            $id = Yii::app()->request->getParam('id', 0);
            
            switch (Yii::app()->controller->id) {
            	case 'cars':
            		$model = KatalogVavtoCars::model()->findByPk($id);
            		if ($model != null) {
            			return array('items' => $this->leftMenuModels($model->parent_id), 'type' => 'models');
            		}
            	break;
            	case 'items':
            		$type = Yii::app()->request->getParam('type', '');
            		$childs = KatalogVavtoItems::model()->getCarPartTypes();
            		
            		if (is_object($item = KatalogVavtoItems::model()->findByPk($id))) {
	            		$result = array();
	            		
	            		foreach ($childs as $key => $child) {
	            			//CHtml::link(($type == $key ? '<b>' : '') . $child . ($type == $key ? '</b>' : ''), '?type=' . $key);
	            			$result[] = array('label' => $child, 'url' => array('/katalogVavto/cars/view', 'id'=> $item->cathegory_id, 'type' => $key), 'itemOptions' => array('class' => $key));
	                	}
	                	
	                	return array('items' => $result, 'type' => 'models');
            		}
            		
            		/*$model = KatalogVavtoItems::model()->findByPk($id);
            		if ($model != null) {
            			$model = KatalogVavtoCars::model()->findByPk($model->cathegory_id);
            			if ($model != null) {
            				return array('items' => $this->leftMenuModels($model->parent_id), 'type' => 'models');
            			}
            		}*/
            	break;
            }
        }
        
        return array('items' => $this->leftMenuBrand(), 'type' => 'brand');
    }

    public function leftMenuBrand($parentId = 0) {
        $nodes = Yii::app()->db->createCommand()
                ->select('id,  slug, short_title AS title,menu_image')
                ->from(KatalogVavtoBrands::model()->tableName() . ' t')
                ->where(' active_state=1')
                ->order('t.order')
                ->queryAll();
        $result = array();
        foreach ($nodes as $value) {
            $result[] = array('label' => $value['title'], 'url' => array('/katalogVavto/brands/view', 'id' => $value['id']), 'itemOptions' => array('class' => $value['menu_image']));
        }
        return $result;
    }

    public function leftMenuModels($parentId = 0) {
        $nodes = Yii::app()->db->createCommand()
                ->select('id, slug, short_title AS title,menu_image')
                ->from(KatalogVavtoCars::model()->tableName() . ' t')
                ->where('parent_id=:parentId AND active_state=1', array(':parentId' => $parentId))
                ->order('t.order')
                ->queryAll();
        $result = array();
        foreach ($nodes as $value) {
            $result[] = array('label' => $value['title'], 'url' => array('/katalogVavto/cars/view', 'id' => $value['id']), 'itemOptions' => array('class' => $value['menu_image']));
        }
        return $result;
    }

    public function init() {
        $this->pathFiles = realpath(Yii::app()->basePath . '/..' . $this->pathExportFiles) . '/';

        $assetsDir = dirname(__FILE__) . "/assets";
        $this->_images = Yii::app()->assetManager->publish($assetsDir);
        $this->_css = $this->_images . '/css';
//        $this->_images .= '/images';
        Yii::app()->clientScript->registerCssFile($this->_css . '/katalogVavto.css');

        $this->prefix_url = Yii::app()->config->get('KatalogVavto.PrefixUrl');

        $this->setImport(array(
            'katalogVavto.models.*',
            'katalogVavto.components.*',
        ));

//        if (Yii::app()->cache->get($this->cacheId) === false)
//            $this->updatePathsMap();
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
        $pathsMap = Yii::app()->cache->get('Menu' . $this->cacheId);
        return $pathsMap === false ? $this->generateMenuPathsMap() : $pathsMap;
    }

    /**
     * Возвращает карту путей из кеша.
     * @return mixed
     */
    public function getPathsMap($type = 'Brands') {
        $pathsMap = Yii::app()->cache->get($type . $this->cacheId);
        return $pathsMap === false ? $this->{'generate' . $type . 'PathsMap'}() : $pathsMap;
    }

    public function removeDirectory($dir) {
        if ($objs = glob($dir . "/*")) {
            foreach ($objs as $obj) {
                @is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
            }
        }
        @rmdir($dir);
    }

    /**
     * Сохраняет в кеш актуальную на момент вызова карту путей.
     * @return void
     */
    public function updatePathsMap() {
        $this->removeDirectory(Yii::app()->basePath . '/runtime/cache');

        Yii::app()->cache->set('Brands' . $this->cacheId, $this->generateBrandsPathsMap());
        Yii::app()->cache->set('Cars' . $this->cacheId, $this->generateCarsPathsMap());
//        Yii::app()->cache->set('Menu' . $this->cacheId, $this->generateMenuPathsMap());
        Yii::app()->cache->set('Items' . $this->cacheId, $this->generateItemsPathsMap());
    }

    /**
     * Генерация карты страниц.
     * Используется при разборе и создании URL.
     * @return array ID узла => путь до узла
     */
    public function generateMenuPathsMap($id = 0, $depths = array()) {
        $nodes = array();
        if (Yii::app()->language == Yii::app()->params['default_language']) {
            $nodes = Yii::app()->db->createCommand()
                    ->select('id, level, slug, short_title AS title')
                    ->from('katalog_vavto_cathegorias')
                    ->where('parent_id = :parent_id AND active_state=1', array(':parent_id' => $id))
                    ->order('root, lft')
                    ->queryAll();
        } else {
            $nodes = Yii::app()->db->createCommand()
                    ->select('katalog_vavto_cathegorias.id as id, katalog_vavto_cathegorias.level as level, katalog_vavto_cathegorias.slug as slug, IF(katalog_vavto_cathegorias_' . Yii::app()->language . '.short_title IS NULL OR katalog_vavto_cathegorias_' . Yii::app()->language . '.short_title=\'\', katalog_vavto_cathegorias.short_title,katalog_vavto_cathegorias_' . Yii::app()->language . '.short_title ) AS title')
                    ->from('katalog_vavto_cathegorias')
                    ->leftJoin('katalog_vavto_cathegorias_' . Yii::app()->language, 'katalog_vavto_cathegorias_' . Yii::app()->language . '.id=' . 'katalog_vavto_cathegorias.id')
                    ->where('parent_id = :parent_id AND active_state=1', array(':parent_id' => $id))
                    ->order('root, lft')
                    ->queryAll();
        }
        $pathsMap = array();
        foreach ($nodes as $node) {
            if ($node['level'] > 1)
                $path = $depths[$node['level'] - 1];
            else
                $path = '/' . $this->prefix_url;

            $path .= $node['slug'];
            $depths[$node['level']] = $path . '/';
            $pathsMap[$node['id']] = array('path' => $path, 'title' => $node['title'], 'items' => $this->generateMenuPathsMap($node['id'], $depths));
        }

        return $pathsMap;
    }

    /**
     * Генерация карты страниц.
     * Используется при разборе и создании URL.
     * @return array ID узла => путь до узла
     */
    public function generateBrandsPathsMap() {
        $nodes = Yii::app()->db->createCommand()
                ->select('id,  slug')
                ->from(KatalogVavtoBrands::model()->tableName() . ' t')
                ->order('t.order')
                ->queryAll();

        $pathsMap = array();
//        $depths = array();

        foreach ($nodes as $node) {
            $path = $this->prefix_url;
            
            if (substr($path, mb_strlen($path) - 1, 1) != '/') $path .= '/';

            $path .= $node['slug'];
//            $depths[$node['level']] = $path . '/';
            $pathsMap[$node['id']] = $path;
        }

        return $pathsMap;
    }

    public function generateCarsPathsMap() {
        $nodes = Yii::app()->db->createCommand()
                ->select('id, parent_id, slug')
                ->from(KatalogVavtoCars::model()->tableName() . ' t')
                ->order('t.order')
                ->queryAll();

        $pathsMap = array();
        
		//BrandskatalogVavtoPathsMap
        //$parent = Yii::app()->cache->get('Brands' . $this->cacheId, array());
        $parent = $this->generateBrandsPathsMap();
        
        foreach ($nodes as $node) {
            if (isset($parent[$node['parent_id']])) {
                $path = $parent[$node['parent_id']];
                
                if (substr($path, mb_strlen($path) - 1, 1) != '/') $path .= '/';
                
                $path .= $node['slug'];
//                $depths[$node['level']] = $path . '/';
                $pathsMap[$node['id']] = $path;
            }
        }
        
        //echo '<pre>'; print_r($pathsMap); echo '</pre>';
        
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
                ->from(KatalogVavtoItems::model()->tableName())
                ->order('id')
                ->queryAll();

        $pathsMap = array();

		$cath = $this->generateCarsPathsMap();
        //$cath = Yii::app()->cache->get('Cars' . $this->cacheId, array());
        
        //echo '<pre>'; print_r($nodes); echo '</pre>';

        foreach ($nodes as $node) {
            if ($node['cathegory_id'] > 0 && array_key_exists($node['cathegory_id'], $cath))
                $path = $cath[$node['cathegory_id']] . '/';
            else
                $path = $this->prefix_url . '';

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
        $price = $this->getPriceFunction(array('price' => $value['model']->price, 'brand' => '', 'price_price_group' => Yii::app()->config->get('KatalogVavto.PriceGroup' . Yii::app()->getModule('pricegroups')->getUserGroup())));
        $delivery = 0;

        return array(
            'article_order' => 'КАТАЛОГ АКСЕСУАРОВ №' . $value['model']->id,
            'supplier_inn' => $value['model']->supplier_inn,
            'supplier' => $value['model']->supplier,
            'store' => 'Каталог аксесуаров',
            'name' => $value['model']->title,
            'brand' => '',
            'article' => 'КАТАЛОГ АКСЕСУАРОВ №' . $value['model']->id,
            'delivery' => $delivery,
            'quantum_all' => '',
            'price_echo' => $this->getPriceFormatFunction($price),
            'price' => $price,
            'price_data_id' => $value['model']->id,
            'store_count_state' => 0,
            'weight' => '',
            'go_link' => Yii::app()->createAbsoluteUrl('/katalogVavto/items/view', array('id' => $value['model']->id)),
        );
    }

    public function getSitemap() {
        $array = array();
        $data = array();
        if (Yii::app()->language == Yii::app()->params['default_language']) {
            $data = Yii::app()->db->createCommand()
                    ->select('id,  short_title AS title')
                    ->from('katalog_vavto_cathegorias')
//                    ->where('parent_id=' . $id . ' and active_state=1')
                    ->order('root, lft')
                    ->queryAll();
        } else {
            $data = Yii::app()->db->createCommand()
                    ->select('katalog_vavto_cathegorias.id as id,  IF(katalog_vavto_cathegorias_' . Yii::app()->language . '.short_title IS NULL OR katalog_vavto_cathegorias_' . Yii::app()->language . '.short_title=\'\', katalog_vavto_cathegorias.short_title,katalog_vavto_cathegorias_' . Yii::app()->language . '.short_title ) AS title')
                    ->from('katalog_vavto_cathegorias')
                    ->leftJoin('katalog_vavto_cathegorias_' . Yii::app()->language, 'katalog_vavto_cathegorias_' . Yii::app()->language . '.id=' . 'katalog_vavto_cathegorias.id')
//                    ->where('parent_id=\'' . $id . '\' and active_state=1')
                    ->order('root, lft')
                    ->queryAll();
        }
//        $data = Yii::app()->db->createCommand()
//                ->select('id,  title')
//                ->from(KatalogVavtoCathegorias::model()->tableName())
//                ->queryAll();

        foreach ($data as $value) {
            $array[Yii::app()->createAbsoluteUrl('/katalogVavto/cathegorias/view', array('id' => $value['id']))] = $value['title'];
        }
        $data = array();
        if (Yii::app()->language == Yii::app()->params['default_language']) {
            $data = Yii::app()->db->createCommand()
                    ->select('id,  title AS title')
                    ->from('katalog_vavto_items')
//                    ->where('parent_id=' . $id . ' and active_state=1')
//                    ->order('root, lft')
                    ->queryAll();
        } else {
            $data = Yii::app()->db->createCommand()
                    ->select('katalog_vavto_items.id as id,  IF(katalog_vavto_items_' . Yii::app()->language . '.title IS NULL OR katalog_vavto_items_' . Yii::app()->language . '.title=\'\', katalog_vavto_items.title,katalog_vavto_items_' . Yii::app()->language . '.title ) AS title')
                    ->from('katalog_vavto_items')
                    ->leftJoin('katalog_vavto_items_' . Yii::app()->language, 'katalog_vavto_items_' . Yii::app()->language . '.id=' . 'katalog_vavto_items.id')
//                    ->where('parent_id=\'' . $id . '\' and active_state=1')
//                    ->order('root, lft')
                    ->queryAll();
        }
//        $data = Yii::app()->db->createCommand()
//                ->select('id,  title')
//                ->from(KatalogVavtoItems::model()->tableName())
//                ->queryAll();

        foreach ($data as $value) {
            $array[Yii::app()->createAbsoluteUrl('/katalogVavto/items/view', array('id' => $value['id']))] = $value['title'];
        }
        return $array;
    }

}
