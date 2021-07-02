<?php
class KatalogController extends Controller {
	public $layout = '//layouts/column2';
	
	public function actionIndex() {
		/*if (array_key_exists('i', $_GET)) {
			$cats = array();

			$data = KatalogSeoBrandsCategory::model()->findAll();
			$count = count($data);
			for ($i = 0; $i < $count; $i ++) {
				$cat = $data[$i]->name;
				$cat2 = preg_replace('/[^a-zа-я\d]+/u', '', $cat);

				$cats[$cat2] = $data[$i];
			}

			$items = KatalogSeoBrandsItems::model()->findAll();

			$count = count($items);
			for ($i = 0; $i < $count; $i ++) {
				$name = $this->my_mb_ucfirst($items[$i]->name);

				$parts = array_values(array_diff(explode(' ', $name), array('', ' ')));

				if (count($parts)) {
					$cat = $parts[0].(array_key_exists(1, $parts) ? ' '.$parts[1] : '');
					$cat2 = preg_replace('/[^a-zа-я\d]+/u', '', $cat);

					if (!array_key_exists($cat2, $cats)) {
						$c = new KatalogSeoBrandsCategory();
						$c->name = $cat;
						$c->url = $this->translit($cat);
						$c->save();

						$id_category = $c->primaryKey;

						$cats[$cat2] = $c;
					} else {
						$id_category = $cats[$cat2]->primaryKey;
					}

					$items[$i]->category_id = $id_category;
					$items[$i]->save();
				}
			}

			//echo '<pre>'; print_r(array_values($cats)); echo '</pre>'; exit;
		}*/

		$criteria = new CDbCriteria;
		$criteria->order = 'brand ASC, main DESC';
		$currentPage = Yii::app()->request->getQuery('KatalogSeoBrandsBrands_page', 1);
		
		$dataProvider = new CActiveDataProvider('KatalogSeoBrandsBrands', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 160,
            	'currentPage' => ($currentPage - 1),
            ),
        ));

        $settings = KatalogSeoBrandsSettings::model()->find();

		$this->render('index', array('dataProvider' => $dataProvider, 'settings' => $settings));
	}

	public function actionBrand($brand) {
		//Выбрать категории бренда
		$ids = array();

		$brand = str_replace('__', '/', $brand);

		$criteria = new CDbCriteria;
		$criteria->distinct = true;
		$criteria->select = 'category_id';
		$criteria->compare('brand', $brand);

		$items = KatalogSeoBrandsItems::model()->findAll($criteria);
		$count = count($items);
		for ($i = 0; $i < $count; $i ++) {
			$ids[] = $items[$i]->category_id;
		}

		if (count($ids)) {
			$criteria = new CDbCriteria;
			$criteria->condition = 'id IN('.implode(', ', $ids).')';
			$criteria->order = 'name ASC';
			
			$currentPage = Yii::app()->request->getQuery('KatalogSeoBrandsCategory_page', 1);
		
			$dataProvider = new CActiveDataProvider('KatalogSeoBrandsCategory', array(
	            'criteria' => $criteria,
	            'pagination' => array(
	                'pageSize' => 1000,
	            	'currentPage' => ($currentPage - 1),
	            ),
	        ));

        	$settings = KatalogSeoBrandsSettings::model()->find();
        	$settings->replaceBrand($brand);

			$this->render('brand', array('brand' => $brand, 'model' => new KatalogSeoBrandsCategory(), 'dataProvider' => $dataProvider, 'settings' => $settings));
		} else {
			throw new CHttpException(404, Yii::t('katalogVavto', 'This Page not found.'));
		}
	}

	public function actionCategory($brand, $category) {
		$brand = str_replace('__', '/', $brand);

		$criteria = new CDbCriteria;
		$criteria->compare('brand', $brand);

		$cat = KatalogSeoBrandsCategory::model()->findByAttributes(array('url' => $category));

		if (!is_object($cat))
			throw new CHttpException(404, Yii::t('katalogVavto', 'This Page not found.'));

		$criteria->compare('category_id', $cat->primaryKey);

		$currentPage = Yii::app()->request->getQuery('KatalogSeoBrandsItems_page', 1);
		
		$dataProvider = new CActiveDataProvider('KatalogSeoBrandsItems', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 15,
            	'currentPage' => ($currentPage - 1),
            ),
        ));

        $settings = KatalogSeoBrandsSettings::model()->find();
        $settings->replaceBrandCategory($brand, $cat->name);

		$this->render('category', array('brand' => $brand, 'category' => $cat, 'model' => new KatalogSeoBrandsItems(), 'dataProvider' => $dataProvider, 'settings' => $settings));
	}

	public function actionArticle($brand, $category, $article) {
		$brand = str_replace('__', '/', $brand);

		$criteria = new CDbCriteria;
		$criteria->compare('brand', $brand);
		$criteria->compare('article', $article);

		$cat = KatalogSeoBrandsCategory::model()->findByAttributes(array('url' => $category));

		if (!is_object($cat))
			throw new CHttpException(404, Yii::t('katalogVavto', 'This Page not found.'));

		$criteria->compare('category_id', $cat->primaryKey);

		$currentPage = Yii::app()->request->getQuery('KatalogSeoBrandsItems_page', 1);
		
		$model = KatalogSeoBrandsItems::model()->find($criteria);

        $settings = KatalogSeoBrandsSettings::model()->find();
        $settings->replaceAll($model);

		$this->render('article', array('brand' => $brand, 'category' => $cat, 'article' => $article, 'model' => $model, 'settings' => $settings));
	}
}
?>