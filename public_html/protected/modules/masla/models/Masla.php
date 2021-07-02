<?php
class Masla extends CMyActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'masla';
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, article', 'required'),
			array('country, producer, scope, sae, engine_type, fuel_type, oil_type, api, ilsac, iso, acea, jaso, density, temp_harden, color, index_viscosity, viscosity_forty, viscosity_hundred, temp_flash, alkali_number, temp_loss_fluidity, temp_boiling, sulphate_ash, total_acid_number, viscosity_seeming, evaporability, sulfur, zinc, phosphorus, molybdenum, boron, magnesium, calcium, silicon, sodium, viscosity_seeming_35, ph, barium, aluminum, iron, potassium, active_state', 'numerical', 'integerOnly' => true),
			array('name, article, meta_title, meta_keywords', 'length', 'max' => 255),
			array('description, meta_description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, meta_title, meta_description, meta_keywords, name, article, country, producer, scope, sae, engine_type, fuel_type, oil_type, api, ilsac, iso, acea, jaso, density, temp_harden, color, index_viscosity, viscosity_forty, viscosity_hundred, temp_flash, alkali_number, temp_loss_fluidity, temp_boiling, sulphate_ash, total_acid_number, viscosity_seeming, evaporability, sulfur, zinc, phosphorus, molybdenum, boron, magnesium, calcium, silicon, sodium, viscosity_seeming_35, ph, barium, aluminum, iron, potassium, active_state', 'safe', 'on' => 'search'),
		);
	}
	
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'countryObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'country')),
			'producerObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'producer')),
			'scopeObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'scope')),
			'saeObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'sae')),
			'engineTypeObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'engine_type')),
			'fuelTypeObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'fuel_type')),
			'oilTypeObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'oil_type')),
			'apiObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'api')),
			'ilsacObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'ilsac')),
			'isoObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'iso')),
			'aceaObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'acea')),
			'jasoObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'jaso')),
			
			'densityObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'density')),
			'tempHardenObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'temp_harden')),
			'colorObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'color')),
			'indexViscosityObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'index_viscosity')),
			'viscosityFortyObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'viscosity_forty')),
			'viscosityHundredObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'viscosity_hundred')),
			'tempFlashObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'temp_flash')),
			'alkaliNumberObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'alkali_number')),
			'tempLossFluidityObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'temp_loss_fluidity')),
			'tempBoilingObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'temp_boiling')),
			'sulphateAshObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'sulphate_ash')),
			'totalAcidNumberObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'total_acid_number')),
			'viscositySeemingObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'viscosity_seeming')),
			'evaporabilityObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'evaporability')),
			'sulfurObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'sulfur')),
			'zincObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'zinc')),
			'phosphorusObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'phosphorus')),
			'molybdenumObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'molybdenum')),
			'boronObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'boron')),
			'magnesiumObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'magnesium')),
			'calciumObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'calcium')),
			'siliconObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'silicon')),
			'sodiumObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'sodium')),
			'viscositySeemingObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'viscosity_seeming_35')),
			'phObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'ph')),
			'bariumObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'barium')),
			'aluminumObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'aluminum')),
			'ironObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'iron')),
			'potassiumObject' => array(self::HAS_ONE, 'MaslaPropertyValues', array('id' => 'potassium')),
			
			'volumes' => array(self::HAS_MANY, 'MaslaVolume', array('id_maslo' => 'id')),
			'files' => array(self::HAS_MANY, 'MaslaFiles', array('id_maslo' => 'id')),
			'producers' => array(self::MANY_MANY, 'MaslaProducers', 'masla_producers_masla(id_maslo, id_producer)'),
		);
	}
	
	public function attributeLabels() {
		$additional = array();
		
		$ps = MaslaProperty::model()->findAll();
		$count = count($ps);
		for ($i = 0; $i < $count; $i ++) {
			$additional[$ps[$i]->code] = $ps[$i]->name;
		}
		
		return array_merge(array(
			'id' => 'ID',
			'name' => Yii::t('masla', 'Name'),
			'article' => Yii::t('masla', 'Article'),
			'description' => Yii::t('masla', 'Description'),
			'meta_title' => Yii::t('masla', 'Meta-header'),
			'meta_description' => Yii::t('masla', 'Description page'),
			'meta_keywords' => Yii::t('masla', 'Keywords'),
			'active_state' => Yii::t('masla', 'Active state'),
		), $additional);
	}
	
	public function afterSave() {
		parent::afterSave();

		$imagePath = MaslaVolume::model()->getAbsolutePathImage();

		$volume = Yii::app()->request->getPost('volume', array());
		$volume_id = Yii::app()->request->getPost('volume_id', array());
		
		if (array_key_exists('image', $_FILES) && array_key_exists('name', $_FILES['image'])) {
			foreach ($_FILES['image']['name'] as $id => $name) {
				if (($_FILES['image']['error'][$id] == UPLOAD_ERR_OK) && array_key_exists($id, $volume) && $volume[$id]) {
					$pathinfo = pathinfo($name);
					
					$image = md5(time() * rand()).'.'.$pathinfo['extension'];
					
					move_uploaded_file($_FILES['image']['tmp_name'][$id], $imagePath.$image);
					
					if (in_array($id, $volume_id)) {
						//Upload image
						MaslaVolume::model()->updateByPk($id, array('volume' => $volume[$id], 'image' => $image));
					} else {
						//New image
						$mv = new MaslaVolume();
						$mv->id_maslo = $this->primaryKey;
						$mv->volume = $volume[$id];
						$mv->image = $image;
						$mv->save();
					}
				}
			}
		}
		
		foreach ($volume as $id => $value) {
			if (in_array($id, $volume_id)) {
				MaslaVolume::model()->updateByPk($id, array('volume' => $value));
			}
		}
		
		$delete_image = Yii::app()->request->getPost('delete_image', array());
		if (is_array($delete_image) && count($delete_image)) {
			foreach ($delete_image as $id) {
				$model = MaslaVolume::model()->findByPk($id);
				if (is_object($model)) {
					if (file_exists($imagePath.$model->image)) unlink($imagePath.$model->image);
					
					$model->image = null;
					$model->save();
				}
			}
		}
		
		$delete_volume = Yii::app()->request->getPost('delete_volume', array());
		if (is_array($delete_volume) && count($delete_volume)) {
			foreach ($delete_volume as $id) {
				$model = MaslaVolume::model()->findByPk($id);
				if (is_object($model)) {
					if (file_exists($imagePath.$model->image)) unlink($imagePath.$model->image);
						
					$model->delete();
				}
			}
		}
	}
	
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria = new CDbCriteria;
	
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('article', $this->article, true);
		
		$criteria->compare('country', $this->country);
		$criteria->compare('producer', $this->producer);
		$criteria->compare('engine_type', $this->engine_type);
		$criteria->compare('fuel_type', $this->fuel_type);
		$criteria->compare('oil_type', $this->oil_type);
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}
}