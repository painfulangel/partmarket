<?php
/**
 * This is the model class for table "crosses".
 *
 * The followings are the available columns in table 'crosses':
 * @property integer $id
 * @property string $name
 * @property integer $active_state
 * @property string $create_date
 */
class Crosses extends CMyActiveRecord {
    /**
     *
     * @var String upload file
     */
    public $crossFile = '';

    /**
     *
     * @var String  charset of file
     */
    public $crossCharset = 'cp1251';

    /**
     *
     * @var Boolean
     */
    public $isProgramWork = true;
    
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'crosses';
    }
    
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return Crosses the static model class
     */
    public static function model($className = __CLASS__) {
    	return parent::model($className);
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('name', 'required'),
            array('crossCharset', 'required', 'on' => 'create'),
            array('crossFile', 'file', 'allowEmpty' => false, 'types' => 'txt, csv', 'maxSize' => Yii::app()->controller->module->maxFileSize, 'on' => 'create'),
            array('name', 'length', 'max' => 45),
            array('create_date', 'length', 'max' => 20),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, base_id, name, file_name, file_count, start_row, active_state, create_date', 'safe', 'on' => 'search'),
        );
    }
    
    /**
     * @return array relational rules.
     */
    public function relations() {
    	// NOTE: you may need to adjust the relation name and the related
    	// class name for the relations automatically generated below.
    	return array(
    		'base' => array(self::HAS_ONE, 'CrossesBase', array('id' => 'base_id')),
    	);
    }
    
    public function beforeSave() {
        if ($this->isNewRecord) {
            $temp = CUploadedFile::getInstance($this, 'crossFile');
            if (empty($this->name))
                $this->name = $temp;
            
            //Название файла
            $filename = pathinfo($temp->getName());
            $this->file_name = md5(time()).'.'.$filename['extension'];
        }
        
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->create_date = strtotime(date('d.m.Y'));
                if ($this->isProgramWork) {
                    $model = Crosses::model()->findByAttributes(array('name' => $this->name));
                    if ($model != NULL) {
                        $model->deleteAllSubCrosses();
                        $model->delete();
                    }
                }
            }
            return true;
        } else
            return false;
    }

    public function afterSave() {
        parent::afterSave();
        //set_time_limit(0);
        if ($this->isNewRecord) {
			$this->crossFile = CUploadedFile::getInstance($this, 'crossFile');
			$filename = pathinfo($this->crossFile->getName());

			$filename = $this->file_name; //md5(time()).'.'.$filename['extension'];
			$this->crossFile->saveAs(Yii::app()->getModule('crosses')->pathFiles.$filename);
                
			//Сколько всего строк в файле
			$file = file(Yii::app()->getModule('crosses')->pathFiles.$filename);
			$count = count($file);
            
			if ($count) {
				Yii::app()->db->createCommand()->update($this->tableName(), array('file_count' => $count), 'id=:id', array(':id' => $this->primaryKey));
				
				//!!! Если не было, добавляем задачу планировщика
				$cron = new Crontab('my_crontab', Yii::app()->basePath . '/runtime/');
				 
				$jobs_obj = $cron->getJobs();
				 
				foreach ($jobs_obj as $key => $value) {
					if ($value->getCommandName() == 'ProcessCrossFiles')
						$cron->removeJob($key);
				}
				 
				$job = new CronApplicationJob('protected/yiic', 'ProcessCrossFiles', array(), '*/20', '*', '*');
				$cron->add($job);
				 
				$cron->saveCronFile(); // save to my_crontab cronfile
				$cron->saveToCrontab(); // adds all my_crontab jobs to system (replacing previous my_crontab jobs)
				//!!! Если не было, добавляем задачу планировщика
			}
        }
    }

    public function deleteAllSubCrosses() {
        $db = Yii::app()->db;
        $dataModel = new CrossesData;
        $db->createCommand('DELETE FROM '.$dataModel->tableName().'  WHERE  `cross_id`='.$this->id)->query();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('crosses', 'Name'),
            'create_date' => Yii::t('crosses', 'Creation date'),
            'crossFile' => Yii::t('crosses', 'File'),
            'crossCharset' => Yii::t('crosses', 'Coding'),
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        if ($this->base_id)
        	$criteria->compare('base_id', $this->base_id);
        
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        
        $criteria->compare('create_date', @strtotime($this->create_date), true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function process() {
    	$start_time = microtime(true);
    	
    	$file_name = Yii::app()->getModule('crosses')->pathFiles.$this->file_name;
    	
    	//file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/cross_error2.txt', $file_name.' --- '.file_exists($file_name)."\n", FILE_APPEND);
    	
    	if (file_exists($file_name)) {
    		$portion = intval(Yii::app()->config->get('Site.CrossCount'));
    		if (!$portion) $portion = 5000;
    		
    		$file = file($file_name);
    		$length = count($file);
    		$values = array();
    		$db = Yii::app()->db;
    		$dataModel = new CrossesData;
    		$z = 0;
    		$full_list = array();
    		
    		//Мы загружаем файл с кроссами. Если нашли в базе номер + бренд, тогда присваиваем тот же PARTS_ID, а если стоит галочка, то загружаем без этой проверке, т.е.
    		//А=Б - этой группе свой PARTS_ID и всё
    		$look_for_coincidence = 0;
    		$base = CrossesBase::model()->findByPk($this->base_id);
    		if (is_object($base)) $look_for_coincidence = intval($base->look_for_coincidence);
    		 
    		if ($length > 1) {
    			$start = $this->start_row;
    			$count = ($this->start_row + $portion) > $this->file_count ? $this->file_count : ($this->start_row + $portion);
    			
    			$separator = ";";
    			
    			//file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/cross_error2.txt', $start.' --- '.$count.' --- '.$length."\n", FILE_APPEND);
    			
    			for ($i = $start; $i < $count; $i++) {
    				//file_put_contents('/var/www/dtrus/data/www/dtrus.partexpert.ru/cross_error.txt', $i."\n", FILE_APPEND);
    				
    				if ($this->isProgramWork) {
    					//У автозагрузчика прайс-листов кодировка входящего файла всегда UTF-8, поэтому для предотвращения ошибок её нужно проверять
    					if (mb_detect_encoding($file[$i]) != $this->crossCharset) {
    						$this->crossCharset = mb_detect_encoding($file[$i]);
    					}
    				}
    	
    				if ((mb_detect_encoding($file[$i]) == $this->crossCharset) || ($this->crossCharset != 'UTF-8'))
    					$file[$i] = iconv($this->crossCharset, 'UTF-8', $file[$i]);
    				else {
    					$this->isNewRecord = false;
    					$this->delete();
    					throw new CHttpException(400, Yii::t('crosses', 'Incorrect coding').' - '.mb_detect_encoding($file[$i]).' - '.$this->crossCharset);
    				}
    				
    				//file_put_contents('/var/www/dtrus/data/www/dtrus.partexpert.ru/cross_error.txt', $i.' - '.$file[$i]."\n", FILE_APPEND);
    				 
    				if (strlen($file[$i]) < 1)
    					continue;
    				$string = explode($separator, trim($file[$i]));
    				
    				if (count($string) < 4) continue;
    				
    				for ($j = 0; $j <= 3; $j++) {
    					//$string[$j] = mb_strtoupper($string[$j]);
    					$string[$j] = mb_strtolower($string[$j]);
    				}
    				for ($j = 0; $j < 2; $j++) {
    					$string[$j] = preg_replace("/[^a-zA-Z0-9]/", "", $string[$j]);
    				}
    				$search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
    				$replace = array("\\\\", "\\0", "\\n", "\\r", "", '', "\\Z");
    				
    				$string[2] = trim(str_replace($search, $replace, $string[2]));
    				$string[3] = trim(str_replace($search, $replace, $string[3]));
    				
    				$find_brand = -1;
    				$find_article = array();
    				 
    				//Ориг. номер + ориг. бренд
    				$index1 = $string[0].$string[2];
    				 
    				//Неориг. номер + неориг. бренд
    				$index2 =  $string[1].$string[3];
    				 
    				if ($look_for_coincidence == 0) {
    					/*if (isset($full_list[$index1])) {
    						$find_brand = $full_list[$index1];
    					} elseif (isset($full_list[$index2])) {
    						$find_brand = $full_list[$index2];
    					}*/
    					
    					//if ($find_brand == -1) {
    						$cross_rows = $db->createCommand("SELECT SQL_NO_CACHE `id`, `origion_article`,`origion_brand`, `partsid` FROM `".$dataModel->tableName()."` WHERE (`origion_article`='$string[0]' OR `origion_article`='$string[1]') AND base_id = ".$this->base_id)->queryAll();
    						//Если заданы бренды
    						if (!empty($string[2]) || !empty($string[3])) {
    							foreach ($cross_rows as $cross_row) {
    								//Если задан ориг.бренд и ориг.номер есть в базе
    								//Если задан неориг. бренд и неориг.номер есть в базе
    								if ((!empty($string[2]) && $cross_row['origion_article'] == $string[0]) || (!empty($string[3]) && $cross_row['origion_article'] == $string[1])) {
    									$find_brand = $cross_row['partsid'];
    									$find_article[$cross_row['origion_article'].$cross_row['origion_brand']] = $cross_row['id'];
    								}
    							}
    						}
    						
    						foreach ($cross_rows as $cross_row) {
    							if (($cross_row['origion_article'] == $string[0] && $cross_row['origion_brand'] == $string[2]) || 
    								($cross_row['origion_article'] == $string[1] && $cross_row['origion_brand'] == $string[3])) {
    								$find_brand = $cross_row['partsid'];
    								$find_article[$cross_row['origion_article'].$cross_row['origion_brand']] = $cross_row['id'];
    							}
    						}
    					//}
    				}
    				 
    				if ($find_brand == -1) {
    					$find_brand = $db->createCommand("SELECT SQL_NO_CACHE MAX(`partsid`) AS qq FROM `".$dataModel->tableName()."`")->queryScalar();
    					if ($find_brand == NULL || empty($find_brand))
    						$find_brand = 0;
    					$find_brand++;
    					$values[] = '("'.$this->base_id.'", "'.$this->id.'", "'.$string[0].'", "'.$string[2].'", "'.$find_brand.'")';
    					$values[] = '("'.$this->base_id.'", "'.$this->id.'", "'.$string[1].'", "'.$string[3].'", "'.$find_brand.'")';
    				} else {
    					if (!isset($find_article[$index1]))
    						$values[] = '("'.$this->base_id.'", "'.$this->id.'", "'.$string[0].'", "'.$string[2].'", "'.$find_brand.'")';
    					else {
    						//Артикул уже есть в базе
    						//file_put_contents('/var/www/dtrus/data/www/dtrus.partexpert.ru/cron.txt', $i." Оригинал ".$string[0].'('.$string[2].', база '.$this->base_id.' файл '.$this->primaryKey.') уже есть в базе (id='.$find_article[$index1].')'."\n", FILE_APPEND);
    					}
    					
    					if (!isset($find_article[$index2]))
    						$values[] = '("'.$this->base_id.'", "'.$this->id.'", "'.$string[1].'", "'.$string[3].'", "'.$find_brand.'")';
    					else {
    						//Артикул уже есть в базе
    						//file_put_contents('/var/www/dtrus/data/www/dtrus.partexpert.ru/cron.txt', $i." Замена ".$string[1].'('.$string[3].', база '.$this->base_id.' файл '.$this->primaryKey.') уже есть в базе (id='.$find_article[$index2].')'."\n", FILE_APPEND);
    					}
    				}
    				 
    				if (count($values)) {
    					//echo 'INSERT INTO '.$dataModel->tableName().' (`cross_id`, `origion_article`, `origion_brand`, `partsid`) VALUES '.implode(' , ', $values).'<br>';
    					//file_put_contents('/var/www/dtrus/data/www/dtrus.partexpert.ru/query.txt', $i.' INSERT INTO '.$dataModel->tableName().' (`base_id`, `cross_id`, `origion_article`, `origion_brand`, `partsid`) VALUES '.implode(' , ', $values)."\n", FILE_APPEND);
    					
    					$db->createCommand('INSERT INTO '.$dataModel->tableName().' (`base_id`, `cross_id`, `origion_article`, `origion_brand`, `partsid`) VALUES '.implode(' , ', $values))->query();
    				}
    				
    				//file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/cross_error2.txt', count($values)."\n", FILE_APPEND);
    				 
    				$values = array();
    			}
    			
    			if ($this->start_row + $portion >= $length) {
    				$attributes = array('processed' => 1);
    				
    				unlink($file_name);
    			} else {
    				$attributes = array('start_row' => $this->start_row + $portion);
    			}
    			
    			Crosses::model()->updateByPk($this->primaryKey, $attributes);
    			//$this->save();
    		}
    	}
    	
    	//echo (microtime(true) - $start_time);
    }
}