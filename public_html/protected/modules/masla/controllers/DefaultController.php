<?php
class DefaultController extends MaslaController {
	public $layout = '//layouts/column2';
	
	public function actionIndex() {
		$criteria = new CDbCriteria;
		
		$criteria->compare('active_state', 1);
		
		$p = null;
		$pid = intval(Yii::app()->request->getQuery('p', 0));
		if ($pid) {
			$p = MaslaProducers::model()->findByPk($pid);
			
			$criteria->join = 'JOIN `masla_producers_masla` `mpm` ON `t`.id = `mpm`.id_maslo AND `mpm`.id_producer = '.$pid;
		}
		
		$filter = Yii::app()->request->getQuery('filter', array());
		
		if (count($filter)) {
			foreach ($filter as $id_property => $array) {
				if (is_array($array) && count($array)) {
					$prop = MaslaProperty::model()->findByPk(intval($id_property));
					if (is_object($prop) && $prop->code) {
						$criteria->addInCondition($prop->code, $array);
					}
				}
			}
		}
		
		$currentPage = Yii::app()->request->getQuery('Masla_page', 1);
		
		$dataProvider = new CActiveDataProvider('Masla', array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 21,
				'currentPage' => ($currentPage - 1),
			),
		));
		
		$ps = array();
		$properties = MaslaProperty::model()->findAll(array('condition' => 'filter = 1', 'order' => 'type ASC, id ASC'));
		$count = count($properties);
		for ($i = 0; $i < $count; $i ++) {
			$ps[] = array('id' => $properties[$i]->primaryKey, 'name' => $properties[$i]->name, 'dp' => MaslaPropertyValues::model()->getPopularDataProvider($properties[$i]->primaryKey));
		}
		
		$this->render('index', array(
			'dataProvider' => $dataProvider,
			'labels'       => Masla::model()->attributeLabels(),
			'ps'		   => $ps,
			'properties'   => MaslaProperty::model()->findAll(),
			'p'            => $p,
		));
	}
	
	public function actionView($id) {
		$model = $this->loadModel($id);
		
		$this->render('view', array(
			'model'  => $model,
			'labels' => Masla::model()->attributeLabels(), 
		));
	}
	
	public function loadModel($id) {
		$model = Masla::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('tires', 'This page doesn\'t exist.'));
		return $model;
	}
}