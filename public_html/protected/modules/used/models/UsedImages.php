<?php

/**
 * This is the model class for table "used_images".
 *
 * The followings are the available columns in table 'used_images':
 * @property integer $id
 * @property integer $item_id
 * @property string $image
 *
 * The followings are the available model relations:
 * @property UsedItems $item
 */
class UsedImages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'used_images';
	}

	public function behaviors()
	{
		return array(
			/*'uploadFile' => array(
				// Path alias to extension php file
				'class' => 'application.modules.used.components.behaviors.UploadFileBehavior',
				// Model attribute, default is 'file'
				'attribute' => 'image',
				'allowEmpty' => true,
				//Для update сделать атрибут небезопасным,во избежание массового присваивания и перезаписи
				'scenarios'=>array( 'insert', 'update'=>array('safe'=>false) ),
				// Path alias to your upload dir
				'pathAlias' => 'webroot.uploads.brands',
			)*/
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_id, image', 'required'),
			array('item_id', 'numerical', 'integerOnly'=>true),
			array('image', 'length', 'max'=>255),
			array('image', 'unsafe', 'on'=>'update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, item_id, image', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'item' => array(self::BELONGS_TO, 'UsedItems', 'item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'item_id' => 'Item',
			'image' => 'Image',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('image',$this->image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsedImages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
