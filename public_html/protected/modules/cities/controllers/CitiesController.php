<?php
class CitiesController extends Controller {
	public $layout = '//layouts/empty';

	public function actionIndex() {
		$this->render('index', array('model' => Cities::model()->findAll(array('order' => 'name ASC'))));
	}

	public function actionSetCity($city) {
		$item = Cities::model()->findByPk(intval($city));

		if (is_object($item)) {
			if (!Yii::app()->user->isGuest) {
	            $uid = Yii::app()->user->id;
	            $model = UserProfile::model()->findByAttributes(array('uid' => $uid));

	            if (is_object($model)) {
	                $model->city = $city;
	                $model->save();
	            }
	        }
		}

		echo json_encode(array('name' => $item->name, 'phone' => $item->phone, 'email' => $item->email));
	}
}