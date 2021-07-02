<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class UrlManager extends CUrlManager {
    public function createUrl($route, $params =  array(), $ampersand = '&') {
        return parent::createUrl($route, array_merge(array('_lang' => Yii::app()->language), $params), $ampersand);
    }
}
?>
