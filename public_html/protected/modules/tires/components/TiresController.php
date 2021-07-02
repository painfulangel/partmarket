<?php
class TiresController extends BaseCatalogController {
	protected function beforeAction($action) {
		if (!Yii::app()->getModule('tires')->enabledModule) {
			throw new CHttpException(404, Yii::t('tires', 'This page doesn\'t exist.'));
		}
	
		return true;
	}
}