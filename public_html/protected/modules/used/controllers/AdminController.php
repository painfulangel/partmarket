<?php

class AdminController extends UsedController
{
    public $layout = '//layouts/admin_column2';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('index','searchNodes','searchItems','brands'),
                'roles' => array('mainManager', 'admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $model = new CActiveDataProvider('UsedBrands', array(
            'criteria'=> array(
                'order'=>'name',
            ),
        ));
        
        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * Вернуть страницу с марками автомобилей
     * @throws CException
     */
    public function actionBrands()
    {
        $model = new CActiveDataProvider('UsedBrands', array(
          'criteria'=>array(
              'order'=>'name',
        )));

        echo $this->renderPartial('index_applicat',array(
            'model'=>$model,
        ), false, true);
    }

    public function actionSearchNodes()
    {
        $result = array();

        $query = $_POST['q'];

        $models = UsedUnits::model()->findAll(
            'name LIKE :match',
            array(':match' => "%$query%")
        );

        $result['status']['result'] = true;
        $result['status']['message'] = '';

        foreach ($models as $model) {
            $result['grops'][] = $model->attributes;

        }

        echo json_encode($result);
    }

    public function actionSearchItems()
    {
        $result = array();

        $query = $_POST['q'];
        $modification_id = intval($_POST['mid']);

        $models = UsedItems::model()->findAll(
            'name LIKE :match OR vendor_code LIKE :match AND mod_id=:id',
            array(':match' => "%$query%", ':id'=>$modification_id)
        );

        $result['status']['result'] = true;
        $result['status']['message'] = '';

        foreach ($models as $model) {
            $result['grops'][] = $this->renderPartial('_view_search', array('model'=>$model), true, false);//$model->attributes;

        }

        echo json_encode($result);
    }
}