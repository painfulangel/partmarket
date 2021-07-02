<?php

class Page extends CMyNetActiveRecord {

    public $_parent_id;
    public $_slug;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getTranslatedFields() {
        return array(
            'meta_description' => 'string',
            'meta_keywords' => 'string',
            'meta_title' => 'string',
            'page_title' => 'string',
            'content' => 'text',
                //            '' => 'string',
        );
    }

    public function tableName() {
        return 'pages' . Yii::app()->controller->module->position . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

    public function rules() {
        return array(
            array('slug, page_title, meta_title', 'required'),
            array('slug', 'checkuniqueslug'),
            array('content, meta_description, meta_keywords, parent_id, layout,order', 'safe'),
            array('parent_id', 'compare', 'operator' => '!=', 'compareAttribute' => 'id', 'allowEmpty' => true, 'message' => 'Узел не может быть сам себе родителем.'),
            array('slug', 'match', 'pattern' => '/^[\w][\w\-]*+$/', 'message' => 'Разрешённые символы: строчные буквы латинского алфавита, цифры, дефис.'),
            array('page_title', 'match', 'pattern' => '/^\d+$/', 'not' => true, 'message' => 'Заголовок страницы не может состоять из одного числа.'), // иначе будут проблемы при генерации хлебных крошек
            array('layout', 'default', 'setOnEmpty' => true, 'value' => null),
            array('is_published', 'boolean'),
            array('id, slug, page_title, is_published', 'safe', 'on' => 'search'),
        );
    }

    public function checkuniqueslug($attribute) {
        $db = Yii::app()->db;
        $sql = "SELECT id FROM pages_top WHERE slug='$this->slug' LIMIT 1";
        $id = $db->createCommand($sql)->queryScalar();
        if ($id != null && $id != $this->id)
            $this->addError($attribute, Yii::t('pages', 'Use a unique alias'));
        else {
            $sql = "SELECT id FROM pages_left WHERE slug='$this->slug' LIMIT 1";
            $id = $db->createCommand($sql)->queryScalar();
            if ($id != null && $id != $this->id)
                $this->addError($attribute, Yii::t('pages', 'Use a unique alias'));
        }
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'lft' => Yii::t('pages', 'Left key'),
            'rgt' => Yii::t('pages', 'Right key'),
            'level' => Yii::t('pages', 'Level'),
            'parent_id' => Yii::t('pages', 'Parent'),
            'slug' => Yii::t('pages', 'Text sensor'),
            'layout' => Yii::t('pages', 'Template'),
            'is_published' => Yii::t('pages', 'Published'),
            'page_title' => Yii::t('pages', 'Title'),
            'content' => Yii::t('pages', 'The text of the page'),
            'meta_title' => Yii::t('pages', 'Meta title'),
            'meta_description' => Yii::t('pages', 'Description'),
            'meta_keywords' => Yii::t('pages', 'Keywords'),
        );
    }

    public function defaultScope() {
        if (empty($this->load_lang))
            return array(
                'order' => 'root, lft',
            );
        else
            return array();
    }

    public function scopes() {
        if (empty($this->load_lang))
            return array(
                'published' => array(
                    'condition' => 'is_published = 1',
                ),
            );
        else
            return array();
    }

    public function behaviors() {
        if (empty($this->load_lang))
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
        else
            return array();
    }

    protected function afterFind() {
        parent::afterFind();
        if (empty($this->load_lang)) {
            $this->_parent_id = $this->parent_id;
            $this->_slug = $this->slug;
        }
    }

    public function afterSave() {
        parent::afterSave();
        if (empty($this->load_lang))
            if ($this->parent_id !== $this->_parent_id || $this->slug !== $this->_slug)
                Yii::app()->getModule('pages' . Yii::app()->controller->module->position)->updatePathsMap();
    }

    public function afterUpdateOrder() {
        Yii::app()->getModule('pages' . Yii::app()->controller->module->position)->updatePathsMap();
    }

    protected function afterDelete() {
        parent::afterDelete();
        if (empty($this->load_lang))
            Yii::app()->getModule('pages' . Yii::app()->controller->module->position)->updatePathsMap();
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('slug', $this->slug, true);
        $criteria->compare('page_title', $this->page_title, true);
        $criteria->compare('is_published', $this->is_published);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));
    }

    public function getBreadcrumbs() {
        $this->tableSchema->name = $this->tableName();
        $this->tableSchema->rawName = '`' . $this->tableName() . '`';

        $ancestors = $this->ancestors()->findAll();
        $output = array();
        foreach ($ancestors as $ancestor)
            $output[$ancestor->page_title] = ''; //Yii::app()->urlManager->createUrl('/pages' . Yii::app()->controller->module->position . '/default/view', array('id' => $ancestor->id));
        array_push($output, $this->page_title);
        return $output;
    }

    /**
     * Формирует массив из страниц для использования в выпадающем меню, например, при выборе родителя узла.
     */
    public function selectList() {
        $output = array();
        $nodes = $this->findAll();
        foreach ($nodes as $node)
            $output[$node->id] = str_repeat('  ', $node->level - 1) . $node->page_title;
        return $output;
    }

    public function InitOrderFunc() {
        $this->slug = 'temp' . time();
        $this->page_title = 'temp';
        $this->meta_title = 'temp';
    }

}
