<?php
class KatalogSeoBrandsModule extends CWebModule {
    public $enabledModule = true;
    
	public function init() {
		$this->setImport(array(
            'katalogSeoBrands.models.*',
        ));
	}
}
?>