<?php
class Tires extends CMyActiveRecord {
	public $_image;
	public $imagePath = 'tires/';
	public $imagePathThumb = 'tires/thumb/';
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CMyActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return KatalogAccessoriesItems the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'tires';
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('name, article', 'required'),
				array('type, producer, width, height, diameter, seasonality, speed_index, shipp, load_index, axis, active_state', 'numerical', 'integerOnly' => true),
				array('name, article, meta_title, meta_keywords', 'length', 'max' => 255),
				array('description, meta_description, image', 'safe'),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('id, meta_title, meta_description, meta_keywords, name, article, type, producer, width, height, diameter, seasonality, speed_index, shipp, load_index, axis, active_state', 'safe', 'on' => 'search'),
		);
	}
	
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'typeObject' => array(self::HAS_ONE, 'TiresPropertyValues', array('id' => 'type')),
			'producerObject' => array(self::HAS_ONE, 'TiresPropertyValues', array('id' => 'producer')),
			'widthObject' => array(self::HAS_ONE, 'TiresPropertyValues', array('id' => 'width')),
			'heightObject' => array(self::HAS_ONE, 'TiresPropertyValues', array('id' => 'height')),
			'diameterObject' => array(self::HAS_ONE, 'TiresPropertyValues', array('id' => 'diameter')),
			'seasonalityObject' => array(self::HAS_ONE, 'TiresPropertyValues', array('id' => 'seasonality')),
			'speedIndexObject' => array(self::HAS_ONE, 'TiresPropertyValues', array('id' => 'speed_index')),
			'shippObject' => array(self::HAS_ONE, 'TiresPropertyValues', array('id' => 'shipp')),
			'loadIndexObject' => array(self::HAS_ONE, 'TiresPropertyValues', array('id' => 'load_index')),
			'axisObject' => array(self::HAS_ONE, 'TiresPropertyValues', array('id' => 'axis')),
		);
	}
	
	public function afterSave() {
		parent::afterSave();
		
		if (Yii::app()->request->getPost('delete_image', 0) && $this->image) {
			unlink($this->getAbsolutePathImage().$this->image);
			
			self::model()->updateByPk($this->primaryKey, array('image' => NULL));
		} else {
			$this->_image = CUploadedFile::getInstance($this, '_image');
			
			if ($this->_image != null) {
				$filename = pathinfo($this->_image->getName());
				$extension = $filename['extension'];
				$filename = $this->primaryKey.'.'.$extension;
				$this->_image->saveAs($this->getAbsolutePathImage().$filename);
	
				//@unlink($this->getAbsolutePathImage().$this->image);
				
				self::model()->updateByPk($this->primaryKey, array('image' => $filename));
			}
		}
	}
	
	public function getAbsolutePathImage() {
		return realpath(Yii::app()->basePath.'/..'.'/images/'.$this->imagePath.'/').'/';
	}
	
	public function getImage($just_name = false) {
		if (!$just_name) {
			if (!empty($this->image) && file_exists($this->getAbsolutePathImage().$this->image))
				return '/images/'.$this->imagePath.'/'.$this->image;
			else
				return '';
		}else {
			if (!empty($this->image) && file_exists($this->getAbsolutePathImage().$this->image))
				return $this->image;
			else
				return '';
		}
	}
	
	public function getAttachment() {
		$thumb = $this->getThumb();
		
		return substr($thumb, 1);
	}
	
	public function getThumb($height = 200) {
		if ($this->image) {
			$sizes = getimagesize(YiiBase::getPathOfAlias('webroot').$this->getImage());
		
			$width = ceil($sizes[0] * $height / $sizes[1]);
			//$height = ceil($width * $sizes[1] / $sizes[0]);
		
			return ImageFly::Instance()->get($this, 'image', $width, $height);
		}
		
		return '';
	}
	
	public function attributeLabels() {
		return array(
				'id' => 'ID',
				'name' => Yii::t('tires', 'Name'),
				'article' => Yii::t('tires', 'Article'),
				'description' => Yii::t('tires', 'Description'),
				'type' => Yii::t('tires', 'Type'),
				'producer' => Yii::t('tires', 'Producer'),
				'width' => Yii::t('tires', 'Width'),
				'height' => Yii::t('tires', 'Height'),
				'diameter' => Yii::t('tires', 'Diameter'),
				'seasonality' => Yii::t('tires', 'Seasonality'),
				'speed_index' => Yii::t('tires', 'Speed index'),
				'shipp' => Yii::t('tires', 'Shipp'),
				'load_index' => Yii::t('tires', 'Load index'),
				'axis' => Yii::t('tires', 'Axis'),
				'image' => Yii::t('tires', 'Picture'),
				'_image' => Yii::t('tires', 'Picture'),
				'meta_title' => Yii::t('tires', 'Meta-header'),
				'meta_description' => Yii::t('tires', 'Description page'),
				'meta_keywords' => Yii::t('tires', 'Keywords'),
				'active_state' => Yii::t('tires', 'Active state'),
		);
	}
	
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria = new CDbCriteria;
		
		$criteria->compare('id', $this->id);
		$criteria->compare('type', $this->type, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('article', $this->article, true);
		$criteria->compare('producer', $this->producer, true);
		$criteria->compare('active_state', $this->active_state);
		
		if ($this->image == 1) {
			$criteria->addCondition("image IS NOT NULL");
		} else if ($this->image == 2)
			$criteria->addCondition("image IS NULL");
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}
}