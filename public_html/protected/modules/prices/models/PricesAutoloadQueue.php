<?php

/**
 * This is the model class for table "prices_autoload_queue".
 *
 * The followings are the available columns in table 'prices_autoload_queue':
 * @property integer $id
 * @property integer $rule_id
 * @property integer $store_id
 * @property string $path
 * @property string $filename
 * @property integer $created
 */
class PricesAutoloadQueue extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'prices_autoload_queue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rule_id, store_id, path, filename', 'required'),
			array('rule_id, store_id, created', 'numerical', 'integerOnly'=>true),
			array('path, filename', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rule_id, store_id, path, filename, created', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'rule_id' => 'Rule',
			'store_id' => 'Store',
			'path' => 'Path',
			'filename' => 'Filename',
			'created' => 'Created',
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
		$criteria->compare('rule_id',$this->rule_id);
		$criteria->compare('store_id',$this->store_id);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getFirstRecord()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 'created';
        $criteria->limit = 1;

        return self::model()->find($criteria);
    }



	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PricesAutoloadQueue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
