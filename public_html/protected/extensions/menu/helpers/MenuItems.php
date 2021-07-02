<?php
class MenuItems {
    public static function getMenu($position) {
        $data = Yii::app()->menu->getMenu();
        if ($position == 'left') {
            $temp = Yii::app()->getModule('userControl')->getAsUserBlock();
            if (!empty($temp)) {
                $return = array('-1' => $temp);
                $temp = MenuItems::getItems($data[$position]);

                foreach ($temp as $key => $value) {
                    $return[$key] = $value;
                }

                return $return;
            }
            
            $temp = MenuItems::getItems($data[$position]);
            foreach ($temp as $key => $value) {
                if (isset($temp[$key]['items'])) {
                    foreach ($temp[$key]['items'] as $key2 => $value2) {
                        if (isset($temp[$key]['items'][$key2]['items'])) {
                            unset($temp[$key]['items'][$key2]['items']);
                        }
                    }
                }
            }
            return $temp;
        } else if ($position == 'top') {
            $temp = MenuItems::getItems($data[$position]);
            foreach ($temp as $key => $value) {
                if (isset($temp[$key]['items'])) {
                    unset($temp[$key]['items']);
                }
            }
            return $temp;
        }

        return MenuItems::getItems($data[$position]);
//        else
//            return MenuItems::getItems($data[$position]);
    }

    public static function getItems($data, $type = null) {
        $items = array();
//        print_r($data);
        if (empty($data))
            return $items;
        if ($type == null) {
            foreach ($data as $value) {
                if ($value['type'] == 'cache') {
                	switch ($value['value']) {
                		case 'katalogVavto':
                			if (!Yii::app()->getModule('katalogVavto')->enabledModule) {
                				continue;
                			}
                		break;
                		case 'katalogAccessories':
                			if (!Yii::app()->getModule('katalogAccessories')->enabledModule) {
                				continue;
                			}
                		break;
                		case 'tires':
                			if (!Yii::app()->getModule('tires')->enabledModule) {
                				continue;
                			}
                		break;
                		case 'used':
                			if (!Yii::app()->getModule('used')->enabledModule) {
                				continue;
                			}
                		break;
                		case 'universal':
                			if (!Yii::app()->getModule('universal')->enabledModule) {
                				continue;
                			}
                		break;
                		case 'masla':
                			if (!Yii::app()->getModule('masla')->enabledModule) {
                				continue;
                			}
                		break;
                	}
                	
                    switch ($value['value']) {
                    	case 'pages_top':
                    	case 'pages_left':
                    	case 'katalogAccessories':
                    	case 'katalogVavto':
                    	case 'universal':
                    		$dataCache = Yii::app()->getModule($value['value'])->getMenuPathsMap();
                    	break;
                    }
                    
                    foreach ($dataCache as $valueCache) {
                        $items[] = array(
                            'label' => $valueCache['title'],
                            'url' => $valueCache['path'],
                            //'visible' => ($value['visible'] == 1 ? !Yii::app()->user->isGuest : true),
                            'items' => MenuItems::getItems($valueCache['items'], 'cache'),
                        );
                    }
                } else if ($value['type'] == 'search_form') {
                    $items[] = '<form class="navbar-search pull-left">
                    <input type="text" class="search-query" placeholder="search">
                </form>';
                } else {
                    $url = '';
                    if (is_array($value['value']))
                        $url = $value['value'];
                    else
                        $url = (strpos('1' . $value['value'], 'http') > 0 ? $value['value'] : array($value['value']));
                    
                    if (isset($value['items']))
                        $items[] = array(
                            'label' => $value['title'],
                            'url' => $url,
                            'visible' => ( $value['visible'] == 1 || $value['visible'] ? !Yii::app()->user->isGuest : true),
                            'items' => MenuItems::getItems($value['items']),
                        );
                    else
                        $items[] = array(
                            'label' => $value['title'],
                            'url' => $url,
                            'visible' => ($value['visible'] == 1 ? !Yii::app()->user->isGuest : true),
                        );
                }
            }
        } else {
            foreach ($data as $value) {
                $items[] = array(
                    'label' => $value['title'],
                    'url' => $value['path'],
                    //'visible' => ($value['visible'] == 1 ? !Yii::app()->user->isGuest : true),
                    'items' => MenuItems::getItems($value['items'], 'cache'),
                );
            }
        }
        
        return $items;
    }

    public static function getItemsLeft($data, $type = null) {
        $items = array();
        if ($type == null) {
            foreach ($data as $value) {
                if ($value['type'] == 'cache') {
                    if ($value['value'] == 'pages_top' || $value['value'] == 'pages_left')
                        $dataCache = Yii::app()->getModule($value['value'])->getMenuPathsMap();
                    // print_r($dataCache);
                    foreach ($dataCache as $valueCache) {
                        $items[] = array(
                            'name' => $valueCache['title'],
                            'link' => $valueCache['path'],
                            //'visible' => ($value['visible'] == 1 ? !Yii::app()->user->isGuest : true),
                            'sub' => MenuItems::getItemsLeft($valueCache['items'], 'cache'),
                        );
                    }
                } else {
                    $items[] = array(
                        'name' => $value['title'],
                        'link' => (strpos('1' . $value['value'], 'http') > 0 ? $value['value'] : array($value['value'])),
                        'visible' => ($value['visible'] == 1 ? !Yii::app()->user->isGuest : true),
                    );
                }
            }
        } else {
            foreach ($data as $value) {
                $items[] = array(
                    'name' => $value['title'],
                    'link' => $value['path'],
                    //'visible' => ($value['visible'] == 1 ? !Yii::app()->user->isGuest : true),
                    'sub' => MenuItems::getItemsLeft($value['items'], 'cache'),
                );
            }
        }
        return $items;
    }
}