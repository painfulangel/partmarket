<?php

class NewsModule extends CWebModule {

    public $perPage = 20;

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'news.models.*',
            'news.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }

    public function getSitemap() {
        $array = array(Yii::app()->createAbsoluteUrl('/news/default/index') => Yii::t('news', 'News'));
        
        $data = array();
        if (Yii::app()->language == Yii::app()->params['default_language']) {
            $data = Yii::app()->db->createCommand()
                    ->select('id,  title AS title, link')
                    ->from('news')
//                    ->where('parent_id=' . $id . ' and active_state=1')
//                    ->order('root, lft')
                    ->queryAll();
        } else {
            $data = Yii::app()->db->createCommand()
                    ->select('news.id as id,  IF(news_' . Yii::app()->language . '.title IS NULL OR news_' . Yii::app()->language . '.title=\'\', news.title, news_' . Yii::app()->language . '.title ) AS title, link')
                    ->from('news')
                    ->leftJoin('news_' . Yii::app()->language, 'news_' . Yii::app()->language . '.id=' . 'news.id')
//                    ->where('parent_id=\'' . $id . '\' and active_state=1')
//                    ->order('root, lft')
                    ->queryAll();
        }
//        $data = Yii::app()->db->createCommand()
//                ->select('id,  title')
//                ->from(News::model()->tableName())
//                ->queryAll();

        foreach ($data as $value) {
        	$params = array('id' => $value['id']);
        	
        	if (array_key_exists('link', $value) && (trim($value['link']) != '')) {
        		$params = array('link' => $value['link']);
        	}
        	
            $array[Yii::app()->createAbsoluteUrl('/news/default/view', $params)] = $value['title'];
        }

        return $array;
    }

}
