<?php
class UniversalProduct extends CMyActiveRecord {
	private $data = array();
	private $labels = array();
	
	public $_image;
	public $imagePath = 'universal/';
	public $imagePathThumb = 'universal/thumb/';
	
	public $priceExport = false;
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'universal_products';
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_razdel, name, article', 'required'),
			array('id_razdel', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 255),
			array('meta_keywords, meta_description, meta_title, anons, content, analogs, image', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_razdel, name, article, meta_title, meta_description, meta_keywords, anons, content, analogs, active_state', 'safe', 'on' => 'search'),
		);
	}
	
	public function attributeLabels() {
		if (count($this->labels) == 0) {
			$this->labels = $this->getDefaultLabels();
		}
		
		return $this->labels;
	}
	
	public function addAttributeLabel($code, $value) {
		if (count($this->labels) == 0) {
			$this->labels = $this->getDefaultLabels();
		}
		
		$this->labels[$code] = $value;
	}
	
	private function getDefaultLabels() {
		return array(
			'id' => 'ID',
			'name' => Yii::t('universal', 'Name'),
			'article' => Yii::t('universal', 'Article'),
			'meta_title' => Yii::t('universal', 'Meta-header'),
			'meta_description' => Yii::t('universal', 'Description page'),
			'meta_keywords' => Yii::t('universal', 'Keywords'),
			'anons' => Yii::t('universal', 'Anons'),
			'content' => Yii::t('universal', 'Content'),
			'analogs' => Yii::t('universal', 'Аналоги'),
			'active_state' => Yii::t('universal', 'Active state'),
			'image' => Yii::t('universal', 'Main image'),
			'_image' => Yii::t('universal', 'Main image'),
		);
	}
	
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.
	
		$criteria = new CDbCriteria;
		
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('article', $this->article, true);
		$criteria->compare('active_state', $this->active_state, true);
		$criteria->compare('id_razdel', $this->id_razdel, true);
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}
	
	public function behaviors() {
		return array(
			'galleryBehavior' => array(
				'class' => 'GalleryBehavior',
				'idAttribute' => 'gallery_id',
				// 'imagePath' => 'images/katalog/katalogAccessories/'.$this->alias,
				'versions' => array(
					'small' => array(
						'centeredpreview' => array(200, 200), //array(98, 98),
					),
					'medium' => array(
						'resize' => array(800, null),
					)
				),
				'name' => false,
				'description' => false,
			),
		);
	}
	
	public function __get($property) {
		if (array_key_exists($property, $this->data)) {
			return $this->data[$property];
		} else {
			return parent::__get($property);
		}
	}
	
	public function __set($property, $value) {
		if (strpos($property, 'char') !== false) {
			$this->data[$property] = $value;
		} else {
			parent::__set($property, $value);
		}
	}
	
	public function afterSave() {
		parent::afterSave();
		
		if ($this->priceExport == true) return;
		
		//Main image
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
		//Main image
		
		//Characteristics
		if (!$this->isNewRecord) UniversalProductChars::model()->deleteAll('id_product = '.$this->primaryKey);
		
		$post = Yii::app()->request->getPost(get_class($this), array());
		
		$chars = UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.intval($this->id_razdel), 'select' => 'id, type'));
		foreach ($chars as $char) {
			$value = '';
			if ($char->type != 1) $value = 0;
			
			$value = '';
			
			if (array_key_exists('char'.$char->primaryKey, $post)) $value = $post['char'.$char->primaryKey];
			
			$condition = $value != '';
			if ($char->type != 1) {
				$value = intval($value);
				$condition = $value != 0;
			}
			
			//echo $char->primaryKey.' - '.$value.'<br>';
			
			if ($condition) {
				$item = new UniversalProductChars();
				
				$item->id_product = $this->primaryKey;
				$item->id_chars = $char->primaryKey;
				
				if ($char->type == 1) {
					$item->value_string = $value;
				} else {
					$item->value_number = $value;
				}
				
				//echo '<span style="color: red;">'.$item->value_string.' - '.$item->value_number.'</span><br>';
				
				$item->save();
			}
		}
		//Characteristics
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
	
	public function getImages() {
		$items = array();
		
		foreach ($this->galleryBehavior->getGallery()->galleryPhotos as $photo) {
			if (empty($photo->name)) $photo->name = $this->name;
			$items[] = array(
				'id' => $photo->id,
				'title' => (string) $photo->name,
				'image' => $photo->getUrl(),
				'thumb' => $photo->getPreview(),
			);
		}
		
		return $items;
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
	
	public function afterDelete() {
		UniversalProductChars::model()->deleteAll('id_product = '.$this->primaryKey);
		
		return parent::afterDelete();
	}
}