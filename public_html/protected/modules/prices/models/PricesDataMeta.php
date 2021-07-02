<?php
class PricesDataMeta extends CMyActiveRecord {
    public $_image = '';
    
    public $imagePath = 'PricesDataMeta/';
    public $imagePathThumb = 'PricesDataMeta/thumb/';
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
        return 'prices_data_meta';
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('article', 'required'),
            array('_image', 'file', 'allowEmpty' => true, 'types' => 'gif, jpg, png', 'maxSize' => 2621440,),
            array('brand, article, article_search, content, meta_title, meta_description, meta_keywords', 'safe'),
			array('brand, article, article_search, content, image, _image, meta_title, meta_description, meta_keywords', 'safe', 'on' => 'search'),
		);
	}
	
	public function attributeLabels() {
		return array(
				'id' => 'ID',
				'brand' => Yii::t('prices', 'Meta brand'),
				'article' => Yii::t('prices', 'Meta articul'),
				'content' => Yii::t('prices', 'Meta content'),
				'image' => Yii::t('prices', 'Meta image'),
				'_image' => Yii::t('prices', 'Meta image'),
				'meta_title' =>Yii::t('prices', 'Meta title') ,
				'meta_description' => Yii::t('prices', 'Meta description'),
				'meta_keywords' => Yii::t('prices','Meta keywords'),
		);
	}
	
	public function beforeSave() {
		if (parent::beforeSave()) {
			$this->_image = CUploadedFile::getInstance($this, '_image');
	
			if ($this->_image != NULL) {
				$filename = pathinfo($this->_image->getName());
				$extension = $filename['extension'];
				$filename = md5(time().rand()).'.'.$extension;
				$this->_image->saveAs($this->getAbsolutePathImage().$filename);
	
				@unlink($this->getAbsolutePathImage().$this->image);
	
				$this->image = $filename;
			}
			
			$this->article_search = preg_replace('/[^a-zа-я\d]+/ui', '', mb_strtolower(trim($this->article)));
		
			$this->brand = mb_strtoupper($this->brand);
			
			return true;
		}
		
		return false;
	}
	
	public function getAttachment() {
		return $this->getImage();
	}
	
	public function getImage($type = '', $just_name = false) {
		if (!$just_name) {
			if (!empty($this->image) && file_exists($this->getAbsolutePathImage().$this->image))
				return 'images/PricesDataMeta/'.$this->image;
			else
				return '';
		}else {
			if (!empty($this->image) && file_exists($this->getAbsolutePathImage().$this->image))
				return $type.$this->image;
			else
				return '';
		}
	}
	
	public function getAbsolutePathImage() {
		return realpath(Yii::app()->basePath.'/..'.'/images/PricesDataMeta/').'/';
	}
	
	public function getThumb() {
		$width = 251;
		
		$sizes = getimagesize(YiiBase::getPathOfAlias('webroot').'/'.$this->getImage());
		
		$height = ceil($width * $sizes[1] / $sizes[0]);
		
		return ImageFly::Instance()->get($this, 'image', $width, $height);
	}
	
	public function search() {
		$criteria = new CDbCriteria;
		
		$criteria->compare('id', $this->id);
		$criteria->compare('article', $this->article);
		$criteria->compare('content', $this->content, true);
		
		if ($this->image == 1) {
            $criteria->addCondition("image IS NOT NULL");
        } else if ($this->image == 2)
            $criteria->addCondition("image IS  NULL");
		
		$criteria->compare('meta_title', $this->meta_title);
		$criteria->compare('meta_description', $this->meta_description);
		$criteria->compare('meta_keywords', $this->meta_keywords, true);
	
		return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
		));
	}
}