<?php

/**
 * This is the model class for table "used_mod".
 *
 * The followings are the available columns in table 'used_mod':
 * @property integer $id
 * @property integer $brand_id
 * @property integer $model_id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $text
 * @property string $image
 * @property integer $sort
 *
 * The followings are the available model relations:
 * @property UsedItems[] $usedItems
 * @property UsedItemsUsage[] $usedItemsUsages
 * @property UsedModels $model
 * @property UsedBrands $brand
 */
class UsedMod extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'used_mod';
	}
	
	public function behaviors()
    {
            return array(
                'SlugBehavior' => array(
                    'class' => 'application.modules.used.components.behaviors.SlugBehavior',
                    'slug_col' => 'slug',
                    'title_col' => 'name',
                    'overwrite' => true
                ),
				'uploadFile' => array(
					// Path alias to extension php file
					'class' => 'application.modules.used.components.behaviors.UploadFileBehavior',
					// Model attribute, default is 'file'
					'attribute' => 'image',
					'allowEmpty' => true,
					//Для update сделать атрибут небезопасным,во избежание массового присваивания и перезаписи
					'scenarios'=>array( 'insert', 'update'=>array('safe'=>false) ),
					// Path alias to your upload dir
					'pathAlias' => 'webroot.uploads.models',
				),
				'SortableModel' => array(
					'class' => 'application.modules.used.components.behaviors.SortableModelBehavior',
					/* optional parameters */
					'findField'=>'name',
					'orderField' => 'sort',
				),
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
			array('brand_id, model_id, name', 'required'),
			array('brand_id, model_id, sort', 'numerical', 'integerOnly'=>true),
			array('name, slug, title, image', 'length', 'max'=>255),
			array('keywords, description, text', 'safe'),
			array('image', 'unsafe', 'on'=>'update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, brand_id, model_id, name, slug, title, keywords, description, text, image, sort', 'safe', 'on'=>'search'),
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
			'usedItems' => array(self::HAS_MANY, 'UsedItems', 'mod_id'),
			'usedItemsUsages' => array(self::HAS_MANY, 'UsedItemsUsage', 'mod_id'),
			'model' => array(self::BELONGS_TO, 'UsedModels', 'model_id'),
			'brand' => array(self::BELONGS_TO, 'UsedBrands', 'brand_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'brand_id' => Yii::t(UsedModule::TRANSLATE_PATH, 'Brand'),
			'model_id' => Yii::t(UsedModule::TRANSLATE_PATH, 'Model'),
			'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Name'),
			'slug' => Yii::t(UsedModule::TRANSLATE_PATH, 'Slug'),
			'title' => Yii::t(UsedModule::TRANSLATE_PATH, 'Title'),
			'keywords' => Yii::t(UsedModule::TRANSLATE_PATH, 'Keywords'),
			'description' => Yii::t(UsedModule::TRANSLATE_PATH, 'Description'),
			'text' => Yii::t(UsedModule::TRANSLATE_PATH, 'Text'),
			'image' => Yii::t(UsedModule::TRANSLATE_PATH, 'Image'),
			'sort' => Yii::t(UsedModule::TRANSLATE_PATH, 'Sort'),
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
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('sort',$this->sort);
		
		$criteria->order = 'brand_id';
		//$criteria->order = 'model_id';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return string image url or default image
	 */
	public function getImageUrl()
	{
		if($this->image)
		{
			return "/uploads/models/".$this->image;
		}
		else
		{
			return "/uploads/models/default.jpg";
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsedMod the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
