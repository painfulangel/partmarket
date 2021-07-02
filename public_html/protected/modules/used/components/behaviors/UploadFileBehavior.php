<?php

/**
 * Yii upload file behaviour
 *
 * @author Demin Dmitry <sizemail@gmail.com>
 * @link http://size.perm.ru
 * @copyright 2014 SiZE
 * @since 1.15
 */
class UploadFileBehavior extends CActiveRecordBehavior {

	/**
	 * @var string Validator name according to CValidator::createValidator name attribute
	 */
	public $validatorName = 'file';

	/**
	 * @var string Model file attribute
	 */
	public $attribute = 'file';

	/**
	 * @var string Upload path alias. Default to root /upload dir.
	 */
	public $pathAlias = 'webroot.upload';

	/**
	 * File Validator properties
	 */
	public $allowEmpty;
	public $types;
	public $mimeTypes;
	public $minSize;
	public $maxSize;
	public $tooLarge;
	public $tooSmall;
	public $wrongType;
	public $wrongMimeType;
	public $maxFiles;
	public $tooMany;
	public $safe;

	/**
	 * Base Validator properties
	 */
	public $message;
	public $skipOnError;
	
	/**
	 * Some properties to set from config
	 */
	public $on;
	protected $except;
	protected $enableClientValidation;

	/**
	 * Additional properties
	 */

	/**
	 * @var bool Save original name on true or generate new.
	 */
	public $originalName = false;

	/**
	 * @var array Scenarios
	 * This can be a string value with scenario name or scenario name
	 * as a key with file validator params
	 * array( 'insert', 'update' )
	 * array( 'insert' => array( 'types' => 'jpg,png,gif' ), 'update' )
	 */
	public $scenarios = array( 'insert', 'update' );

	/**
	 * @var string Path to upload dir. Generates from $pathAlias.
	 */
	public $path;

	/**
	 * @param array $rules additional validators
	 */
	public $rules = array();

	public function attach( $owner ){
		parent::attach( $owner );
	}

	protected function setupFileValidatorParams(){
		$params = array();
		
		if ( $this->allowEmpty !== null ) {
			$params[ 'allowEmpty' ] = $this->allowEmpty;
		}
		if ( $this->types !== null ) {
			$params[ 'types' ] = $this->types;
		}
		if ( $this->mimeTypes !== null ) {
			$params[ 'mimeTypes' ] = $this->mimeTypes;
		}
		if ( $this->minSize !== null ) {
			$params[ 'minSize' ] = $this->minSize;
		}
		if ( $this->maxSize !== null ) {
			$params[ 'maxSize' ] = $this->maxSize;
		}
		if ( $this->tooLarge !== null ) {
			$params[ 'tooLarge' ] = $this->tooLarge;
		}
		if ( $this->tooSmall !== null ) {
			$params[ 'tooSmall' ] = $this->tooSmall;
		}
		if ( $this->wrongType !== null ) {
			$params[ 'wrongType' ] = $this->wrongType;
		}
		if ( $this->wrongMimeType !== null ) {
			$params[ 'wrongMimeType' ] = $this->wrongMimeType;
		}
		if ( $this->maxFiles !== null ) {
			$params[ 'maxFiles' ] = $this->maxFiles;
		}
		if ( $this->tooMany !== null ) {
			$params[ 'tooMany' ] = $this->tooMany;
		}
		if ( $this->safe !== null ) {
			$params[ 'safe' ] = $this->safe;
		}
		
		if ( $this->message !== null ) {
			$params[ 'message' ] = $this->message;
		}
		if ( $this->skipOnError !== null ) {
			$params[ 'skipOnError' ] = $this->skipOnError;
		}
		if ( $this->enableClientValidation !== null ) {
			$params[ 'enableClientValidation' ] = $this->enableClientValidation;
		}
		if ( $this->on !== null ) {
			$params[ 'on' ] = $this->on;
		}
		if ( $this->except !== null ) {
			$params[ 'except' ] = $this->except;
		}

		foreach( $this->scenarios as $scenario => $items ) if ( is_array( $items ) && $this->owner->scenario == $scenario ) {
			foreach( $items as $k => $v ) if ( property_exists( $this, $k ) ) {
				$params[ $k ] = $v;
			}
		}
		
		return $params;
	}

	protected function scenarioPass(){
		$pass = false;
		foreach( $this->scenarios as $k => $v ){
			$pass = is_array( $v ) ? $this->owner->scenario == $k : $this->owner->scenario == $v;
			if ( $pass ) {
				break;
			}
		}
		return $pass;
	}

	public function beforeValidate( $event ){
		if ( !$this->scenarioPass() ) {
			return;
		}

		$fileValidator = CValidator::createValidator( $this->validatorName, $this->owner, $this->attribute, $this->setupFileValidatorParams() );
		$this->owner->validatorList->add( $fileValidator );
	}

	public function afterValidate( $event ){
		if ( !$this->scenarioPass() ) {
			return;
		}

		if ( $this->owner->hasErrors() && !$this->owner->hasErrors( $this->attribute ) ) {	
			$file = CUploadedFile::getInstance( $this->owner, $this->attribute );
			if ( $file !== null ) {
				$this->owner->addError( $this->attribute, 'Не забудьте заново указать файл в поле «'.$this->owner->getAttributeLabel( $this->attribute ).'».' );
			}
		}
	}

	public function beforeSave( $event ){
		if ( !$this->scenarioPass() ) {
			return;
		}

		if ( !( $file = CUploadedFile::getInstance( $this->owner, $this->attribute ) ) ) {
			return;
		}
		// Delete current file before new upload
		$this->deleteCurrentFile();

		$filename = $this->originalName ? $file->name : $this->generateFileName( $file );

		$this->owner->{$this->attribute} = $filename;

		$file->saveAs( $this->uploadPath.$filename );
	}

	public function beforeDelete( $event ){
		$this->deleteCurrentFile();
	}

	public function getUploadPath(){
		if ( !$this->path ) {
			$path = Yii::getPathOfAlias( $this->pathAlias ).DIRECTORY_SEPARATOR;
			if ( !is_dir( $path ) ) {
				mkdir( $path, 0777, true );
				chmod( $path, 0777 );
			}
			$this->path = $path;
		}

		return $this->path;
	}

	public function deleteCurrentFile(){
		$filePath = $this->uploadPath.$this->owner->{$this->attribute};
		if ( @is_file( $filePath ) ) {
			@unlink( $filePath );
		}
	}

	public function generateFileName( CUploadedFile $file, $length = 8 ){
		$extension = $file->getExtensionName();
		$name = mb_substr( md5( uniqid( mt_rand(), true ) ), 0, $length );
		$filename = $name.'.'.$extension;

		if ( file_exists( $this->uploadPath.$filename ) ) {
			return $this->generateFileName( $file, $length );
		} else {
			return $filename;
		}
	}

}
