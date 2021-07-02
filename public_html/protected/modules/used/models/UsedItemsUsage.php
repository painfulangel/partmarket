<?php

/**
 * This is the model class for table "used_items_usage".
 *
 * The followings are the available columns in table 'used_items_usage':
 * @property integer $id
 * @property integer $mod_id
 * @property integer $item_id
 *
 * The followings are the available model relations:
 * @property UsedItems $item
 * @property UsedMod $mod
 */
class UsedItemsUsage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'used_items_usage';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mod_id, item_id', 'required'),
			array('mod_id, item_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mod_id, item_id', 'safe', 'on'=>'search'),
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
			'mod' => array(self::BELONGS_TO, 'UsedMod', 'mod_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'mod_id' => 'Mod',
			'item_id' => 'Item',
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
		$criteria->compare('mod_id',$this->mod_id);
		$criteria->compare('item_id',$this->item_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsedItemsUsage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
