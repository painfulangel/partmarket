<?php

class PageNet extends CMyActiveRecord {

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

    public function behaviors() {
        return array();
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'pages' . Yii::app()->controller->module->position . (empty($this->load_lang) ? '' : '_' . $this->load_lang);
    }

}
