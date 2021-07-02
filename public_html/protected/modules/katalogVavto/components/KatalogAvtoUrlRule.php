<?php
class KatalogAvtoUrlRule extends CBaseUrlRule {
    public function createUrl($manager, $route, $params, $ampersand) {
//        print_r($params);
        $pathsMapBrands = Yii::app()->getModule('katalogVavto')->getPathsMap('Brands');
        $pathsMapCars = Yii::app()->getModule('katalogVavto')->getPathsMap('Cars');
        
        if ($route === 'katalogVavto/brands/view' && isset($params['id'], $pathsMapBrands[$params['id']]))
            return $pathsMapBrands[$params['id']] . $manager->urlSuffix . (isset($params['_lang']) ? '?_lang=' . $params['_lang'] : '');
        else if ($route === 'katalogVavto/cars/view' && isset($params['id'], $pathsMapCars[$params['id']])) {
            $end = '';
            if (isset($params['KatalogVavtoItems_page']))
                $end.=(empty($end) ? '?' : '&') . 'KatalogVavtoItems_page=' . $params['KatalogVavtoItems_page'];
            if (isset($params['type']))
                $end.=(empty($end) ? '?' : '&') . 'type=' . $params['type'];
            if (isset($params['_lang']))
                $end.=(empty($end) ? '?' : '&') . '_lang=' . $params['_lang'];
            return $pathsMapCars[$params['id']] . $manager->urlSuffix . $end;
        } else if ($route === 'katalogVavto/items/view') {
            $pathsMapItems = Yii::app()->getModule('katalogVavto')->getPathsMap('Items');
            
            if (isset($params['id'], $pathsMapItems[$params['id']])) {
                return $pathsMapItems[$params['id']] . $manager->urlSuffix . (isset($params['_lang']) ? '?_lang=' . $params['_lang'] : '');
            }
        } else
            return false;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
    	if ($pathInfo == Yii::app()->config->get('KatalogVavto.PrefixUrl')) {
    		return 'katalogVavto/katalog/index';
    	}
    	
        $pathsMapBrands = Yii::app()->getModule('katalogVavto')->getPathsMap('Brands');
        $pathsMapCars = Yii::app()->getModule('katalogVavto')->getPathsMap('Cars');

        //!!! Если тип или подтип в адресе
        $type = strpos($pathInfo, 'type-') !== false;
        $subtype = strpos($pathInfo, 'subtype-') !== false;

        if ($type || $subtype) {
            $pi = explode('/', $pathInfo);

            if ($type && $subtype) {
                $type = str_replace('type-', '', $pi[count($pi) - 2]);
                $subtype = str_replace('subtype-', '', $pi[count($pi) - 1]);

                $_GET['subtype'] = $subtype;
                $pathInfo = str_replace('/subtype-'.$subtype, '', $pathInfo);
            } else {
                $type = str_replace('type-', '', $pi[count($pi) - 1]);
            }

            $pathInfo = str_replace('/type-'.$type, '', $pathInfo);

            $_GET['type'] = $type;
        }
        //!!! Если тип или подтип в адресе

        if (array_key_exists('i', $_GET)) {
            //echo $pathInfo; exit;
        }
        
        $id = array_search($pathInfo, $pathsMapBrands);

        if ($id == NUll) {
            $id = array_search($pathInfo, $pathsMapCars);
            if ($id == NUll) {
                $pathsMapItems = Yii::app()->getModule('katalogVavto')->getPathsMap('Items');
                
                $id = array_search($pathInfo, $pathsMapItems);
            } else {
                $_GET['id'] = $id;
                return 'katalogVavto/cars/view';
            }
        } else {
            $_GET['id'] = $id;
            return 'katalogVavto/brands/view';
        }
        
        if ($id === false)
            return false;
        
        $_GET['id'] = $id;
        return 'katalogVavto/items/view';
    }
}