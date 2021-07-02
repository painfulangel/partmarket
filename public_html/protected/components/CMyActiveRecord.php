<?php
class CMyActiveRecord extends CActiveRecord {
    protected $original_data = false;
    protected $load_lang = '';
    protected $tranlatedFields = array();
    protected $tranlatedModels = array();
    protected $dataArray = null;
    protected $all_langs = NULL;

    public function __construct($scenario = 'insert', $lang = '') {
        if (!empty($lang))
            $this->load_lang = $lang;
        $this->tranlatedFields = $this->getTranslatedFields();
        parent::__construct($scenario);
    }

    public function getTranslatedFields() {
        return $this->tranlatedFields;
    }

    public function getList() {
        if ($this->dataArray == null) {
            $db = Yii::app()->db;
            $data = $db->createCommand('SELECT * FROM ' . $this->tableName())->queryAll();
            $this->dataArray = array();
            foreach ($data as $value) {
                $this->dataArray[$value['id']] = $value['name'];
            }
        }
        return $this->dataArray;
    }

    public function loadTranslateModel($lang) {
        $this->load_lang = $lang;
        $classname = get_class($this);
        $model = new $classname('insert', $lang);
        $data = false;
        if ($this->getPrimaryKey() > 0)
            $data = Yii::app()->db->createCommand('SELECT * FROM `' . $this->tableName() . '` WHERE ' . $this->tableSchema->primaryKey . '=' . $this->getPrimaryKey() . ' LIMIT 1')->queryRow();
        if ($data) {
            $model = new $classname('update', $lang);
            $model->setPrimaryKey($this->getPrimaryKey());
            foreach ($this->getTranslatedFields() as $key => $value) {
                $model->{$key} = $data[$key];
            }
            //file_put_contents(Yii::getPathOfAlias('webroot').'/info.txt', '1 - '.$classname.' - '.get_class($model->tableSchema).' - '.$model->tableSchema->name.' - '.$model->tableName()."\n", FILE_APPEND);
        	
            $model->tableSchema->name = $model->tableName();
            $model->tableSchema->rawName = '`' . $model->tableName() . '`';
            
            $this->tranlatedModels[$lang] = $model;
        }
        $this->load_lang = '';
    }

