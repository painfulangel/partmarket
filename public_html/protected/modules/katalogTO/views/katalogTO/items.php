<?php
$this->breadcrumbs = array(
    Yii::t('katalogTO', 'Catalogue TI') => array('brands'),
    $seo_model->model->car->name => array('models', 'id' => $seo_model->model->car_id),
    $seo_model->model->name => array('types', 'id' => $seo_model->model_id),
    $seo_model->name,
);
$this->metaDescription = Yii::t('katalogTO', 'Spare parts TI на').' '.$seo_model->model->car->name.' '.$seo_model->model->name.' '.$seo_model->name;
$this->metaKeywords = Yii::t('katalogTO', 'Spare parts TI на').' '.$seo_model->model->car->name.' '.$seo_model->model->name.' '.$seo_model->name;
$this->pageTitle = Yii::t('katalogTO', 'Spare parts TI на').' '.$seo_model->model->car->name.' '.$seo_model->model->name.' '.$seo_model->name;
?>

<h1><?= Yii::t('katalogTO', 'Spare parts for cars') ?> <?= $seo_model->model->car->name.' '.$seo_model->model->name.' '.$seo_model->name ?></h1>
<div class="katlogTo-item-detail">
    <?php
    echo (!empty($seo_model->model->img) ? CHtml::image("/images/KatalogTO/cars/{$seo_model->model->img}", $seo_model->model->title, array("style" => "max-width: 100px;max-height: 100px;")) : "");
    ?>
    <p>
        <?= $seo_model->model->car->name.' '.$seo_model->model->name.' '.$seo_model->name ?>
    </p>
    <p>
        <?= $seo_model->type_year ?>
    </p>
</div>



<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'wto-cars-grid',
    'dataProvider' => $model->search(),
    'template' => '{items}',
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'search',
            'type' => 'raw',
            'value' => '"<b>$data->search</b>"',
            'htmlOptions' => array(
                    'style' => 'text-align: center;vertical-align: middle;',
                'aria-label'=>$model->getAttributeLabel("search")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'descr',
            'type' => 'raw',
            'value' => '"<b>$data->descr</b>"',
            'htmlOptions' => array(
                    'style' => 'text-align: center;vertical-align: middle;',
                'aria-label'=>$model->getAttributeLabel("descr")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'box',
            'type' => 'raw',
            'htmlOptions' => array(
                    'style' => 'text-align: center;vertical-align: middle;',
                'aria-label'=>$model->getAttributeLabel("box")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => 'comment',
            'type' => 'raw',
            'htmlOptions' => array(
                    'style' => 'text-align: center;vertical-align: middle;',
                'aria-label'=>$model->getAttributeLabel("comment")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => '',
            'type' => 'raw',
            'value' => 'CHtml::link("'.Yii::t('katalogTO', 'Price').'", '.(Yii::app()->config->get('Site.SearchType') == 1 ? 'array("/detailSearchNew/default/search","article"=>$data->search)' : 'array("/detailSearch/default/search","search_phrase"=>$data->search)').', array("target"=>"_blank"))',
            'htmlOptions' => array('style' => 'text-align: center;vertical-align: middle;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
    ),
));
?>

<style>
    tbody tr:hover{
        background: #eeeeee;
        cursor: pointer;
    }
    .katlogTo-item-detail img{
        float: left;
    }
</style>
