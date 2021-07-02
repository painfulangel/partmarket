<?php

/**
 * This is the model class for table "used_nodes".
 *
 * The followings are the available columns in table 'used_nodes':
 * @property integer $id
 * @property string $name
 * @property integer $sort
 *
 * The followings are the available model relations:
 * @property UsedItems[] $usedItems
 * @property UsedUnits[] $usedUnits
 */
class UsedNodes extends CActiveRecord
{
	private static $_asTree;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'used_nodes';
	}

	public function behaviors()
    {
            return array(
				'SortableModel' => array(
					'class' => 'application.modules.used.components.behaviors.SortableModelBehavior',
					/* optional parameters */
					'orderField' => 'sort',
				),
            );
    }

	/*public function defaultScope()
	{
		return array(
			'condition' => 'order by sort asc',
		);
	}*/
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('sort', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, sort', 'safe', 'on'=>'search'),
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
			'usedItems' => array(self::HAS_MANY, 'UsedItems', 'node_id'),
			'usedUnits' => array(self::HAS_MANY, 'UsedUnits', 'node_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sort',$this->sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => array(
				'defaultOrder' => 't.sort asc',
			),
		));
	}

	/**
	 * Получить корневые категории узлов
	 * @return array|mixed|null
	 */
	public static function getTree()
	{
		if(empty(self::$_asTree))
		{
			$rows = self::model()->findAll();
			foreach ($rows as $item)
			{
				self::$_asTree[] = UsedUnits::getChildNodes($item);
			}
		}
		return self::$_asTree;
	}

	/**
	 * Генерация дерева для узлов и агрегатов
	 * @param $modification
	 * @return array
	 */
	public function generateTree($modification)
	{
		/**
		 * Получить все узлы
		 */
		$models = self::model()->findAll();
		
		$data = array();

		foreach ($models as $category)
		{
			//echo CVarDumper::dump($category->id,10,true);
			/**
			 * Полчить количество деталей для этого узла
			 */
			$issetIems = UsedItems::model()->countByAttributes(array('node_id'=>$category->id));

			//echo CVarDumper::dump($issetIems,10,true);
			//if($issetIems)
			//{
				$data[$category->id] = array(
					'id'   => 'node-'.$category->id,
					'text' => CHtml::ajaxLink(
						$text = $category->name,
						$url = '/used/nodes/view/id/'.$category->id.'/mod/'.$modification->id,
						$ajaxOptions=array (
							'type'=>'GET',
							'dataType'=>'html',
							'success'=>'function(html){ jQuery("#node-'.$category->id.' a").css("font-weight","bold"); jQuery("#node").val('.$category->id.'); jQuery("#items-list-view").html(html);jQuery("#unit-form").html(""); }'
						),
						$htmlOptions=array ('class'=>'')
					) //'<a href="/used/nodes/view/id/'.$category->id.'/mod/'.$modification->id.'">'.$category->name.'</a>',

				);

				foreach ($category->usedUnits as $item)
				{
					//$issetIemsUnit = UsedItems::model()->countByAttributes(array('unit_id'=>$item->id));
					//if($issetIemsUnit)
					//{
						$data[$category->id]['children'][$item->id] = array(
							'id'   => 'unit-'.$item->id,
							'text' =>  CHtml::ajaxLink(
								$text = $item->name,
								$url = '/used/units/view/id/'.$item->id.'/mod/'.$modification->id,
								$ajaxOptions=array (
									'type'=>'GET',
									'dataType'=>'html',
									'success'=>'function(html){jQuery("#unit-'.$item->id.' a").css("font-weight","bold");jQuery("#node").val('.$category->id.'); jQuery("#unit").val('.$item->id.'); jQuery("#items-list-view").html(html);jQuery("#unit-form").html(""); }'
								),
								$htmlOptions=array ('class'=>'')
							), //'<a href="/used/units/view/id/'.$item->id.'/mod/'.$modification->id.'">'.$item->name.'</a>',
							'expanded' => false,
						);
					//}

				}
			//}


		}
		return $data;
	}


	/**
	 * Генерация дерева для фронтенда
	 * Выбираются только те узлы и агрегаты у которых есть детали
	 * но только для этой модификации
	 * @param $modification
	 * @return array
	 */
	public function generateFrontTree($modification)
	{
		/**
		 * Получить все узлы
		 */
		$models = self::model()->findAll();

		$data = array();

		foreach ($models as $category)
		{
			//echo CVarDumper::dump($category->id,10,true);
			/**
			 * Полчить количество деталей для этого узла и модификации авто
			 * @todo попробовать привязать вместе с таблицей применяемости.
			 *
			 */
			//$issetIems = UsedItems::model()->countByAttributes(array('node_id'=>$category->id, 'mod_id'=>$modification->id));
			$criteria = new CDbCriteria();
			$criteria->compare('node_id', $category->id);
			$criteria->compare('t.mod_id', $modification->id);
			$criteria->with = 'usedItemsUsages';

			$issetIems = UsedItems::model()->count($criteria);


			//echo CVarDumper::dump($issetIems,10,true);
			/**
			 * Если детали есть, заполняем массив для дерева
			 */
			if($issetIems)
			{
				/**
				 * Корневые узлы дерева
				 */
				$data[$category->id] = array(
					'id'   => 'node-'.$category->id,
					'text' => CHtml::ajaxLink(
						$text = $category->name,
						$url = '/used/cars/node/id/'.$category->id.'/mod/'.$modification->id,
						$ajaxOptions=array (
							'type'=>'GET',
							'dataType'=>'html',
							'success'=>'function(html){ jQuery("#node-'.$category->id.' a").css("font-weight","bold"); jQuery("#node").val('.$category->id.'); jQuery("#items-list-view").html(html);jQuery("#unit-form").html(""); }'
						),
						$htmlOptions=array ('class'=>'')
					) //'<a href="/used/nodes/view/id/'.$category->id.'/mod/'.$modification->id.'">'.$category->name.'</a>',

				);

				/**
				 * Если есть дочерние узлы(агрегаты)
				 * Заполняем массив агрегатов для дерева
				 */
				foreach ($category->usedUnits as $item)
				{
					$issetIemsUnit = UsedItems::model()->countByAttributes(array('unit_id'=>$item->id, 'mod_id'=>$modification->id));
					if($issetIemsUnit)
					{
						$data[$category->id]['children'][$item->id] = array(
							'id'   => 'unit-'.$item->id,
							'text' =>  CHtml::ajaxLink(
								$text = $item->name,
								$url = '/used/cars/unit/id/'.$item->id.'/mod/'.$modification->id,
								$ajaxOptions=array (
									'type'=>'GET',
									'dataType'=>'html',
									'success'=>'function(html){jQuery("#unit-'.$item->id.' a").css("font-weight","bold");jQuery("#node").val('.$category->id.'); jQuery("#unit").val('.$item->id.'); jQuery("#items-list-view").html(html);jQuery("#unit-form").html(""); }'
								),
								$htmlOptions=array ('class'=>'')
							), //'<a href="/used/units/view/id/'.$item->id.'/mod/'.$modification->id.'">'.$item->name.'</a>',
							'expanded' => false,
						);
					}

				}
			}


		}
		return $data;
	}

	public function generateFrontTreeTest($modification)
	{
		//$sql = "SELECT i.node_id FROM used_items i LEFT JOIN used_items_usage iu ON i.id=iu.item_id WHERE i.mod_id={$modification->id} GROUP BY i.id ";
		$sql = "SELECT node_id FROM used_items WHERE mod_id={$modification->id} OR id in (select item_id from used_items_usage where mod_id={$modification->id})";
		$tmp = Yii::app()->db->createCommand($sql)->queryAll();
		$nodes_idx = array();
		foreach ($tmp as $node) {
			$nodes_idx[]=$node['node_id'];
		}
		//echo CVarDumper::dump($nodes_idx,10,true);

		/**
		 * Получить все узлы
		 */
		$models = self::model()->findAllByAttributes(array('id'=>$nodes_idx));


		//echo CVarDumper::dump($tmp,10,true);exit;
		//echo CVarDumper::dump($models,10,true);exit;

		$data = array();

		foreach ($models as $category)
		{
			//echo CVarDumper::dump($category,10,true);
			/**
			 * Полчить количество деталей для этого узла и модификации авто
			 * @todo попробовать привязать вместе с таблицей применяемости.
			 *
			 */
			//$issetIems = UsedItems::model()->countByAttributes(array('node_id'=>$category->id, 'mod_id'=>$modification->id));
			$criteria = new CDbCriteria();
			$criteria->compare('node_id', $category->id);
			$criteria->compare('t.mod_id', $modification->id);
			$criteria->with = 'usedItemsUsages';

			$issetIems = UsedItems::model()->count($criteria);

			/**
			 * Временно ставим флаг, что есть детали
			 */
			$issetIems = true;

			//echo CVarDumper::dump($issetIems,10,true);
			/**
			 * Если детали есть, заполняем массив для дерева
			 */
			if($issetIems)
			{
				/**
				 * Корневые узлы дерева
				 */
				$data[$category->id] = array(
					'id'   => 'node-'.$category->id,
					'text' => CHtml::ajaxLink(
						$text = $category->name,
						$url = '/used/cars/node/id/'.$category->id.'/mod/'.$modification->id,
						$ajaxOptions=array (
							'type'=>'GET',
							'dataType'=>'html',
							'success'=>'function(html){ jQuery("#node-'.$category->id.' a").css("font-weight","bold"); jQuery("#node").val('.$category->id.'); jQuery("#items-list-view").html(html);jQuery("#unit-form").html(""); }'
						),
						$htmlOptions=array ('class'=>'')
					) //'<a href="/used/nodes/view/id/'.$category->id.'/mod/'.$modification->id.'">'.$category->name.'</a>',

				);

				/**
				 * Если есть дочерние узлы(агрегаты)
				 * Заполняем массив агрегатов для дерева
				 * @todo попробовать устроить такую же выборку как выше
				 * ВРОДЕ БЫ ТУТ ПРАВИЛЬНО ВЫВЕДЕНЫ АГРЕГАТЫ НО НАДО ПРОВЕРИТЬ
				 */
				//echo CVarDumper::dump($category->usedUnits,10,true);
				foreach ($category->usedUnits as $item)
				{
					$sqlUnit = "SELECT * FROM used_items WHERE (mod_id={$modification->id} OR id in (select item_id from used_items_usage where mod_id={$modification->id})) AND unit_id={$item->id}";
					$issetIemsUnit = Yii::app()->db->createCommand($sqlUnit)->queryScalar();
					//$issetIemsUnit = UsedItems::model()->countByAttributes(array('unit_id'=>$item->id));
					if($issetIemsUnit)
					{
						$data[$category->id]['children'][$item->id] = array(
							'id'   => 'unit-'.$item->id,
							'text' =>  CHtml::ajaxLink(
								$text = $item->name,
								$url = '/used/cars/unit/id/'.$item->id.'/mod/'.$modification->id,
								$ajaxOptions=array (
									'type'=>'GET',
									'dataType'=>'html',
									'success'=>'function(html){jQuery("#unit-'.$item->id.' a").css("font-weight","bold");jQuery("#node").val('.$category->id.'); jQuery("#unit").val('.$item->id.'); jQuery("#items-list-view").html(html);jQuery("#unit-form").html(""); }'
								),
								$htmlOptions=array ('class'=>'')
							), //'<a href="/used/units/view/id/'.$item->id.'/mod/'.$modification->id.'">'.$item->name.'</a>',
							'expanded' => false,
						);
					}

				}
			}


		}
		return $data;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsedNodes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
