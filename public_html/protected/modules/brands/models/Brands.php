<?php
class Brands extends CMyActiveRecord {
    public $_image = '';
    
    public $imagePath = 'Brands/';
    public $imagePathThumb = 'Brands/thumb/';

	public function getTranslatedFields() {
        return array(
            'name' => 'string',
            'description' => 'text',
        );
    }

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
        return 'brands'.(empty($this->load_lang) ? '' : '_' . $this->load_lang);
	}

	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
        return array(
            array('name', 'required'),
            array('hide, active_state', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('description, synonym', 'safe'),
            // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
            array('id, name, description, image, synonym, hide, active_state', 'safe', 'on' => 'search'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('brands', 'Name'),
            'description' => Yii::t('brands', 'Description'),
            'image' => Yii::t('brands', 'Image'),
            '_image' => Yii::t('brands', 'Image'),
            'synonym' => Yii::t('brands', 'Synonym'),
            'hide' => Yii::t('brands', 'Hide'),
            'active_state' => Yii::t('brands', 'Active'),
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
				return 'images/Brands/'.$this->image;
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
		return realpath(Yii::app()->basePath.'/..'.'/images/Brands/').'/';
	}
	
	public function getThumb() {
		$width = 250;
		
		$sizes = getimagesize(YiiBase::getPathOfAlias('webroot').'/'.$this->getImage());
		
		$height = ceil($width * $sizes[1] / $sizes[0]);
		
		return ImageFly::Instance()->get($this, 'image', $width, $height);
	}

	public function search() {
		$criteria = new CDbCriteria;
		
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		
		if ($this->image == 1) {
            $criteria->addCondition("image IS NOT NULL");
        } else if ($this->image == 2)
            $criteria->addCondition("image IS NULL");
		
		$criteria->compare('hide', $this->hide);
		$criteria->compare('active_state', $this->active_state);
	
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}
?>