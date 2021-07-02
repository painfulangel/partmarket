<?php

class PagesModule extends CWebModule {

    /**
     * @var string идентификатор, по которому доступна закешированная карта путей
     */
    public $cacheId = 'pagesPathsMap';
    public $position = '';

    public function init() {
        $this->setImport(array(
            'ext.pages.models.*',
            'ext.pages.components.*',
        ));

        if (Yii::app()->cache->get($this->cacheId) === false)
            $this->updatePathsMap();
    }

    /**
     * Возвращает карту путей из кеша.
     * @return mixed
     */
    public function getMenuPathsMap() {
        $pathsMap = Yii::app()->cache->get('Menu' . Yii::app()->language . $this->cacheId);
        return $pathsMap === false ? $this->generateMenuPathsMap() : $pathsMap;
    }

    /**
     * Возвращает карту путей из кеша.
     * @return mixed
     */
    public function getPathsMap() {
        $pathsMap = Yii::app()->cache->get($this->cacheId);
        return $pathsMap === false ? $this->generatePathsMap() : $pathsMap;
    }

    public function removeDirectory($dir) {
        if ($objs = glob($dir . "/*")) {
            foreach ($objs as $obj) {
                @is_dir($obj) ? $this->removeDirectory($obj) : @unlink($obj);
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

        Yii::app()->cache->set($this->cacheId, $this->generatePathsMap());
        Yii::app()->cache->set('Menu' . Yii::app()->language . $this->cacheId, $this->generateMenuPathsMap());
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
                    ->select('id, level, slug, page_title')
                    ->from('pages' . $this->position)
                    ->where('parent_id=' . $id . ' and is_published=1')
                    ->order('root, lft')
                    ->queryAll();
        } else {
            $nodes = Yii::app()->db->createCommand()
                    ->select('pages' . $this->position . '.id as id, pages' . $this->position . '.level as level, pages' . $this->position . '.slug as slug, IF(' . 'pages' . $this->position . '_' . Yii::app()->language . '.page_title IS NULL OR pages' . $this->position . '_' . Yii::app()->language . '.page_title=\'\', pages' . $this->position . '.page_title, pages' . $this->position . '_' . Yii::app()->language . '.page_title) as page_title')
                    ->from('pages' . $this->position)
                    ->leftJoin('pages' . $this->position . '_' . Yii::app()->language, 'pages' . $this->position . '_' . Yii::app()->language . '.id=' . 'pages' . $this->position . '.id')
                    ->where('parent_id=\'' . $id . '\' AND is_published=1')
                    ->order('root, lft')
                    ->queryAll();
        }
        $pathsMap = array();
        foreach ($nodes as $node) {
            if ($node['level'] > 1)
                $path = $depths[$node['level'] - 1];
            else
                $path = '/';

            $path .= $node['slug'];
            $depths[$node['level']] = $path . '/';
            $pathsMap[$node['id']] = array('path' => $path, 'title' => $node['page_title'], 'items' => $this->generateMenuPathsMap($node['id'], $depths));
        }

        return $pathsMap;
    }

    /**
     * Генерация карты страниц.
     * Используется при разборе и создании URL.
     * @return array ID узла => путь до узла
     */
    public function generatePathsMap() {
        $nodes = Yii::app()->db->createCommand()
                ->select('id, level, slug')
                ->from('pages' . $this->position)
                ->order('root, lft')
                ->queryAll();

        $pathsMap = array();
        $depths = array();

        foreach ($nodes as $node) {
            if ($node['level'] > 1)
                $path = $depths[$node['level'] - 1];
            else
                $path = '';

            $path .= $node['slug'];
            $depths[$node['level']] = $path . '/';
            $pathsMap[$node['id']] = $path;
        }

        return $pathsMap;
    }

    public function getSitemap() {
        $array = array();
        $data = array();
        if (Yii::app()->language == Yii::app()->params['default_language']) {
            $data = Yii::app()->db->createCommand()
                    ->select('id,  page_title AS title')
                    ->from('pages' . $this->position)
//                    ->where('parent_id=' . $id . ' and active_state=1')
//                    ->order('root, lft')
                    ->queryAll();
        } else {
            $data = Yii::app()->db->createCommand()
                    ->select('pages' . $this->position.'.id as id,  IF(pages' . $this->position.'_' . Yii::app()->language . '.page_title IS NULL OR pages' . $this->position.'_' . Yii::app()->language . '.page_title=\'\', pages' . $this->position.'.page_title, pages' . $this->position.'_' . Yii::app()->language . '.page_title ) AS title')
                    ->from('pages' . $this->position)
                    ->leftJoin('pages' . $this->position.'_' . Yii::app()->language, 'pages' . $this->position.'_' . Yii::app()->language . '.id=' . 'pages' . $this->position.'.id')
//                    ->where('parent_id=\'' . $id . '\' and active_state=1')
//                    ->order('root, lft')
                    ->queryAll();
        }
//        $data = Yii::app()->db->createCommand()
//                ->select('id,  page_title AS title')
//                ->from('pages' . $this->position)
//                ->queryAll();

        foreach ($data as $value) {
            $array[Yii::app()->createAbsoluteUrl('/pages' . $this->position . '/default/view', array('id' => $value['id']))] = $value['title'];
        }

        return $array;
    }

}
