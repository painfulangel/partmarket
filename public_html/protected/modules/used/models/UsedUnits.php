<?php

/**
 * This is the model class for table "used_units".
 *
 * The followings are the available columns in table 'used_units':
 * @property integer $id
 * @property integer $node_id
 * @property string $name
 * @property integer $sort
 *
 * The followings are the available model relations:
 * @property UsedItems[] $usedItems
 * @property UsedNodes $node
 */
class UsedUnits extends CActiveRecord
{
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'used_units';
	}
	
	public function behaviors()
    {
            return array(
				/*'SortableModel' => array(
					'class' => 'application.modules.used.components.behaviors.SortableModelBehavior',
					///* optional parameters 
					'orderField' => 'sort',
				),*/
            );
    }

	public function defaultScope(){
		return array(
			'order'=>'name ASC'
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
			array('node_id, name', 'required'),
			array('node_id, sort', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, node_id, name, sort', 'safe', 'on'=>'search'),
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
			'usedItems' => array(self::HAS_MANY, 'UsedItems', 'unit_id'),
			'node' => array(self::BELONGS_TO, 'UsedNodes', 'node_id'),
			//'usedItemsCount' => array(self::STAT, 'UsedItems', 'unit_id', 'condition'=>'mod_id={$mod->id} OR id in (select item_id from used_items_usage where mod_id={$mod->id})'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'node_id' => Yii::t(UsedModule::TRANSLATE_PATH, 'Node'),
			'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Name'),
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
		$criteria->compare('node_id',$this->node_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sort',$this->sort);
		
		$criteria->order = 'node_id';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Построить дерево
	 * @param $item
	 * @return array|void
	 */
	public static function getChildNodes($item)
	{
		$result = '';//array();
		$child = array();
		if(!$item)
		{
			return;
		}

		if($item->usedUnits)
		{
			foreach ($item->usedUnits as $usedUnit)
			{
				$child[$usedUnit->id] = array(
					'text'=>$usedUnit->name,
					'hasChildren'=>true,

				);
			}
		}

		$result = array(
			'text'=>$item->name,
			'expanded'=>false,
			'hasChildren'=>false,
			'cildren'=>$child,
		);

		return $result;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsedUnits the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
