<?php
	class BrandsController extends Controller {
		public $layout = '//layouts/empty';

		public function actionIndex($brand) {
			$brand = mb_strtolower(trim($brand));

			$data = Brands::model()->find(array('condition' => 'LOWER(name) = "'.$brand.'" OR LOWER(synonym) LIKE "%'.$brand.'%"'));

			if (!is_object($data)) {
				throw new CHttpException(404, Yii::t('prices', 'This page doesn\'t exist.'));
			} else {
				$this->render('index', array('model' => $data));
			}
		}
	}
?>