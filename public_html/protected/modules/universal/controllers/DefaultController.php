<?php
class DefaultController extends UniversalController {
	public $layout = '//layouts/column2';
	
	public function actionIndex($alias) {
		$razdel = $this->getRazdel($alias);
		//echo CVarDumper::dump($razdel,10,true);exit;
		
		$criteria = new CDbCriteria;
		
		$filter_values = array();
		
		$filter_chars = UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$razdel->primaryKey.' AND filter_main = 1', 'order' => '`order` ASC'));
        //echo CVarDumper::dump($filter_chars,10,true);exit;
		$count = count($filter_chars);
		for ($i = 0; $i < $count; $i ++) {
			$request = Yii::app()->request;
			
			$name = 'chars'.$filter_chars[$i]->primaryKey;
			
			switch ($filter_chars[$i]->type) {
				case 1:
					$value = trim($request->getQuery($name, ''));
					
					if ($value) {
						$criteria->addSearchCondition($name, $value);
						
						$filter_values[$name] = $value;
					}
				break;
				case 2:
				case 4:
					if ($filter_chars[$i]->filter_view == 1) {
						$value = intval($request->getQuery($name, 0));
						
						if ($value) {
							$criteria->addCondition($name.' = '.$value);
						
							$filter_values[$name] = $value;
						}
					} else {
						$value = array_diff($request->getQuery($name, array()), array(''));
						
						if (count($value)) {
							$criteria->addInCondition($name, $value);
						
							$filter_values[$name] = $value;
						}
					}
				break;
				case 5:
					$min = intval($request->getQuery($name.'min', 0));
					$max = intval($request->getQuery($name.'max', 0));
					
					if ($min && $max) {
						$filter_values[$name.'min'] = $min;
						$filter_values[$name.'max'] = $max;
						
						$criteria->addBetweenCondition($name, $min, $max);
					}
				break;
				case 6:
					$value = intval($request->getQuery($name, 0));
					
					if ($value) {
						$filter_values[$name] = $value;
						
						if ($value == 1) {
							$criteria->addCondition($name.' = 1');
						} else {
							$criteria->addCondition($name.' IS NULL');
						}
					}
				break;
			}
		}
		
		//echo '<pre>'; print_r($filter_values); echo '</pre>'; exit;
		
		$model = UniversalProductBase::modelBase('universal_products_'.$razdel->alias, array(array('chars2', 'length', 'max' => 255), array('chars2', 'safe'), array('chars2', 'safe', 'on' => 'search')));
        //echo CVarDumper::dump($model,10,true);exit;
		$dataProvider = new CActiveDataProvider($model,
			array(
				'criteria' => $criteria,
				'pagination'=>array(
						'pageSize' => 21,
						//'currentPage' => ($currentPage - 1),
				),
			)
		);
        //echo CVarDumper::dump(UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$razdel->primaryKey, 'order' => '`order` ASC')),10,true);exit;
		
		$this->render('index', array('razdel'        => $razdel, 
									 'chars'         => UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$razdel->primaryKey, 'order' => '`order` ASC')),
									 'filter_chars'  => $filter_chars,
									 'filter_values' => $filter_values,
									 'dataProvider'  => $dataProvider));
	}
	
	public function actionView($alias, $id) {
		$model = UniversalProductBase::modelBase('universal_products_'.$alias)->findByAttributes(array('id' => $id));
		
		if (!is_object($model)) throw new CHttpException(400, Yii::t('universal', 'This page doesn\'t exist.'));
		
		$razdel = $this->getRazdel($alias);
		
		$this->render('view', array('model'  => $model, 
									'razdel' => $razdel,
									'chars'  => UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$razdel->primaryKey, 'order' => '`order` ASC'))));
	}
	
	public function actionFilter($alias) {
		$razdel = $this->getRazdel($alias);
		
		$filter_chars = UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$razdel->primaryKey.' AND filter = 1', 'order' => '`order` ASC'));
		
		$this->render('filter', array('razdel'       => $razdel,
									 'filter_chars' => $filter_chars));
	}
	
	private function getRazdel($alias) {
		$razdel = UniversalRazdel::model()->findByAttributes(array('alias' => $alias));
		
		if (($alias == '') || !is_object($razdel)) throw new CHttpException(400, Yii::t('universal', 'This page doesn\'t exist.'));
		
		return $razdel;
	}
}