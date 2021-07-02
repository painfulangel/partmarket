<?php
$this->breadcrumbs = array(
    Yii::t('katalogTO', 'Catalogue TI') => array('brands'),
    $seo_model->name,
);

$this->metaTitle = Yii::t('katalogTO', 'Spare parts TI на') . ' ' . $seo_model->name;
$this->metaDescription = Yii::t('katalogTO', 'Spare parts TI на') . ' ' . $seo_model->name;
$this->metaKeywords = Yii::t('katalogTO', 'Spare parts TI на') . ' ' . $seo_model->name;
?>

<h1><?= Yii::t('katalogTO', 'Spare parts TI на') . ' ' . $seo_model->name ?></h1>

<?php
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $model->search(),
    'itemView' => '_models',
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
//            'value' => '(!empty($data->img)?CHtml::image("/images/KatalogTO/cars/$data->img",$data->title,array("style"=>"max-width: 100px;max-height: 100px;")):"")',
//            'htmlOptions' => array('style' => 'text-align: center; width:50px;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'name',
//            'type' => 'raw',
//            'value' => '"<b>$data->name</b>"',
//            'htmlOptions' => array('style' => 'text-align: center;vertical-align: middle;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'content',
//            'type' => 'raw',
//            'htmlOptions' => array('style' => 'text-align: center;vertical-align: middle;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => '',
//            'type' => 'raw',
//            'value' => 'CHtml::link("Смотреть модификации",array("/katalogTO/katalogTO/types","id"=>$data->id),array("id"=>"item".$data->id,"target"=>"_blank","onclick"=>"return false;"))',
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
        float: left;
        margin: 10px;
    }
    .katalog-to_div p{
        padding-left: 110px;
    }
    .katalog-to_div h3{
        padding-left: 110px; margin-bottom:0;
        font-size: 14px; line-height:30px;
        color: #000;
    }
    .katalog-to_div{
    	display: inline-block;
   	 height: 87px;
   	 vertical-align: top;
   	 width: 308px; margin-bottom:10px;
    }
    .katalog-to_div:hover{
        background: #eeeeee;
        cursor: pointer;
    }  
</style>
