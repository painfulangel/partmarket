<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KatalogAccessoriesConfig
 *
 * @author Sergij
 */
class KatalogAccessoriesConfig {

    public function run() {
        Yii::app()->getModule('katalogAccessories')->updatePathsMap();
    }

}
