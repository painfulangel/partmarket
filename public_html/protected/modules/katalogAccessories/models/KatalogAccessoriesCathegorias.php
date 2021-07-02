<?php
/**
 * This is the model class for table "katalog_accessories_cathegorias".
 *
 * The followings are the available columns in table 'katalog_accessories_cathegorias':
 * @property integer $id
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $root
 * @property string $lft
 * @property string $rgt
 * @property string $level
 * @property string $parent_id
 * @property integer $order
 * @property string $title
 * @property string $slug
 * @property string $text
 * @property boolean $active_state 
 */
class KatalogAccessoriesCathegorias extends CMyNetActiveRecord {
    public $_parent_id;
    public $_slug;

    public function getTranslatedFields() {
        return array(
            'meta_title' => 'string',
            'meta_description' => 'string',
            'meta_keywords' => 'string',
            'title' => 'string',
            'text' => 'text',
//            '',
//            '',
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'katalog_accessories_cathegorias'.(empty($this->load_lang) ? '' : '_'.$this->load_lang);
    }

    public function init() {
        parent::init();
        $this->active_state = 1;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('slug, title, meta_title', 'required'),
            array('title', 'unique'),
            array('slug', 'checkuniqueslug'),
//            array('meta_title, meta_description, meta_keywords, root, lft, rgt, level, parent_id', 'required'),
            array('order', 'numerical', 'integerOnly' => true),
            array('meta_title, meta_description, meta_keywords, title, slug', 'length', 'max' => 255),
            array('root, lft, rgt, level, parent_id', 'length', 'max' => 10),
            array('text,active_state', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, meta_title, active_state, meta_description, meta_keywords, root, lft, rgt, level, parent_id, order, title, slug, text', 'safe', 'on' => 'search'),
        );
    }

    public function checkuniqueslug($attribute) {
        $db = Yii::app()->db;
        $sql = 'SELECT id FROM `'.KatalogAccessoriesCathegorias::model()->tableName()."` WHERE slug='$this->slug' LIMIT 1";
        $id = $db->createCommand($sql)->queryScalar();
        if ($id != null && $id != $this->id)
            $this->addError($attribute, Yii::t('katalogAccessories', 'Use a unique alias'));
        else {
            $sql = 'SELECT id FROM `'.KatalogAccessoriesItems::model()->tableName()."` WHERE slug='$this->slug' LIMIT 1";
            $id = $db->createCommand($sql)->queryScalar();
            if ($id != null && $id != $this->id)
                $this->addError($attribute, Yii::t('katalogAccessories', 'Use a unique alias'));
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'meta_title' => Yii::t('katalogAccessories', 'Meta-header'),
            'meta_description' => Yii::t('katalogAccessories', 'Description page'),
            'meta_keywords' => Yii::t('katalogAccessories', 'Keywords'),
            'root' => 'Root',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'level' => 'Level',
            'parent_id' => Yii::t('katalogAccessories', 'Procreator'),
            'order' => 'Order',
            'title' => Yii::t('katalogAccessories', 'Name'),
            'slug' => Yii::t('katalogAccessories', 'Alias'),
            'text' => Yii::t('katalogAccessories', 'Text'),
            'active_state' => Yii::t('katalogAccessories', 'Activity'),
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

        $criteria->compare('id', $this->id);
        $criteria->compare('meta_title', $this->meta_title, true);
        $criteria->compare('meta_description', $this->meta_description, true);
        $criteria->compare('meta_keywords', $this->meta_keywords, true);
        $criteria->compare('root', $this->root, true);
        $criteria->compare('lft', $this->lft, true);
        $criteria->compare('rgt', $this->rgt, true);
        $criteria->compare('level', $this->level, true);
        $criteria->compare('parent_id', $this->parent_id, true);
        $criteria->compare('order', $this->order);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('slug', $this->slug, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('active_state', $this->active_state, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function defaultScope() {
        return array(
            'order' => 'root, lft',
        );
    }

    public function scopes() {
        return array(
            'published' => array(
                'condition' => 'active_state = 1',
            ),
        );
    }

    public function behaviors() {
        return array(
            'nestedSetBehavior' => array(
                'class' => 'ext.nested-set.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'rootAttribute' => 'root',
                'hasManyRoots' => true,
            ),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CMyActiveRecord descendants!
     * @param string $className active record class name.
     * @return KatalogAccessoriesCathegorias the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    protected function afterFind() {
        parent::afterFind();

        $this->_parent_id = $this->parent_id;
        $this->_slug = $this->slug;
    }

    public function afterSave() {
        parent::afterSave();

        if ($this->parent_id !== $this->_parent_id || $this->slug !== $this->_slug)
            Yii::app()->controller->module->updatePathsMap();
    }

    public function afterUpdateOrder() {
        Yii::app()->controller->module->updatePathsMap();
    }

    protected function afterDelete() {
        parent::afterDelete();

        Yii::app()->controller->module->updatePathsMap();
    }

    public function getBreadcrumbs() {
        $ancestors = $this->ancestors()->findAll();
        $output = array();
        foreach ($ancestors as $ancestor)
            $output[$ancestor->title] = Yii::app()->urlManager->createUrl('/katalogAccessories/cathegorias/view', array('id' => $ancestor->id));
        array_push($output, $this->title);
        return $output;
    }

    public function selectList() {
        $output = array();
        $nodes = $this->findAll();
        foreach ($nodes as $node)
            $output[$node->id] = str_repeat('  ', $node->level - 1).$node->title;
        return $output;
    }

    public function moveItems($id) {
        //$db = Yii::app()->db;
        //$sql = 'UPDATE `'.KatalogAccessoriesItems::model()->tableName().'` SET  '." cathegory_id='$id' WHERE cathegory_id='$this->id' ";
        //$db->createCommand($sql)->query();
    	$command = Yii::app()->db->createCommand();
    	$command->update(KatalogAccessoriesItems::model()->tableName(), array('cathegory_id' => $id), 'cathegory_id=:cathegory_id', array(':cathegory_id' => $this->id));
    }

    public function InitOrderFunc() {
        $this->slug = 'temp'.time();
        $this->title = 'temp';
        $this->meta_title = 'temp';
    }

    public function getItemsDataProvider() {
        $criteria = new CDbCriteria;

        $ids = array("cathegory_id='$this->id'");
//        $ancestors = $this->ancestors()->findAll();
        $ancestors = $this->descendants()->findAll();
//        print_r($ancestors);
        foreach ($ancestors as $ancestor) {
            $ids[] = "cathegory_id='$ancestor->id'";
        }
//        print_r($ids);
        $criteria->addCondition(implode($ids, ' OR '));

        //  $criteria->compare('cathegory_id', $this->id);
        $criteria->compare('active_state', 1);

        return new CActiveDataProvider('KatalogAccessoriesItems', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            ),
        ));
    }

    public function importTXT($filename, $fileCharset) {
        $file = file($filename);

        $n = count($file);
        $values = '';

        $db = Yii::app()->db;
        $z = 0;
        $ids = array();
        if ($n > 1) {
            $separator = ";";
            $string = explode($separator, trim($file[0]));
            if (count($string) < 5)
                $separator = "\t";

            for ($i = 1; $i < $n; $i++) {
                if (strlen($file[$i]) < 5)
                    continue;
                $file[$i] = iconv($fileCharset, 'UTF-8', $file[$i]);
                $string = explode($separator, trim($file[$i]));
                if (count($string) < 5)
                    continue;
                
                foreach ($string as $key => $value) {
                    $string[$key] = trim($value);
                }
                
                $model = $this->model()->findByPk($string[0]);
                if (!is_object($model)) {
                    $model = new KatalogAccessoriesCathegorias;
                    
                    //file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/2.txt', $string[0].' - '.$string[1]."\n", FILE_APPEND);
                }
                
                $model->title = $string[1];
                $model->slug = $string[3];
                $model->meta_title = $string[4];
                if ($string[2] == '0') {
                    $model->parent_id = 0;
                } else {
                    $temp = $this->model()->findByAttributes(array('title' => $string[2]));
                    if ($temp != NULL) {
                        $model->parent_id = $temp->id;
                    } else
                        $model->parent_id = 0;
                }

                $temp_i = 1;
                while (!$model->validate()) {
                    $model->slug = $string[3].$temp_i;
                    $temp_i++;
                }

                if ($model->validate()) {
                    if ($model->parent_id) {
                        $parent = KatalogAccessoriesCathegorias::model()->findByPk($model->parent_id);
                        if (($parent !== null) && $model->getIsNewRecord()) {
                        	//Модель KatalogAccessoriesCathegorias
                            $model->appendTo($parent);
                        }
                    }
                    
                    //file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/2.txt', $string[1]."\n", FILE_APPEND);
                    
                    $model->saveNode();
                    
                    //file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/2.txt', "done\n", FILE_APPEND);
                }

                $ids[] = 'id!='.$model->id;
            }

			$data = $db->createCommand('DELETE FROM  '.$this->tableName().'  WHERE '.implode(' AND ', $ids))->query();
        }
    }

    public function export() {
        $db = Yii::app()->db;
        $data = $db->createCommand('SELECT *, (SELECT title FROM '.$this->tableName().' WHERE id=t.parent_id) AS parent_title FROM '.$this->tableName().' `t`')->queryAll();
        $export = 'Id;Название;Название родительськой категории;Псевдоним;Мета-заголовок'."\n";
        foreach ($data as $value) {
            $export .= $value['id'].';'.$value['title'].';'.($value['parent_id'] != 0 ? $value['parent_title'] : '0').';'.$value['slug'].';'.$value['meta_title']."\n";
        }

        return iconv('UTF-8', 'cp1251', $export);
    }
}