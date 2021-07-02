<?php

/**
 * This is the model class for table "used_items".
 *
 * The followings are the available columns in table 'used_items':
 * @property integer $id
 * @property integer $brand_id
 * @property integer $model_id
 * @property integer $mod_id
 * @property integer $node_id
 * @property integer $unit_id
 * @property integer $brand_item_id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $vendor_code
 * @property string $original_num
 * @property string $replacement
 * @property integer $type
 * @property integer $state
 * @property string $comment
 * @property string $price
 * @property integer $delivery_time
 * @property integer $availability
 * @property integer $set
 * @property integer $created_at
 * @property integer $updated_at
 *
 * The followings are the available model relations:
 * @property UsedImages[] $usedImages
 * @property UsedUnits $unit
 * @property UsedBrands $brand
 * @property UsedModels $model
 * @property UsedMod $mod
 * @property UsedNodes $node
 * @property UsedItemsUsage[] $usedItemsUsages
 */
class UsedItems extends CActiveRecord
{
	/**
	 * Тип запчасти, новая/бу
	 */
	const TYPE_NEW = 0;
	const TYPE_USED = 1;
	
	/**
	 * Состояние детали
	 */
	const STATE_EXELLENT = 5;//Отличное
	const STATE_GOOD = 4;//хорошее
	const STATE_INCOMPLETE = 3;//некомплект
	const STATE_BROKEN = 2;//сломанная
	const STATE_DEFECT = 1;//с дефектом

	const IMAGES_PATH = '/uploads/items/';

	const PRICE_NAME = 'default_part_use.xls';

	const GEN_PREFIX_VENDOR_CODE = 'ZSPU';

