<?php

/**
 * @author ElisDN <mail@elisdn.ru>
 * @link http://www.elisdn.ru
 */
class DConfig extends CApplicationComponent {

    public $cache = false;
    public $dependency = null;
    protected $data = array();

    public function init() {
//        $this->setImport(array(
//            'news.models.*',
//            'news.components.*',
//        ));

        $data = array();
        
        $db = $this->getDbConnection();

        $items = $db->createCommand('SELECT * FROM config')->queryAll();

        foreach ($items as $item) {
            if ($item['param'])
                $data[$item['id']] = array('param' => $item['param'], 'value' => $item['value'] === '' ? $item['default'] : $item['value']);
        }
        
        //Yii::app()->language ещё не инициализирован, данные берём из сессии
        $lang = trim(Yii::app()->request->getQuery('_lang', ''));
        if ($lang == '' && !defined('PLAN_TASK')) $lang = Yii::app()->session['_lang'];
        
        if ($lang != '' && $lang != Yii::app()->params['default_language']) {
            $items_translated = $db->createCommand('SELECT * FROM `config_'.$lang.'`')->queryAll();
            
            foreach ($items_translated as $item) {
                // && $item['value']
                if (array_key_exists($item['id'], $data) && ($item['value'] || in_array($item['id'], array(94)))) {
                    $data[$item['id']]['value'] = $item['value'];
                }
            }
        }
        
        foreach ($data as $array) {
            $this->data[$array['param']] = $array['value'];
        }

        parent::init();
    }

    public function get($key) {
        if (array_key_exists($key, $this->data))
            return $this->data[$key];
        else
            throw new CException('Undefined parameter ' . $key);
    }

    public function set($key, $value) {
        $model = Config::model()->findByAttributes(array('param' => $key));
        if (!$model)
            throw new CException('Undefined parameter ' . $key);

        $model->value = $value;

        if ($model->save())
            $this->data[$key] = $value;
    }

    public function add($params) {
        if (isset($params[0]) && is_array($params[0])) {
            foreach ($params as $item)
                $this->createParameter($item);
        } elseif ($params)
            $this->createParameter($params);
    }

    public function delete($key) {
        if (is_array($key)) {
            foreach ($key as $item)
                $this->removeParameter($item);
        } elseif ($key)
            $this->removeParameter($key);
    }

    protected function getDbConnection() {
        if ($this->cache)
            $db = Yii::app()->db->cache($this->cache, $this->dependency);
        else
            $db = Yii::app()->db;

        return $db;
    }

    protected function createParameter($param) {
        if (!empty($param['param'])) {
            $model = Config::model()->findByAttributes(array('param' => $param['param']));
            if ($model === null)
                $model = new Config();

            $model->param = $param['param'];
            $model->label = isset($param['label']) ? $param['label'] : $param['param'];
            $model->value = isset($param['value']) ? $param['value'] : '';
            $model->default = isset($param['default']) ? $param['default'] : '';
            $model->type = isset($param['type']) ? $param['type'] : 'string';

            $model->save();
        }
    }

    protected function removeParameter($key) {
        if (!empty($key)) {
            $model = Config::model()->findByAttributes(array('param' => $key));
            if ($model)
                $model->delete();
        }
    }

}
