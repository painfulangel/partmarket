<?php

/**
 * This is the model class for table "used_item_sets".
 *
 * The followings are the available columns in table 'used_item_sets':
 * @property integer $id
 * @property integer $item_id
 * @property integer $brand_item_id
 * @property integer $name
 * @property string $vendor_code
 * @property string $original_num
 * @property string $replacement
 * @property integer $type
 * @property integer $state
 * @property string $comment
 * @property string $price
 * @property integer $delivery_time
 * @property integer $availability
 * @property integer $created_at
 * @property integer $updated_at
 *
 * The followings are the available model relations:
 * @property UsedItems $item
 * @property UsedSetsImages[] $usedSetsImages
 */
class UsedItemSets extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'used_item_sets';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('item_id, name, vendor_code, original_num, replacement, comment, price, delivery_time, availability, created_at, updated_at', 'required'),
			array('item_id, brand_item_id, type, state, delivery_time, availability, created_at, updated_at', 'numerical', 'integerOnly'=>true),
			array('vendor_code, name, original_num, replacement', 'length', 'max'=>255),
			array('price', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, item_id, name, vendor_code, original_num, replacement, type, state, comment, price, delivery_time, availability, created_at, updated_at', 'safe', 'on'=>'search'),
		);
	}

	public function afterSave()
	{
		if(!$this->vendor_code)
		{
			$this->updateByPk($this->id, array('vendor_code'=>UsedItems::GEN_PREFIX_VENDOR_CODE.$this->id));

		}
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
			'usedSetsImages' => array(self::HAS_MANY, 'UsedSetsImages', 'set_id'),
			'brandItem' => array(self::BELONGS_TO, 'UsedBrandsItems', 'brand_item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'item_id' => Yii::t(UsedModule::TRANSLATE_PATH, 'Item'),
			'brand_item_id'=>Yii::t(UsedModule::TRANSLATE_PATH, 'Brand Item'),
			'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Name'),
			'vendor_code' => Yii::t(UsedModule::TRANSLATE_PATH, 'Vendor Code'),
			'original_num' => Yii::t(UsedModule::TRANSLATE_PATH, 'Original Num'),
			'replacement' => Yii::t(UsedModule::TRANSLATE_PATH, 'Replacement'),
			'type' => Yii::t(UsedModule::TRANSLATE_PATH, 'Type'),
			'state' => Yii::t(UsedModule::TRANSLATE_PATH, 'State'),
			'comment' => Yii::t(UsedModule::TRANSLATE_PATH, 'Comment'),
			'price' => Yii::t(UsedModule::TRANSLATE_PATH, 'Price'),
			'delivery_time' => Yii::t(UsedModule::TRANSLATE_PATH, 'Delivery Time'),
			'availability' => Yii::t(UsedModule::TRANSLATE_PATH, 'Availability'),
			'created_at' => Yii::t(UsedModule::TRANSLATE_PATH, 'Created At'),
			'updated_at' => Yii::t(UsedModule::TRANSLATE_PATH, 'Updated At'),
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
		$criteria->compare('name',$this->name);
		$criteria->compare('vendor_code',$this->vendor_code,true);
		$criteria->compare('original_num',$this->original_num,true);
		$criteria->compare('replacement',$this->replacement,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('state',$this->state);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('delivery_time',$this->delivery_time);
		$criteria->compare('availability',$this->availability);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Возвращает массив производителей автозапчастей для выпадающего списка
	 * @return array
	 */
	public function getBrandsItem()
	{
		return CHtml::listData(UsedBrandsItems::model()->findAll(), 'id', 'name');
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsedItemSets the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
