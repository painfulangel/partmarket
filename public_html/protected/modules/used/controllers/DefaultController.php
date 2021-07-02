<?php

class DefaultController extends UsedController
{
    public $layout = '//layouts/admin_column2';


    public function actionIndex()
    {
        $this->render('index');
    }
}