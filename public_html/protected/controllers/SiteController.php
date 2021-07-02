<?php

class SiteController extends Controller
{

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function loadModel($id)
    {
        $model = PricesFtpAutoloadRules::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionPage()
    {
        $this->layout = "//layouts/column2_test";
        $this->render('page');
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $url = Yii::app()->config->get('Site.SiteIndexPage');

        $pathsMap_top = Yii::app()->getModule('pages_top')->getPathsMap();
        $id = array_search($url, $pathsMap_top);
        if ($id == NUll) {
            $pathsMap_left = Yii::app()->getModule('pages_left')->getPathsMap();
            $id = array_search($url, $pathsMap_left);
        } else {
            $_GET['id'] = $id;
            $this->forward('/pages_top/default/view', 1);
        }
        if ($id == false)
            throw new CHttpException(404, "Страница не найдена");
        $_GET['id'] = $id;
        $this->forward('/pages_left/default/view', 1);
//        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else

//            if ($error['code'] == '404')
//                $this->render('error404', $error);
//            else
                $this->render('error', $error);
        }
    }

    public function actionSitemap()
    {
        $result_urls = array();
        foreach (Yii::app()->params['sitemap'] as $value) {
            foreach (Yii::app()->getModule($value)->getSitemap() as $key => $value) {
                $result_urls[$key] = $value;
            }
        }

        //print_r($result_urls);

        $this->render('sitemap', array('data' => $result_urls));
    }

    public function GetContents($dir, $files = array())
    {
        if (!($res = opendir($dir)))
            exit("Нет такой директории...");
        while (($file = readdir($res)) == TRUE)
            if ($file != "." && $file != "..")
                if (is_dir("$dir/$file")) {
                    array_push($files, "$dir/$file");
                    $files = $this->GetContents("$dir/$file", $files);
                } else
                    array_push($files, "$dir/$file");
        closedir($res);
        return $files;
    }

}
