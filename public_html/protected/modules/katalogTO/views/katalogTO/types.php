<?php
$this->breadcrumbs = array(
    Yii::t('katalogTO', 'Catalogue TI') => array('brands'),
    $seo_model->car->name => array('models', 'id' => $seo_model->car_id),
    $seo_model->name,
);
$this->metaDescription = Yii::t('katalogTO', 'Spare parts TI for').' '.$seo_model->car->name . ' ' . $seo_model->name;
$this->metaKeywords = Yii::t('katalogTO', 'Spare parts TI for').' '.$seo_model->car->name . ' ' . $seo_model->name;
$this->pageTitle = Yii::t('katalogTO', 'Spare parts TI for').' '.$seo_model->car->name . ' ' . $seo_model->name;
?>

<h1><?= Yii::t('katalogTO', 'Spare parts TI for') ?> <?= $seo_model->car->name . ' ' . $seo_model->name ?></h1>
<div class="katlogTo-item-detail">
    <?php
    echo (!empty($seo_model->img)?CHtml::image("/images/KatalogTO/cars/$seo_model->img",$seo_model->title,array("style"=>"max-width: 100px;max-height: 100px;")):"");
   
    ?>
    <p>
        <?= $seo_model->car->name . ' ' . $seo_model->name?>
    </p>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'wto-cars-grid',
    'dataProvider' => $model->search(),
    'template' => '{items}',
    'selectionChanged' => 'js:function(id) 
  {
  var_id=$.fn.yiiGridView.getSelection(id);
 document.location=$("#item"+var_id).attr("href");
  return false;   
  }',
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => '',
//            'name' => 'name',
            'type' => 'raw',
            'value' => '"<b> ".$data->model->car->name." ".$data->model->name." $data->name</b>"',
            'htmlOptions' => array(
                    'style' => 'text-align: left;vertical-align: middle;',
                'aria-label'=>'Название'
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'type_year',
            'name' => '',
            'type' => 'raw',
            'value' => '" ".$data->type_year.""',
            'htmlOptions' => array(
                    'style' => 'text-align: center;vertical-align: middle;',
                'aria-label'=>$model->getAttributeLabel("type_year")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => '',
//            'name' => 'engine',
            'type' => 'raw',
            'value' => '" ".$data->engine_model." ".$data->engine.(!empty($data->engine_horse)?" ".$data->engine_horse." л.с.":"").""',
            'htmlOptions' => array(
                    'style' => 'text-align: center;vertical-align: middle;',
                'aria-label'=>$model->getAttributeLabel("engine")
            ),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'name' => '',
            'type' => 'raw',
            'value' => 'CHtml::link("Смотреть детали",array("/katalogTO/katalogTO/items","id"=>$data->id),array("id"=>"item".$data->id,"target"=>"_blank","onclick"=>"return false;"))',
            'htmlOptions' => array(
                    'style' => 'text-align: center;vertical-align: middle;',
                //'aria-label'=>$model->getAttributeLabel("engine")
            ),
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
