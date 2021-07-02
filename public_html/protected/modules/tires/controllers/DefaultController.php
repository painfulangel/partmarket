<?php
class DefaultController extends TiresController {
	public $layout = '//layouts/column2';
	
	public function actionIndex() {
		$criteria = new CDbCriteria;

		$criteria->compare('active_state', 1);
		
		$filter = Yii::app()->request->getQuery('filter', array());
		
		if (count($filter)) {
			foreach ($filter as $id_property => $array) {
				if (count($array)) {
					switch (intval($id_property)) {
						case 2:
							if (is_array($array) && count($array)) $criteria->addInCondition('producer', $array);
						break;
						case 6:
							if (is_array($array) && count($array)) $criteria->addInCondition('seasonality', $array);
						break;
						case 3:
							if (is_array($array) && count($array)) $criteria->addInCondition('width', $array);
						break;
						case 4:
							if (is_array($array) && count($array)) $criteria->addInCondition('height', $array);
						break;
						case 5:
							if (is_array($array) && count($array)) $criteria->addInCondition('diameter', $array);
						break;
						case 1:
							if (is_array($array) && count($array)) $criteria->addInCondition('type', $array);
						break;
						case 7:
							if (is_array($array) && count($array)) $criteria->addInCondition('speed_index', $array);
						break;
						case 8:
							if (is_array($array) && count($array)) $criteria->addInCondition('shipp', $array);
						break;
						case 10:
							if (is_array($array) && count($array)) $criteria->addInCondition('axis', $array);
						break;
					}
				}
			}
		}
		
		//Min and max load index
		$item = TiresPropertyValues::model()->find(array('condition' => 'id_property = 9', 'select' => 'value_int_min', 'order' => 'value_int_min ASC'));
		$minLoadIndex = $item->value_int_min;
		
		$item = TiresPropertyValues::model()->find(array('condition' => 'id_property = 9', 'select' => 'value_int_max', 'order' => 'value_int_max DESC'));
		$maxLoadIndex = $item->value_int_max;
		
		$load_index_min = Yii::app()->request->getQuery('load_index_min', 0);
		$load_index_max = Yii::app()->request->getQuery('load_index_max', 0);
		
		if (($load_index_min && $load_index_min != $minLoadIndex) || ($load_index_max && $load_index_max != $maxLoadIndex)) {
			$data = TiresPropertyValues::model()->findAll(array('condition' => 'IF(value_int_max IS NULL, value_int_min BETWEEN '.$load_index_min.' AND '.$load_index_max.', value_int_min >= '.$load_index_min.' AND value_int_max <='.$load_index_max.')'));
			
			if ($count = count($data)) {
				$ids = array();
				
				for ($i = 0; $i < $count; $i ++) {
					$ids[] = $data[$i]->primaryKey;
				}
				
				$criteria->addInCondition('load_index', $ids);
			}
		}
		
		$currentPage = Yii::app()->request->getQuery('Tires_page', 1);
		
        $dataProvider = new CActiveDataProvider('Tires', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 21,
            	'currentPage' => ($currentPage - 1),
            ),
        ));
        
		$this->render('index', array(
			'dataProvider'   => $dataProvider,
			'dataProvider1'  => TiresPropertyValues::model()->getPopularDataProvider(1), //Type
			'dataProvider2'  => TiresPropertyValues::model()->getPopularDataProvider(2), //Brands
			'dataProvider3'  => TiresPropertyValues::model()->getPopularDataProvider(3), //Width
			'dataProvider4'  => TiresPropertyValues::model()->getPopularDataProvider(4), //Height
			'dataProvider5'  => TiresPropertyValues::model()->getPopularDataProvider(5), //Diameter
			'dataProvider6'  => TiresPropertyValues::model()->getPopularDataProvider(6), //Seasonality
			'dataProvider7'  => TiresPropertyValues::model()->getPopularDataProvider(7), //Speed_index
			'dataProvider8'  => TiresPropertyValues::model()->getPopularDataProvider(8), //Shipp
			'dataProvider9'  => TiresPropertyValues::model()->getPopularDataProvider(9), //Load_index
			'dataProvider10' => TiresPropertyValues::model()->getPopularDataProvider(10), //Axis
			
			'minLoadIndex'   => $minLoadIndex,
			'maxLoadIndex'   => $maxLoadIndex,
				
			'properties'     => TiresProperty::model()->findAll(),
		));
	}
	
	public function actionView($id) {
		$model = $this->loadModel($id);
		
		$this->render('view', array(
			'model' => $model,
		));
	}
	
	public function loadModel($id) {
		$model = Tires::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('tires', 'This page doesn\'t exist.'));
		return $model;
	}
}