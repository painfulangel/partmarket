<?php

/**
 * Created by PhpStorm.
 * User: foreach
 * Date: 23.11.16
 * Time: 22:55
 * Базовый контроллер для каталогов, выводит меню верхнего уровня
 */
class BaseCatalogController extends Controller
{
    public function init() {
        parent::init();
        
        $this->admin_header = array();
        
        if (Yii::app()->getModule('katalogAccessories')->enabledModule) {
        	$this->admin_header[] = array(
                'name' => Yii::t('admin_layout', 'ACCESSORIES CATALOGUE'),
                'url' => array('/katalogAccessories/adminCathegorias/admin'),
                'active' => $this->getModule()->id === 'katalogAccessories',
            );
        }
        
        if (Yii::app()->getModule('katalogVavto')->enabledModule) {
        	$this->admin_header[] = array(
                'name' => Yii::t('admin_layout', 'Own catalog, SEO'),
                'url' => array('/katalogVavto/adminBrands/admin'),
                'active' => $this->getModule()->id === 'katalogVavto',
            );
        }
        
        if (Yii::app()->getModule('tires')->enabledModule) {
        	$this->admin_header[] = array(
                'name' => Yii::t('tires', 'Tires catalog'),
                'url' => array('/tires/adminTires/admin'),
                'active' => $this->getModule()->id === 'tires',
            );
        }
        
        if (Yii::app()->getModule('used')->enabledModule) {
        	$this->admin_header[] = array(
                'name' => Yii::t('used', 'Used catalog'),
                'url' => array('/used/admin'),
                'active' => $this->getModule()->id === 'used',
            );
        }
        
        if (Yii::app()->getModule('universal')->enabledModule) {
        	$this->admin_header[] = array(
                'name' => Yii::t('universal', 'Universal catalog'),
                'url' => array('/universal/admin/admin'),
                'active' => $this->getModule()->id === 'universal',
            );
        }
        
        if (Yii::app()->getModule('masla')->enabledModule) {
        	$this->admin_header[] = array(
        			'name' => Yii::t('masla', 'Oil catalog'),
        			'url' => array('/masla/admin/'),
        			'active' => $this->getModule()->id === 'masla',
        	);
        }

        if (Yii::app()->getModule('katalogSeoBrands')->enabledModule) {
            $this->admin_header[] = array(
                'name' => Yii::t('katalogSeoBrands', 'Katalog SEO brands'),
                'url' => array('/katalogSeoBrands/admin/admin'),
                'active' => $this->getModule()->id === 'katalogSeoBrands',
            );
        }
    }
}