<?php
	class UniversalView {
		public static function recreateView($id_razdel) {
			$razdel = UniversalRazdel::model()->findByPk($id_razdel);
			
			if (is_object($razdel) && ($alias = $razdel->alias)) {
				$adds1 = array();
				$adds2 = array();
				
				$chars = UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$razdel->primaryKey));
				$count = count($chars);
				for ($i = 0; $i < $count; $i ++) {
					$adds1[] = 'a'.$i.'.'.($chars[$i]->type == 1 ? 'value_string' : 'value_number').' AS chars'.$chars[$i]->primaryKey;
					$adds2[] = 'LEFT JOIN universal_products_chars a'.$i.' ON a.id = a'.$i.'.id_product AND a'.$i.'.id_chars = '.$chars[$i]->primaryKey;
				}
				
				self::deleteView($razdel->alias);
				
				$sql = 'CREATE VIEW universal_products_'.$razdel->alias.' AS SELECT a.*'.(count($adds1) ? ', '.implode(', ', $adds1) : '').' FROM universal_products a '.implode(' ', $adds2).' WHERE id_razdel = '.$id_razdel.';';
				
				//CDbCommand
				$command = Yii::app()->db->createCommand($sql)->query();
			}
		}
		
		public static function deleteView($alias) {
			$sql = 'DROP VIEW IF EXISTS universal_products_'.$alias;
			$command = Yii::app()->db->createCommand($sql)->query();
		}
	}
?>