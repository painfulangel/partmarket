<?php
class MaslaVolume extends CMyActiveRecord {
	public $_image;
	public $imagePath = 'masla/';
	public $imagePathThumb = 'masla/thumb/';
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'masla_volume';
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
		} else {
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
}