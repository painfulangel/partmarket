<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsedController
 *
 * @author debian
 */
class UsedController extends BaseCatalogController
{
    //public $admin_header;

    //public $admin_subheader;

    public function init() {
        parent::init();

        $this->admin_subheader = array(
            array(
                'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Brands'),
                'url' => array('/used/brands/admin'),
                'active' => Yii::app()->controller->id == 'brands',
            ),
            array(
                'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Models'),
                'url' => array('/used/models/admin'),
                'active' => Yii::app()->controller->id == 'models',
            ),
            array(
                'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Modifications'),
                'url' => array('/used/modification/admin'),
                'active' => Yii::app()->controller->id == 'modification',
            ),
            array(
                'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Brand Items'),
                'url' => array('/used/brandsItems/admin'),
                'active' => Yii::app()->controller->id == 'brandsItems',
            ),
            array(
                'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Nodes'),
                'url' => array('/used/nodes/admin'),
                'active' => Yii::app()->controller->id == 'nodes',
            ),
            array(
                'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Units'),
                'url' => array('/used/units/admin'),
                'active' => Yii::app()->controller->id == 'units',
            ),
            array(
                'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Items'),
                'url' => array('/used/items/admin'),
                'active' => Yii::app()->controller->id == 'items',
            ),
            array(
                'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Export'),
                'url' => array('/used/items/export'),
                'active' => Yii::app()->controller->id == 'export',
            ),
            array(
                'name' => 'Перезаписать прайс-лист',
                'url' => array('/used/items/exportToPrices'),
                'active' => Yii::app()->controller->id == 'items',
            ),
        );
    }
}
