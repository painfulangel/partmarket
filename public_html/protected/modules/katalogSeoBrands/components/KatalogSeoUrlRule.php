<?php
class KatalogSeoUrlRule extends CBaseUrlRule {
	public function createUrl($manager, $route, $params, $ampersand) {
		return false;
	}

	public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
		$settings = KatalogSeoBrandsSettings::model()->find();

		if (is_object($settings)) {
			if ($pathInfo == $settings->url)
    			return 'katalogSeoBrands/katalog/index';
    		else {
    			//echo $pathInfo; exit;
    			$parts = explode('/', $pathInfo);
                if ($parts[0] == $settings->url) {
                    switch (count($parts)) {
                        case 2:
                            $_GET['brand'] = $parts[1];
                            return 'katalogSeoBrands/katalog/brand';
                        break;
                        case 3:
                            $_GET['brand'] = $parts[1];
                            $_GET['category'] = $parts[2];
                            return 'katalogSeoBrands/katalog/category';
                        break;
                        case 4:
                            $_GET['brand'] = $parts[1];
                            $_GET['category'] = $parts[2];
                            $_GET['article'] = $parts[3];
                            return 'katalogSeoBrands/katalog/article';
                        break;
                    }
                }
    		}
    	}

    	return false;
	}
}
?>