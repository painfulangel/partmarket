<?php
class KatalogAccessoriesUrlRule extends CBaseUrlRule {
    public function createUrl($manager, $route, $params, $ampersand) {
        $pathsMapCathegories = Yii::app()->getModule('katalogAccessories')->getPathsMap();
        $pathsMapItems = Yii::app()->getModule('katalogAccessories')->getPathsMap('Items');
        
        if ($route === 'katalogAccessories/cathegorias/view' && isset($params['id'], $pathsMapCathegories[$params['id']]))
            return $pathsMapCathegories[$params['id']].$manager->urlSuffix.(isset($params['_lang']) ? '?_lang=' . $params['_lang'] : '');
        else if ($route === 'katalogAccessories/items/view' && isset($params['id'], $pathsMapItems[$params['id']]))
            return $pathsMapItems[$params['id']].$manager->urlSuffix.(isset($params['_lang']) ? '?_lang=' . $params['_lang'] : '');
        else
            return false;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
        $pathsMapCathegories = Yii::app()->getModule('katalogAccessories')->getPathsMap();
        $pathsMapItems = Yii::app()->getModule('katalogAccessories')->getPathsMap('Items');
        $id = array_search($pathInfo, $pathsMapCathegories);
        if ($id == NUll)
            $id = array_search($pathInfo, $pathsMapItems);
        else {
            $_GET['id'] = $id;
            return 'katalogAccessories/cathegorias/view';
        }
        if ($id === false)
            return false;
        $_GET['id'] = $id;
        return 'katalogAccessories/items/view';
    }
}