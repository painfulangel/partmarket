<?php
/* @var $this SiteController */
$this->breadcrumbs = array(
//	'Cron Logs'=>array('index'),
    Yii::t('config', 'Choose catalog'),
);
$this->pageTitle = Yii::t('config', 'Choose catalog');

        $this->admin_header = array();
        
        if (Yii::app()->getModule('katalogAccessories')->enabledModule) {
        	$this->admin_header[] = array(
                'name' => Yii::t('admin_layout', 'ACCESSORIES CATALOGUE'),
                'url' => array('/katalogAccessories/adminCathegorias/admin'),
                'active' => false,
            );
        }
        
        if (Yii::app()->getModule('katalogVavto')->enabledModule) {
        	$this->admin_header[] = array(
                'name' => Yii::t('admin_layout', 'Own catalog, SEO'),
                'url' => array('/katalogVavto/adminBrands/admin'),
                'active' => false,
            );
        }
        
        if (Yii::app()->getModule('tires')->enabledModule) {
        	$this->admin_header[] = array(
                'name' => Yii::t('tires', 'Tires catalog'),
                'url' => array('/tires/adminTires/admin'),
                'active' => false,
            );
        }
        
        if (Yii::app()->getModule('used')->enabledModule) {
        	$this->admin_header[] = array(
                'name' => Yii::t('used', 'Used catalog'),
                'url' => array('/used/admin'),
                'active' => false,
            );
        }
        
        if (Yii::app()->getModule('universal')->enabledModule) {
        	$this->admin_header[] = array(
                'name' => Yii::t('universal', 'Universal catalog'),
                'url' => array('/universal/admin/admin'),
                'active' => false,
            );
        }
        
        if (Yii::app()->getModule('masla')->enabledModule) {
        	$this->admin_header[] = array(
        			'name' => Yii::t('masla', 'Oil catalog'),
        			'url' => array('/masla/admin/'),
        			'active' => false,
        	);
        }

if (Yii::app()->getModule('katalogSeoBrands')->enabledModule) {
        $this->admin_header[] = array(
            'name' => Yii::t('katalogSeoBrands', 'Katalog SEO brands'),
            'url' => array('/katalogSeoBrands/admin/admin'),
            'active' => false,
        );
}
?>
<h1><?php echo Yii::t('config', 'Choose catalog') ?></h1>