    public function createTranslatedModel($lang) {
        $this->load_lang = $lang;
        $tables = array();
        if (get_class($this) == 'Page') {
            $tables[] = 'pages_left_' . $this->load_lang;
            $tables[] = 'pages_top_' . $this->load_lang;
        } else {
            $tables[] = $this->tableName();
        }
        foreach ($tables as $table_name) {
            $insert_fields = array();
            foreach ($this->getTranslatedFields() as $field_name => $field_type) {
                $type = $field_type;
                if ($field_type == 'string') {
                    $type = 'varchar(255)';
                }
                $insert_fields[] = " `$field_name` $type COLLATE utf8_unicode_ci NOT NULL ,\n";
            }
            Yii::app()->db->createCommand('CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
        `' . $this->tableSchema->primaryKey . '` int(10) unsigned NOT NULL AUTO_INCREMENT,
        ' . implode(' ', $insert_fields) . '
        PRIMARY KEY (`' . $this->tableSchema->primaryKey . '`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;')->query();
        }
        $this->load_lang = '';
    }

    public function getTranslatedModel($lang, $allow_empty = false) {
        $this->loadTranslateModel($lang);
        if ($allow_empty && !isset($this->tranlatedModels[$lang])) {
            $classname = get_class($this);
            $model = new $classname('insert', $lang);
            $model->setPrimaryKey($this->getPrimaryKey());
            $model->tableSchema->name = $model->tableName();
            $model->tableSchema->rawName = '`' . $model->tableName() . '`';
            $model->disableBehaviors();

            $this->tableSchema->name = $this->tableName();
            $this->tableSchema->rawName = '`' . $this->tableName() . '`';

            $this->tranlatedModels[$lang] = $model;
        }

        return $this->tranlatedModels[$lang];
    }

    public function afterSave() {
        parent::afterSave();

        $new_flag = false;
        if (count($this->tranlatedFields) > 0) {
            if (empty($this->load_lang)) {
                foreach ($this->langsList() as $lang) {
                    $model = null;
                    $classname = get_class($this);
                    if ($this->isNewRecord) {
                        $model = new $classname('insert', $lang['link_name']);
                        $model->setPrimaryKey($this->getPrimaryKey());
                        $new_flag = true;
                    } else {
                        if (!isset($this->tranlatedModels[$lang['link_name']])) {
                            $this->loadTranslateModel($lang['link_name']);
                        }
                        if (!isset($this->tranlatedModels[$lang['link_name']])) {
                            $model = new $classname('insert', $lang['link_name']);
                            $model->setPrimaryKey($this->getPrimaryKey());
                            $new_flag = true;
                        } else {
                            $model = $this->tranlatedModels[$lang['link_name']];
                        }
                    }
                    $atrs = $this->getTranslatedFields();
                    foreach ($this->getAttributes(true) as $key => $at) {
                        if (!isset($atrs[$key]))
                            unset($model->{$key});
                    }
                    foreach ($this->getTranslatedFields() as $field_name => $field_type) {
                        if (isset($_POST[$classname . '_' . $lang['link_name']][$field_name])) {
                            $model->{$field_name} = $_POST[$classname . '_' . $lang['link_name']][$field_name];
                        } else
                            $model->{$field_name} = '';
                    }
                    $atrs = array();
                    foreach ($this->getTranslatedFields() as $key => $value) {
                        $atrs[] = $key;
                    }
                    $model->tableSchema->name = $model->tableName();
                    $model->tableSchema->rawName = '`' . $model->tableName() . '`';

                    if ($model->getPrimaryKey() != $this->getPrimaryKey()) {

                        $model->setPrimaryKey($this->getPrimaryKey());
                    }
                    if (!$new_flag) {
                        $model->scenario = 'update';
                        $model->isNewRecord = false;
                    } else {
                        
                    }
                    $model->disableBehaviors();
                    $model->save(false);
                    $this->tableSchema->name = $this->tableName();
                    $this->tableSchema->rawName = '`' . $this->tableName() . '`';
                }
            }
        }
    }

    public function langsList() {
        if ($this->all_langs == NULL) {
            $this->all_langs = array();
            $models = Yii::app()->db->createCommand('SELECT * FROM `languages` WHERE `active` = 1')->queryAll();
            foreach ($models as $model) {
                $this->all_langs[$model['id']] = array('id' => $model['id'], 'short_name' => $model['short_name'], 'name' => $model['name'], 'link_name' => $model['link_name']);
            }
        }
        return $this->all_langs;
    }

    public function deleteTranslatedModel($lang) {
        $this->load_lang = $lang;
        $tables = array();
        if (get_class($this) == 'Page') {
            $tables[] = 'pages_left_' . $this->load_lang;
            $tables[] = 'pages_top_' . $this->load_lang;
        } else {
            $tables[] = $this->tableName();
        }
        foreach ($tables as $table_name) {
            Yii::app()->db->createCommand('DROP TABLE IF EXISTS `' . $table_name . '`')->query();
        }
        $this->load_lang = '';
    }

    public function getTranslatedAttributes($name, $lang) {
        $flag = false;
        foreach ($this->langsList() as $row) {
            if ($row['link_name'] == $lang) {
                $flag = true;
            }
        }
        if ($flag && isset($this->tranlatedFields[$name])) {
            if (!isset($this->tranlatedModels[$lang])) {
                $this->loadTranslateModel($lang);
            }if (isset($this->tranlatedModels[$lang][$name]) && !empty($this->tranlatedModels[$lang][$name])) {
                return $this->tranlatedModels[$lang][$name];
            }
        }
    }

    public function __get($name) {
    	//file_put_contents(Yii::getPathOfAlias('webroot').'/info.txt', '00 - '.get_class($this).' - '.$name."\n", FILE_APPEND);
    	
        if (!$this->original_data) {
            $flag = false;
            foreach ($this->langsList() as $row) {
                if ($row['link_name'] == Yii::app()->language) {
                    $flag = true;
                }
            }
            
            //file_put_contents(Yii::getPathOfAlias('webroot').'/info.txt', '01 - '.get_class($this).' - '.$name."\n", FILE_APPEND);
            
            if ($flag && isset($this->tranlatedFields[$name]) && empty($this->load_lang)) {
            	//file_put_contents(Yii::getPathOfAlias('webroot').'/info.txt', '02 - '.get_class($this).' - '.$name."\n", FILE_APPEND);
            	
                if (!isset($this->tranlatedModels[Yii::app()->language])) {
                	//file_put_contents(Yii::getPathOfAlias('webroot').'/info.txt', '03 - '.get_class($this).' - '.$name."\n", FILE_APPEND);
                	
                    $this->loadTranslateModel(Yii::app()->language);
                }
                if (isset($this->tranlatedModels[Yii::app()->language][$name]) && !empty($this->tranlatedModels[Yii::app()->language][$name])) {
                    return $this->tranlatedModels[Yii::app()->language][$name];
                }
            }
        }
        return parent::__get($name);
    }

    public static function model($className = __CLASS__) {
        return parent::
                model($className);
    }
}