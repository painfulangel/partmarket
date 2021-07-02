<?php

class DefaultController extends Controller {

    public function actionView($id) {
        $page = $this->loadModel($id);

        if (defined('TURNON_CITIES') && (TURNON_CITIES == true) && ($page->slug == 'contact')) {
            $city = Cities::getInfo();
            if (is_object($city)) $page->content = $city->contacts;
        }

        $this->render('view', array(
            'page' => $page,
        ));
    }

    public function loadModel($id, $slug = '') {
        if (empty($slug))
            $model = Page::model()->findByPk($id);
        else
            $model = Page::model()->findByAttributes(array('slug' => $slug));
        if ($model === null)
            throw new CHttpException(404, Yii::t('pages', 'This Page not found.'));
        return $model;
    }

}
