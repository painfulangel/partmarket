<?php
class MaslaController extends BaseCatalogController {
	protected function beforeAction($action) {
		if (!Yii::app()->getModule('masla')->enabledModule) {
			throw new CHttpException(404, Yii::t('masla', 'This page doesn\'t exist.'));
		}
	
		return true;
	}
}