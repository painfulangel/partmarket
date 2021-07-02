<?php
if (!empty($model->id)) {
    $this->breadcrumbs = array(
        Yii::t('prices', 'Prices') => array('index'),
        Yii::t('prices', 'Viewing of a price') => array('', 'id' => $model->price_id),
        Yii::t('prices', 'Viewing of a detail'),
    );

    $data = $model->search()->getData();
    $this->metaKeywords = $this->metaDescription = $this->pageTitle = Yii::app()->config->get('Site.SiteName') . ' | ' . $data[0]->name . ' | ' . $data[0]->original_article . ' | ' . $data[0]->brand;
} else {
    $this->breadcrumbs = array(
        Yii::t('prices', 'Prices') => array('index'),
        Yii::t('prices', 'Viewing of a price')
    );
    $this->pageTitle = Yii::t('prices', 'Viewing of a price');
}
?>
<h1><?= Yii::t('prices', 'Viewing of a price') ?></h1>
<?php
//CHtml::link(, array(\'\', \'id\' => $data->price_id, \'article_id\' => $data->id))
if (empty($model->id)) {
    $this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'prices-grid',
        'dataProvider' => $model->search(),
        //'filter' => $model,
        'ajaxUpdate' => false,
        'columns' => array(
            array(
                'class' => 'bootstrap.widgets.TbDataColumn',
                'type' => 'raw',
                'name' => 'brand',
                'value' => 'CHtml::link($data->brand, array(\'\', \'id\' => $data->price_id, \'article_id\' => $data->id))'
            ),
            array(
                'class' => 'bootstrap.widgets.TbDataColumn',
                'type' => 'raw',
                'name' => 'original_article',
                'value' => 'CHtml::link($data->original_article, array(\'\', \'id\' => $data->price_id, \'article_id\' => $data->id))'
            ),
            array(
                'class' => 'bootstrap.widgets.TbDataColumn',
                'type' => 'raw',
                'name' => 'name',
                'value' => 'CHtml::link($data->name, array(\'\', \'id\' => $data->price_id, \'article_id\' => $data->id))'
            ),
            array(
                'class' => 'bootstrap.widgets.TbDataColumn',
                'type' => 'raw',
                'name' => 'price',
                'value' => function($data) use ($model_price, $model_store) {
                    $temp = Yii::app()->getModule('prices')->getCartFormData(array('model' => $data, 'model_price' => $model_price, 'model_store' => $model_store));
                    return $temp['price_echo'];
                }, //'Yii::app()->getModule(\'prices\')->getPriceFormatFunction($data->price)'
                    ),
                    array(
                        'class' => 'bootstrap.widgets.TbDataColumn',
                        'type' => 'raw',
                        'name' => 'quantum',
                        'value' => '$data->quantum'
                    ),
                    array(
                        'class' => 'bootstrap.widgets.TbDataColumn',
                        'type' => 'raw',
                        'name' => 'delivery',
                        'value' => '$data->delivery'
                    ),
                    array(
//'class' => 'bootstrap.widgets.TbDataColumn',
                        'type' => 'raw',
//            'value' => 'function($data) use ($model_price,$model_store){return Yii::app()->getModule(\'prices\')->getCartFormData(array(\'model\'=>$data,\'model_price\'=>$model_price,\'model_store\'=>$model_store));}',
//'name' => 'delivery',
// 'value' => 'function($data) use ($model_price,$model_store){echo "dssdsd";return Yii::app()->getModule(\'shop_cart\')->getBlockForm(Yii::app()->getModule(\'prices\')->getCartFormData(array(\'model\'=>$data,\'model_price\'=>$model_price,\'model_store\'=>$model_store)));}',
                        'value' => function($data) use ($model_price, $model_store) {
                            return Yii::app()->getModule('shop_cart')->getForm(Yii::app()->getModule('prices')->getCartFormData(array('model' => $data, 'model_price' => $model_price, 'model_store' => $model_store)));
                        },
                                'htmlOptions' => array('style' => 'width: 70px;'),
                            ),
                        ),
                    ));
                } else {
                    $this->widget('bootstrap.widgets.TbGridView', array(
                        'id' => 'prices-grid',
                        'dataProvider' => $model->search(),
                        //'filter' => $model,
                        'ajaxUpdate' => false,
                        'columns' => array(
                            array(
                                'class' => 'bootstrap.widgets.TbDataColumn',
                                'type' => 'raw',
                                'name' => 'brand',
                                'value' => '$data->brand'
                            ),
                            array(
                                'class' => 'bootstrap.widgets.TbDataColumn',
                                'type' => 'raw',
                                'name' => 'original_article',
                                'value' => '$data->original_article'
                            ),
                            array(
                                'class' => 'bootstrap.widgets.TbDataColumn',
                                'type' => 'raw',
                                'name' => 'name',
                                'value' => '$data->name'
                            ),
                            array(
                                'class' => 'bootstrap.widgets.TbDataColumn',
                                'type' => 'raw',
                                'name' => 'price',
                                'value' => function($data) use ($model_price, $model_store) {
                                    $temp = Yii::app()->getModule('prices')->getCartFormData(array('model' => $data, 'model_price' => $model_price, 'model_store' => $model_store));
                                    return $temp['price_echo'];
                                }, //'Yii::app()->getModule(\'prices\')->getPriceFormatFunction($data->price)'
                                    ),
                                    array(
                                        'class' => 'bootstrap.widgets.TbDataColumn',
                                        'type' => 'raw',
                                        'name' => 'quantum',
                                        'value' => '$data->quantum'
                                    ),
                                    array(
                                        'class' => 'bootstrap.widgets.TbDataColumn',
                                        'type' => 'raw',
                                        'name' => 'delivery',
                                        'value' => '$data->delivery'
                                    ),
                                    array(
//'class' => 'bootstrap.widgets.TbDataColumn',
                                        'type' => 'raw',
//            'value' => 'function($data) use ($model_price,$model_store){return Yii::app()->getModule(\'prices\')->getCartFormData(array(\'model\'=>$data,\'model_price\'=>$model_price,\'model_store\'=>$model_store));}',
//'name' => 'delivery',
// 'value' => 'function($data) use ($model_price,$model_store){echo "dssdsd";return Yii::app()->getModule(\'shop_cart\')->getBlockForm(Yii::app()->getModule(\'prices\')->getCartFormData(array(\'model\'=>$data,\'model_price\'=>$model_price,\'model_store\'=>$model_store)));}',
                                        'value' => function($data) use ($model_price, $model_store) {
                                            return Yii::app()->getModule('shop_cart')->getForm(Yii::app()->getModule('prices')->getCartFormData(array('model' => $data, 'model_price' => $model_price, 'model_store' => $model_store)));
                                        },
                                                'htmlOptions' => array('style' => 'width: 70px;'),
                                            ),
                                        ),
                                    ));
                                }
                                ?>
