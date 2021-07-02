<?php
$this->breadcrumbs = array(
    Yii::t('katalogTO', 'Catalogue TI'),
);

$this->metaTitle = Yii::t('katalogTO', 'Spare parts TI technical inspection of the car');
$this->metaDescription = Yii::t('katalogTO', 'Spare parts TI technical inspection of the car');
$this->metaKeywords = Yii::t('katalogTO', 'Spare parts TI technical inspection of the car');
?>

<h1><?= Yii::t('katalogTO', 'Spare parts for TI (technical inspection)') ?></h1>

<?php
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $model->search(),
    'itemView' => '_brands',
//     'ajaxUpdate' => false,
    'template' => "{items}",
    'id' => 'wto-cars-grid',
//    'selectionChanged' => 'js:function(id) 
//  {
//  var_id=$.fn.yiiGridView.getSelection(id);
// document.location=$("#item"+var_id).attr("href");
//  return false;   
//  }',
));
?>

<?php
//$this->widget('bootstrap.widgets.TbGridView', array(
//    'id' => 'wto-cars-grid',
//    'dataProvider' => $model->search(),
//    'template' => '{items}',
//    'selectionChanged' => 'js:function(id) 
//  {
//  var_id=$.fn.yiiGridView.getSelection(id);
// document.location=$("#item"+var_id).attr("href");
//  return false;   
//  }',
//    'columns' => array(
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => '',
//            'type' => 'raw',
//            'value' => '(!empty($data->img)?CHtml::image("/images/KatalogTO/logos/$data->img",$data->title,array("style"=>"max-width: 40px;max-height: 40px;")):"")',
//            'htmlOptions' => array('style' => 'text-align: center; width:50px;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'name',
//            'type' => 'raw',
//            'value' => '"<b>$data->name</b>"',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => '',
//            'type' => 'raw',
//            'value' => 'CHtml::link("Смотреть автомобили",array("/katalogTO/katalogTO/models","id"=>$data->id),array("id"=>"item".$data->id,"target"=>"_blank","onclick"=>"return false;"))',
//            'htmlOptions' => array('style' => 'text-align: center;vertical-align: middle;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
//    ),
//));
?>
<div class="clear"></div>
<style>
    tbody tr:hover{
        background: #eeeeee;
        cursor: pointer;
    }
    .katalog-to_div img{
        /*float: left;*/
        margin:10px;
    }
    .katalog-to_div{
        width: 270px; height:70px;
        float: left;
    }
    .katalog-to_div:hover{
        background: #eeeeee;
        cursor: pointer;
    }
    .katalog-to_div h3{
        padding-left:20px;
        font-size: 18px;
        color: #000;
        display: inline;
        line-height: 60px;
    }
    .katalog-to_div img{
        /*vertical-align: middle;*/
        display: inline;
    }
</style>