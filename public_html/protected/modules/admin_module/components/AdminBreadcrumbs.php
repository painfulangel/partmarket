<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminBreadcrumbs
 *
 * @author Sergij
 */
class AdminBreadcrumbs {

    public static function get($array) {
        return array_merge(array(Yii::t('admin_module', 'Administration') => array('/admin_module/default/index')), $array);
    }

}
