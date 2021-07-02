<?php
class UniversalController extends BaseCatalogController {
	protected function beforeAction($action) {
		if (!Yii::app()->getModule('universal')->enabledModule) {
			throw new CHttpException(404, Yii::t('universal', 'This page doesn\'t exist.'));
		}
	
		return true;
	}
}