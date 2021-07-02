<?php
class UniversalRazdel extends CMyActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'universal_razdel';
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, alias', 'required'),
			array('active_state', 'numerical', 'integerOnly' => true),
			array('name, alias', 'length', 'max' => 255),
			array('meta_keywords, meta_description, meta_title', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, meta_title, meta_description, meta_keywords, name, alias, active_state', 'safe', 'on' => 'search'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => Yii::t('universal', 'Name'),
			'alias' => Yii::t('universal', 'Alias'),
			'meta_title' => Yii::t('universal', 'Meta-header'),
			'meta_description' => Yii::t('universal', 'Description page'),
			'meta_keywords' => Yii::t('universal', 'Keywords'),
			'active_state' => Yii::t('universal', 'Active state'),
		);
	}
	
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria = new CDbCriteria;
		
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}
	
	public function getUrl() {
		return '/universal/'.$this->alias.'/';
	}
	
	public function afterSave() {
		parent::afterSave();
		
		if ($this->isNewRecord) UniversalView::recreateView($this->primaryKey);
	}
	
	public function afterDelete() {
		$chars = UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$this->primaryKey));
		
		$count = count($chars);
		for ($i = 0; $i < $count; $i ++) {
			$chars[$i]->delete();
		}
		
		$products = UniversalProduct::model()->findAll(array('condition' => 'id_razdel = '.$this->primaryKey));
		
		$count = count($products);
		for ($i = 0; $i < $count; $i ++) {
			$products[$i]->delete();
		}
		
		UniversalView::deleteView($this->alias);
	
		return parent::afterDelete();
	}
	
	public function export() {
		$export = ' ID;'.
				  Yii::t('universal', 'Name').';'.
				  Yii::t('universal', 'Article').';'.
				  Yii::t('universal', 'Meta-header').';'.
				  Yii::t('universal', 'Description page').';'.
				  Yii::t('universal', 'Keywords').';'.
				  Yii::t('universal', 'Anons').';'.
				  Yii::t('universal', 'Content').';'.
				  Yii::t('universal', 'Аналоги');
		
		$chars = UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$this->primaryKey, 'order' => 'id ASC'));
		
		$count = count($chars);
		for ($i = 0; $i < $count; $i ++) {
			$export .= ';'.$chars[$i]->name;
		}
		
		$export .= "\n";
		
		$products = UniversalProductBase::modelBase('universal_products_'.$this->alias)->findAll();
		$count2 = count($products);
		for ($i = 0; $i < $count2; $i ++) {
			$p = $products[$i];
			$export .= $p->id.';'.
					   $this->strip($p->name).';'.
					   $this->strip($p->article).';'.
					   $this->strip($p->meta_title).';'.
					   $this->strip($p->meta_description).';'.
					   $this->strip($p->meta_keywords).';'.
					   $this->strip($p->anons).';'.
					   $this->strip($p->content).';'.
					   $this->strip($p->analogs);
			
			for ($j = 0; $j < $count; $j ++) {
				$name = 'chars'.$chars[$j]->primaryKey;
				
				switch (intval($chars[$j]->type)) {
					case 2:
					case 4:
						$value = $chars[$j]->getListValue($p->{$name});
					break;
					case 6:
						$value = $p->{$name} == 1 ? Yii::t('universal', 'Yes') : Yii::t('universal', 'No');
					break;
					default:
						$value = $p->{$name};
					break;
				}
				
				$export .= ';'.$this->strip($value);
			}
			
			$export .= "\n";
		}
		
		return iconv('UTF-8', 'cp1251', $export);
	}
	
	public function import($filename, $id_razdel) {
		$file = file($filename);
		
		$n = count($file);
		$values = '';
		
		$z = 0;
		$ids = array();
		if ($n > 1) {
			$separator = ";";
			$string = explode($separator, trim($file[0]));
			if (count($string) < 9) $separator = "\t";
		
			for ($i = 1; $i < $n; $i++) {
				if (strlen($file[$i]) < 9) continue;
				$file[$i] = iconv('windows-1251', 'utf-8', $file[$i]);
				$string = explode($separator, trim($file[$i]));
				if (count($string) < 9) continue;
		
				$product = new UniversalProduct();
				if (intval($string[0])) {
					$product = UniversalProduct::model()->findByPk(intval($string[0]));
				}
				
				$product->id_razdel = $id_razdel;
				$product->name = $string[1];
				$product->article = $string[2];
				$product->meta_title = $string[3];
				$product->meta_description = $string[4];
				$product->meta_keywords = $string[5];
				$product->anons = $string[6];
				$product->content = $string[7];
				$product->analogs = $string[8];
				$product->active_state = 1;
				$product->priceExport = true;
				
				if ($product->save() && (count($string) > 9)) {
					if (!$product->isNewRecord) UniversalProductChars::model()->deleteAll('id_product = '.$product->primaryKey);
					
					$chars = UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.intval($id_razdel), 'order' => 'id ASC'));
					
					$ij = 9;
					
					foreach ($chars as $char) {
						$value = $string[$ij];
						
						switch ($char->type) {
							case 2:
							case 4:
								$values = $char->getValues();
								
								if (in_array($value, $values)) {
									$value = array_search($value, $values);
								} else {
									$lv = new UniversalCharsListValues();
									$lv->id_chars = $char->primaryKey;
									
									if ($char->type == 2) {
										$lv->value_string = $value;
									} else {
										$lv->value_number = $value;
									}
									
									if ($lv->save()) {
										$value = $lv->primaryKey;
									}
								}
							break;
							case 6:
								$value = ($value == Yii::t('universal', 'Yes')) ? 1 : 0;
							break;
						}
						
						if ($value) {
							$item = new UniversalProductChars();
								
							$item->id_product = $product->primaryKey;
							$item->id_chars = $char->primaryKey;
								
							if ($char->type == 1) {
								$item->value_string = $value;
							} else {
								$item->value_number = $value;
							}
							
							$item->save();
						}
						
						$ij ++;
					}
				}
				
				/*$ids[] = 'id!='.$model->id;*/
			}
			//exit;
			//$db = Yii::app()->db;
			//$data = $db->createCommand('DELETE FROM  '.$this->tableName().'  WHERE '.implode(' AND ', $ids))->query();
		}
	}
	
	private function strip($value) {
		return str_replace(array("\r", "\n", ";"), '', $value);
	}
}