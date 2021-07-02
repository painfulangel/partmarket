<?php
class PagesUrlRule extends CBaseUrlRule {
    public function createUrl($manager, $route, $params, $ampersand) {
        $pathsMap_top = Yii::app()->getModule('pages_top')->getPathsMap();
        $pathsMap_left = Yii::app()->getModule('pages_left')->getPathsMap();
        
        if ($route === 'pages_top/default/view' && isset($params['id'], $pathsMap_top[$params['id']]))
            return $pathsMap_top[$params['id']] . $manager->urlSuffix . (isset($params['_lang']) ? '?_lang=' . $params['_lang'] : '');
        else if ($route === 'pages_left/default/view' && isset($params['id'], $pathsMap_left[$params['id']]))
            return $pathsMap_left[$params['id']] . $manager->urlSuffix . (isset($params['_lang']) ? '?_lang=' . $params['_lang'] : '');
        else
            return false;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
        $pathsMap_top = Yii::app()->getModule('pages_top')->getPathsMap();
        $pathsMap_left = Yii::app()->getModule('pages_left')->getPathsMap();
//        echo $pathInfo;
        $id = array_search($pathInfo, $pathsMap_top);
        if ($id == NUll)
            $id = array_search($pathInfo, $pathsMap_left);
        else {
            $_GET['id'] = $id;
            return 'pages_top/default/view';
        }
        if ($id === false)
            return false;
        $_GET['id'] = $id;
        return 'pages_left/default/view';
    }
}