	public $images;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'used_items';
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
				'CTimestampBehavior' => array(
					'class' => 'zii.behaviors.CTimestampBehavior',
					'createAttribute' => 'created_at',
					'updateAttribute' => 'updated_at',
				)
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
			array('brand_id, model_id, mod_id, node_id, unit_id, brand_item_id, name, availability', 'required'),
			array('brand_id, model_id, mod_id, node_id, unit_id, brand_item_id, type, state, delivery_time, availability, set, created_at, updated_at', 'numerical', 'integerOnly'=>true),
			array('name, slug, title, vendor_code, original_num, replacement', 'length', 'max'=>255),
			array('original_num', 'default', 'value'=>0),
			array('price', 'length', 'max'=>12),
            array('title, keywords, description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, brand_id, model_id, mod_id, node_id, unit_id, name, vendor_code, original_num, replacement, type, state, comment, price, delivery_time, availability, created_at, updated_at', 'safe', 'on'=>'search'),
		);
	}

	public function beforeSave()
	{
		if (parent::beforeSave())
		{
			$this->title = $this->name;
		}
		return true;
	}
	

	protected function afterSave()
	{
		if($this->images)
		{
			foreach ($this->images as $image => $pic)
			{
				if(!is_dir(Yii::getPathOfAlias('webroot')."/uploads/items/$this->id"))
				{
					mkdir(Yii::getPathOfAlias('webroot')."/uploads/items/$this->id", 0777, true);
				}

				if ($pic->saveAs(Yii::getPathOfAlias('webroot').'/uploads/items/'.$this->id.'/'.$pic->name))
				{
					$model = new UsedImages();
					$model->image = $pic->name;
					$model->item_id = $this->id;

					if(!$model->save())
					{
						//echo CVarDumper::dump($model->getErrors(),10,true);exit;
					}
				}
				else
				{
					//echo CVarDumper::dump($pic->getError(),10,true);exit;
				}
			}


		}


		if(!$this->vendor_code)
		{
			$this->updateByPk($this->id, array('vendor_code'=>self::GEN_PREFIX_VENDOR_CODE.$this->id));

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
			'usedImages' => array(self::HAS_MANY, 'UsedImages', 'item_id'),
			'unit' => array(self::BELONGS_TO, 'UsedUnits', 'unit_id'),
			'brand' => array(self::BELONGS_TO, 'UsedBrands', 'brand_id'),
			'brandItem' => array(self::BELONGS_TO, 'UsedBrandsItems', 'brand_item_id'),
			'model' => array(self::BELONGS_TO, 'UsedModels', 'model_id'),
			'mod' => array(self::BELONGS_TO, 'UsedMod', 'mod_id'),
			'node' => array(self::BELONGS_TO, 'UsedNodes', 'node_id'),
			'usedItemsUsages' => array(self::HAS_MANY, 'UsedItemsUsage', 'item_id'),
			'itemSets' => array(self::HAS_MANY, 'UsedItemSets', 'item_id'),
			'usages' => array(self::MANY_MANY, 'UsedItems', 'used_items_usage(mod_id, item_id)')
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
			'mod_id' => Yii::t(UsedModule::TRANSLATE_PATH, 'Modification'),
			'node_id' => Yii::t(UsedModule::TRANSLATE_PATH, 'Node'),
			'unit_id' => Yii::t(UsedModule::TRANSLATE_PATH, 'Unit'),
			'brand_item_id'=>Yii::t(UsedModule::TRANSLATE_PATH, 'Brand Item'),
			'name' => Yii::t(UsedModule::TRANSLATE_PATH, 'Name'),
            'slug' => Yii::t(UsedModule::TRANSLATE_PATH, 'Slug'),
            'title' => Yii::t(UsedModule::TRANSLATE_PATH, 'Title'),
            'keywords' => Yii::t(UsedModule::TRANSLATE_PATH, 'Keywords'),
            'description' => Yii::t(UsedModule::TRANSLATE_PATH, 'Description'),
			'vendor_code' => Yii::t(UsedModule::TRANSLATE_PATH, 'Vendor Code'),
			'original_num' => Yii::t(UsedModule::TRANSLATE_PATH, 'Original Number'),
			'replacement' => Yii::t(UsedModule::TRANSLATE_PATH, 'Replacement'),
			'type' => Yii::t(UsedModule::TRANSLATE_PATH, 'Type'),
			'state' => Yii::t(UsedModule::TRANSLATE_PATH, 'State'),
			'comment' => Yii::t(UsedModule::TRANSLATE_PATH, 'Comment'),
			'price' => Yii::t(UsedModule::TRANSLATE_PATH, 'Price'),
			'delivery_time' => Yii::t(UsedModule::TRANSLATE_PATH, 'Delivery Time'),
			'availability' => Yii::t(UsedModule::TRANSLATE_PATH, 'Availability'),
			'set' => Yii::t(UsedModule::TRANSLATE_PATH, 'Set'),
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
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('mod_id',$this->mod_id);
		$criteria->compare('node_id',$this->node_id);
		$criteria->compare('unit_id',$this->unit_id);
		//$criteria->compare('name',$this->name);
		$criteria->addSearchCondition('name', $this->name);
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
	 * Типы запчасти
	 */
	public function getTypes()
	{
		return array(
			self::TYPE_USED => Yii::t(UsedModule::TRANSLATE_PATH, 'Used'),
			self::TYPE_NEW => Yii::t(UsedModule::TRANSLATE_PATH, 'New'),
		);
	}
	
	/**
	 * Строковое представление типа запчасти
	 */
	public function getType()
	{
		$types = $this->getTypes();
		return $types[$this->type];
	}
	
	public function getStates()
	{
		return array(
			self::STATE_EXELLENT => Yii::t(UsedModule::TRANSLATE_PATH, 'Exellent'),
			self::STATE_GOOD => Yii::t(UsedModule::TRANSLATE_PATH, 'Good'),
			self::STATE_INCOMPLETE => Yii::t(UsedModule::TRANSLATE_PATH, 'Incomplete'),
			self::STATE_BROKEN => Yii::t(UsedModule::TRANSLATE_PATH, 'Broken'),
			self::STATE_DEFECT => Yii::t(UsedModule::TRANSLATE_PATH, 'Defect'),
		);
	}
	
	public function getState()
	{
		$states = $this->getStates();
		return $states[$this->state];
	}

	/**
	 * Вернуть массив изображений для виджета фансибокс
	 */
	public function imagesItemsForWidget()
	{
		$images = $this->usedImages;
		$items = array();
		foreach ($images as $image) {
			$items[]=array(
				'id'=>$image->id,
				'title'=>'image',
				'image'=>'/uploads/items/'.$this->id.'/'.$image->image,
				'thumb'=>'/uploads/items/'.$this->id.'/'.$image->image,
			);
		}
		return $items;
	}

	public function imagesItemsForNivo()
	{
		$images = $this->usedImages;
		$items = array();
		foreach ($images as $image) {
			$items[]=array(
				'src'=>'/uploads/items/'.$this->id.'/'.$image->image,
				'url'=>'/uploads/items/'.$this->id.'/'.$image->image,
				'caption'=>'',
				'imageOptions'=>array(),
				'linkOptions'=>array(),
			);
		}
		return $items;
	}

	public function imagesItemsForBxImages()
	{
		$images = $this->usedImages;
		//echo CVarDumper::dump($images,10,true);
		$items = array();
		foreach ($images as $k => $image) {
			$items[$k] =  '<li>'.CHtml::link(CHtml::image('/uploads/items/'.$this->id.'/'.$image->image, ''), '/uploads/items/'.$this->id.'/'.$image->image, array('data-fancybox'=>'gallery')).'</li>';
		}
		return $items;
	}

	public function imagesItemsForBxControls()
	{
		$images = $this->usedImages;
		$items = array();
		foreach ($images as $k => $image) {
			$items[] =  CHtml::link(CHtml::image('/uploads/items/'.$this->id.'/'.$image->image, ''), '/uploads/items/'.$this->id.'/'.$image->image, array('data-slide-index'=>$k));
		}
		return $items;
	}

	/**
	 * вернуть src фото для списка запчастей
	 * @return string
	 */
	public function getFrontImage()
	{
		$images = $this->usedImages;
		if($images)
		{
			return '/uploads/items/'.$this->id.'/'.$images[0]->image;
		}
		else
		{
			return '/uploads/models/default.jpg';
		}
	}

	/**
	 * Получить дефолтную картинку для фронтенда
	 * @param $id
	 * @return string
	 */
	public static function frontImage($id)
	{
		$images = UsedImages::model()->findAllByAttributes(array('item_id'=>$id));
		if($images)
		{
			return '/uploads/items/'.$id.'/'.$images[0]->image;
		}
		else
		{
			return '/uploads/models/default.jpg';
		}
	}

	/**
	 * Получить название марки авто для фронтенда
	 * @param $id
	 * @return string
	 */
	public static function brandName($id)
	{
		$brand = UsedBrands::model()->findByPk($id);
		return $brand->name;
	}
	
	public static function stateView($state)
	{
		$states = array(
			self::STATE_EXELLENT => Yii::t(UsedModule::TRANSLATE_PATH, 'Exellent'),
			self::STATE_GOOD => Yii::t(UsedModule::TRANSLATE_PATH, 'Good'),
			self::STATE_INCOMPLETE => Yii::t(UsedModule::TRANSLATE_PATH, 'Incomplete'),
			self::STATE_BROKEN => Yii::t(UsedModule::TRANSLATE_PATH, 'Broken'),
			self::STATE_DEFECT => Yii::t(UsedModule::TRANSLATE_PATH, 'Defect'),
		);
		
		return $states[$state];
	}
	
	public static function typeView($type)
	{
		$types = array(
			self::TYPE_USED => Yii::t(UsedModule::TRANSLATE_PATH, 'Used'),
			self::TYPE_NEW => Yii::t(UsedModule::TRANSLATE_PATH, 'New'),
		);
		return $types[$type];
	}

	/**********************************************************************************
	**********************************************************************************
	 **********************************************************************************/
	/**
	 * Получить массив авто для применяемости
	 * @param $mod
	 * @return array
	 */
	public function getApplicability($mod)
	{
		$result = array();
		$modification = UsedMod::model()->findByPk($mod);
		//$apps = UsedMod::model()->findAllByAttributes(array('brand_id'=>$modification->brand_id));
		$sql = "select * from `used_mod` WHERE `brand_id`={$modification->brand_id} ORDER BY name";
		$apps = UsedMod::model()->findAllBySql($sql);
		foreach ($apps as $app) {
			$result[$app->id] = $app->name.' &bull; '.$app->model->name.' &bull; '.$app->brand->name;
		}
		return $result;
	}

	/**
	 * Экспорт данных в прайс лист
	 * @return string
	 */
	public function export()
	{
		$items = self::model()->findAll();

		$export = 'Производитель;Номер;Наименование;Цена;Кол-во;Срок поставки'."\r\n";

		foreach ($items as $item)
		{
			$export .= $item->brandItem->name.';'.$item->original_num.';'.$item->name.';'.$item->price.';'.$item->availability.';'.$item->delivery_time.';'."\n";
		}

		//return $export;
		//return mb_convert_encoding($export, 'cp1251', 'utf8');
		return iconv('UTF-8', 'cp1251', $export);
	}

	/**
	 * Вернуть данные по умолчанию для создания прайса, если нет прайса
	 * @return array
	 */
	public static function defaultPriceMetaData($key)
	{
		$data = array(
			'price_group_1' => '4',
			'price_group_2' => '2',
			'price_group_3' => '3',
			'price_group_4' => '3',
			'active_state' => '1',
			'delivery' => '0',
			'supplier_inn' => '77777777',
			'supplier' => 'MS',
			'user_id' => null,
			'create_date' => time(),
			'currency' => '5',
			'store_id' => '11',
			'search_state' => '1',
			'rule_id' => '0',
			'language' => '',
		);
		
		return $data[$key];
	}

	/**
	 * @param $vendor_code - внутренний номер детали
	 * @param array $params
	 * @return mixed
	 */
	public static function getPriceMarkup($vendor_code, $params = array())
	{
		$sql = 'SELECT `t`.`id` as `id`, `t`.`name` as `name`, `t`.`brand` as `brand`, `t`.`price` as `price`, `t`.`quantum` as `quantum`, `t`.`article` as `article`, `t`.`original_article` as `original_article`, `t`.`delivery` as `delivery`, `t`.`weight` as `weight`,'
			. "`t_price`.`id` as `price_id`, `t_price`.`name` as `price_name`, `t_price`.`delivery` as `price_delivery`, `t_price`.`price_group_$params[price_group_id]` as `price_price_group`,`t_price`.`price_group_1` as `price_price_group_1`,`t_price`.`price_group_2` as `price_price_group_2`,`t_price`.`price_group_3` as `price_price_group_3`,`t_price`.`price_group_4` as `price_price_group_4`, `t_price`.`supplier_inn` as `price_supplier_inn`, `t_price`.`supplier` as `price_supplier`, `t_price`.`currency` as `price_currency`, "
			. ' `t_store`.`name` as `store_name`, `t_store`.`description` as `store_description`, `t_store`.`top` as `store_top`, `t_store`.`highlight` as `store_highlight`, `t_store`.`count_state` as `store_count_state` '
			. 'FROM `prices_data` `t` JOIN `prices` `t_price`  ON `t`.`price_id`=`t_price`.`id` JOIN `stores` `t_store`  ON t_price.store_id=`t_store`.`id` '
			. "WHERE `t`.`extra_field1`='$vendor_code' and `t_store`.`my_available`='1' and `t_price`.`active_state`='1'";

		$data = Yii::app()->db->createCommand($sql)->queryRow();

		//получить прайс с учетом скидки и каких то других параметров
		$price = Yii::app()->getModule('prices')->getPriceFunction($data);
		$price_echo = Yii::app()->getModule('prices')->getPriceFormatFunction($price);

		return $price_echo;
	}

	/**
	 * Получить цену для корзины
	 * @param $vendor_code - внутренний номер детали
	 * @param array $params
	 * @return mixed
	 */
	public static function getPriceToCart($vendor_code, $params = array())
	{
		$sql = 'SELECT `t`.`id` as `id`, `t`.`name` as `name`, `t`.`brand` as `brand`, `t`.`price` as `price`, `t`.`quantum` as `quantum`, `t`.`article` as `article`, `t`.`original_article` as `original_article`, `t`.`delivery` as `delivery`, `t`.`weight` as `weight`,'
			. "`t_price`.`id` as `price_id`, `t_price`.`name` as `price_name`, `t_price`.`delivery` as `price_delivery`, `t_price`.`price_group_$params[price_group_id]` as `price_price_group`,`t_price`.`price_group_1` as `price_price_group_1`,`t_price`.`price_group_2` as `price_price_group_2`,`t_price`.`price_group_3` as `price_price_group_3`,`t_price`.`price_group_4` as `price_price_group_4`, `t_price`.`supplier_inn` as `price_supplier_inn`, `t_price`.`supplier` as `price_supplier`, `t_price`.`currency` as `price_currency`, "
			. ' `t_store`.`name` as `store_name`, `t_store`.`description` as `store_description`, `t_store`.`top` as `store_top`, `t_store`.`highlight` as `store_highlight`, `t_store`.`count_state` as `store_count_state` '
			. 'FROM `prices_data` `t` JOIN `prices` `t_price`  ON `t`.`price_id`=`t_price`.`id` JOIN `stores` `t_store`  ON t_price.store_id=`t_store`.`id` '
			. "WHERE `t`.`extra_field1`='$vendor_code' and `t_store`.`my_available`='1' and `t_price`.`active_state`='1'";

		$data = Yii::app()->db->createCommand($sql)->queryRow();

		//получить прайс с учетом скидки и каких то других параметров
		$price = Yii::app()->getModule('prices')->getPriceFunction($data);

		return $price;
	}

	/**
	 * Возвращает идентификатор записи в прайслисте
	 * @return int
	 */
	public function getPriceDataId()
	{
		$price = Prices::model()->findByAttributes(array('name'=>self::PRICE_NAME));

		if(!$price)
		{
			return 0;
		}

		$model = PricesData::model()->findByAttributes(array(
			'extra_field1'=>$this->vendor_code,
			'price_id'=>$price->id,
		));

		if($model)
		{
			return $model->id;
		}
		else
		{
			return 0;
		}
	}

	public function getPriceDataAll()
	{
		$price = Prices::model()->findByAttributes(array('name'=>self::PRICE_NAME));

		if(!$price)
		{
			return null;
		}

		$model = PricesData::model()->findByAttributes(array(
			'extra_field1'=>$this->vendor_code,
			'price_id'=>$price->id,
		));

		if($model)
		{
			return array('model'=>$model, 'price'=>$price);
		}
		else
		{
			return null;
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsedItems the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
