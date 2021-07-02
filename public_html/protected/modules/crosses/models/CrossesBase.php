<?php
class CrossesBase extends CMyActiveRecord {
    public $files_upload;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'crosses_base';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('name', 'required'),
				array('name', 'length', 'max' => 45),
				array('create_date', 'length', 'max' => 20),
				array('active_state, garanty, look_for_coincidence', 'numerical', 'integerOnly' => true),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('id, name, create_date, look_for_coincidence', 'safe', 'on' => 'search'),
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => Yii::t('crosses', 'Name'),
			'create_date' => Yii::t('crosses', 'Creation date'),
            'garanty' => Yii::t('crosses', 'Guaranteed cross'),
            'active_state' => Yii::t('crosses', 'Activate cross'),
			'look_for_coincidence' => Yii::t('crosses', "Don't look for coincidences"),
        	'files_upload' => Yii::t('crosses', 'Files upload'),
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
	
		$criteria = new CDbCriteria;
	
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		
		if ($this->active_state == Yii::t('crosses', 'Yes'))
			$criteria->compare('active_state', 1);
		else if ($this->active_state == 'Нет')
			$criteria->compare('active_state', 0);
		else
			$criteria->compare('active_state', $this->active_state);
		
		if ($this->garanty == Yii::t('crosses', 'Yes'))
			$criteria->compare('garanty', 1);
		else if ($this->garanty == Yii::t('crosses', 'No'))
			$criteria->compare('garanty', 0);
		else
			$criteria->compare('garanty', $this->garanty);
		
		return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
		));
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CMyActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Crosses the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->isNewRecord) {
				$this->create_date = strtotime(date('d.m.Y'));
			}
			return true;
		} else
			return false;
	}
	
	//Экспорт базы кроссов
	public function exportCroses() {
		$parts_id = array('brands' => array(), 'article' => array());
		$db = Yii::app()->db;
		$dataModel = new CrossesData;
		$data = $db->createCommand('SELECT * FROM '.$dataModel->tableName().'  WHERE base_id = '.$this->primaryKey)->queryAll();
		$export = 'ориг.номер*;неориг номер*;бренд ориг;бренд неориг'."\n";
	
		foreach ($data as $value) {
			if (!isset($parts_id['article'][$value['partsid']])) {
				$parts_id['article'][$value['partsid']] = $value['origion_article'];
				$parts_id['brands'][$value['partsid']] = $value['origion_brand'];
			} else {
				//                print_r($parts_id);
				$export .= $value['origion_article'].';'.$parts_id['article'][$value['partsid']].';'.$value['origion_brand'].';'.$parts_id['brands'][$value['partsid']]."\n";
			}
		}
		return iconv('UTF-8', 'cp1251', $export);
	}
	
	public function deleteAllSubCrosses($base_id) {
        $db = Yii::app()->db;
        
		$dataModel = new Crosses;
		$db->createCommand('DELETE FROM '.$dataModel->tableName().'  WHERE  `base_id`='.$base_id)->query();
		
		$dataModel = new CrossesData;
		$db->createCommand('DELETE FROM '.$dataModel->tableName().'  WHERE  `base_id`='.$base_id)->query();
	}
	
	public function filesUpload() {
		$items = Crosses::model()->findAllByAttributes(array('base_id' => $this->primaryKey, 'processed' => '0'));
		$count = count($items);
		
		$texts = array();
		
		$j = 1;
		
		for ($i = 0; $i < $count; $i ++) {
			if ($items[$i]->file_count > 0)
				$texts[] = 'Файл '.($j ++).': обработано '.number_format($items[$i]->start_row, 0, '.', ' ').' записей из '.number_format($items[$i]->file_count, 0, '.', ' ');
		}
		
		return implode('; ', $texts);
	}
}